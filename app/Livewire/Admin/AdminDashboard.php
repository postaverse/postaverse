<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\User\User;
use App\Models\Badge;
use App\Models\User\Banned;
use App\Models\Admin\AdminLogs;
use App\Models\Admin\Whitelisted;
use App\Models\Post\Post;
use App\Models\Post\Comment;
use App\Models\Post\Like;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Http\Response;
use Livewire\WithPagination;

class AdminDashboard extends Component
{
    use WithPagination;
    
    // Properties for form inputs
    public ?string $user_id = null;
    public ?string $reason = null;
    public ?string $uid = null;
    public ?string $admin_id = null;
    public ?int $admin_rank = null;
    public ?string $email = null;
    
    // Added properties for UI control and filtering
    public string $activeTab = 'overview';
    public string $searchTerm = '';
    public $confirmingAction = false;
    public $actionType = null;
    public $targetUser = null;
    
    // Define admin rank titles and permissions
    protected $adminRanks = [
        0 => ['title' => 'Regular User', 'color' => 'gray'],
        1 => ['title' => 'Junior Moderator', 'color' => 'blue'],
        2 => ['title' => 'Moderator', 'color' => 'green'],
        3 => ['title' => 'Senior Moderator', 'color' => 'purple'],
        4 => ['title' => 'Administrator', 'color' => 'indigo'],
        5 => ['title' => 'Owner', 'color' => 'red'],
    ];

    // Define permissions by rank
    protected function hasPermission($permission): bool
    {
        $userRank = Auth::user()->admin_rank;
        
        $permissions = [
            'view_dashboard' => $userRank >= 1,
            'manage_reports' => $userRank >= 1,
            'view_logs' => $userRank >= 1,
            'view_stats' => $userRank >= 1,
            'delete_comments' => $userRank >= 1,
            'temp_ban' => $userRank >= 2,
            'manage_users' => $userRank >= 2,
            'delete_posts' => $userRank >= 2,
            'perm_ban' => $userRank >= 3,
            'unban' => $userRank >= 3,
            'manage_badges' => $userRank >= 3,
            'whitelist_email' => $userRank >= 4,
            'manage_admins' => $userRank >= 4,
            'modify_system' => $userRank >= 5,
        ];
        
        return $permissions[$permission] ?? false;
    }
    
    public function getAdminRankTitle($rank): array
    {
        return $this->adminRanks[$rank] ?? ['title' => 'Unknown', 'color' => 'gray'];
    }
    
    public function setActiveTab($tab): void
    {
        $this->activeTab = $tab;
        $this->resetPage();
    }
    
    public function confirmAction($action, $userId = null): void
    {
        $this->confirmingAction = true;
        $this->actionType = $action;
        $this->targetUser = $userId;
        
        if ($action === 'ban' && $userId) {
            $this->user_id = $userId;
        } elseif ($action === 'unban' && $userId) {
            $this->uid = $userId;
        } elseif ($action === 'promote' && $userId) {
            $this->admin_id = $userId;
        }
    }
    
    public function cancelAction(): void
    {
        $this->confirmingAction = false;
        $this->actionType = null;
        $this->targetUser = null;
    }

    public function admins()
    {
        $query = User::where('admin_rank', '>=', 1);
        
        if ($this->searchTerm) {
            $query->where(function($q) {
                $q->where('name', 'like', '%' . $this->searchTerm . '%')
                  ->orWhere('email', 'like', '%' . $this->searchTerm . '%')
                  ->orWhere('id', $this->searchTerm);
            });
        }
        
        return $query->orderByDesc('admin_rank')->get();
    }
    
    public function getBannedUsers()
    {
        $query = Banned::with('user');
        
        if ($this->searchTerm) {
            $query->whereHas('user', function($q) {
                $q->where('name', 'like', '%' . $this->searchTerm . '%')
                  ->orWhere('email', 'like', '%' . $this->searchTerm . '%')
                  ->orWhere('id', $this->searchTerm);
            });
        }
        
        return $query->orderByDesc('created_at')->paginate(10);
    }

    public function addEmail(): void
    {
        if (!$this->hasPermission('whitelist_email')) {
            session()->flash('error', 'You do not have permission to whitelist emails.');
            return;
        }
        
        if (Whitelisted::where('email', $this->email)->exists()) {
            session()->flash('whitelistmessage', 'Email already whitelisted.');
            return;
        }
        
        Whitelisted::create([
            'email' => $this->email,
        ]);
        
        AdminLogs::create([
            'admin_id' => Auth::id(),
            'action' => 'Whitelisted email ' . $this->email,
        ]);
        
        session()->flash('whitelistmessage', 'Email whitelisted successfully.');
        $this->reset('email');
    }

    public function giveBadge(string $userId, string $badgeId): void
    {
        if (!$this->hasPermission('manage_badges')) {
            session()->flash('error', 'You do not have permission to manage badges.');
            return;
        }
        
        $user = User::find($userId);
        $badge = Badge::find($badgeId);

        if ($user && $badge && !$user->badges()->where('badge_id', $badgeId)->exists()) {
            $user->badges()->attach($badge);
            
            AdminLogs::create([
                'admin_id' => Auth::id(),
                'action' => "Gave '{$badge->name}' badge to {$user->name}"
            ]);
            
            session()->flash('success', "Badge '{$badge->name}' given to {$user->name} successfully.");
        }
    }

    public function banUser(): Response|null
    {
        if (!$this->hasPermission('perm_ban')) {
            session()->flash('error', 'You do not have permission to permanently ban users.');
            return null;
        }
        
        $user = User::find($this->user_id);

        if (!$user) {
            session()->flash('error', 'User not found.');
            return null;
        }
        
        // Cannot ban admins
        if ($user->admin_rank > 0 && $user->admin_rank >= Auth::user()->admin_rank) {
            session()->flash('error', 'You cannot ban an admin with equal or higher rank.');
            return null;
        }
        
        $user->bans()->create([
            'reason' => $this->reason,
        ]);
        
        AdminLogs::create([
            'admin_id' => Auth::id(),
            'action' => 'Banned user ' . $user->name . ' (ID: ' . $user->id . ')',
        ]);
        
        session()->flash('banmessage', 'User banned successfully.');
        $this->reset('user_id', 'reason');
        $this->cancelAction();
        
        return null;
    }

    public function unbanUser(): void
    {
        if (!$this->hasPermission('unban')) {
            session()->flash('error', 'You do not have permission to unban users.');
            return;
        }
        
        $user = User::find($this->uid);

        if (!$user) {
            session()->flash('error', 'User not found.');
            return;
        }
        
        $ban = Banned::where('user_id', $user->id)->first();
        
        if ($ban) {
            $ban->delete();
            
            AdminLogs::create([
                'admin_id' => Auth::id(),
                'action' => 'Unbanned user ' . $user->name . ' (ID: ' . $user->id . ')',
            ]);
            
            session()->flash('unbanmessage', 'User unbanned successfully.');
            $this->reset('uid');
            $this->cancelAction();
        } else {
            session()->flash('error', 'User is not currently banned.');
        }
    }

    public function addAdmin(): void
    {
        if (!$this->hasPermission('manage_admins')) {
            session()->flash('error', 'You do not have permission to manage admins.');
            return;
        }
        
        $user = User::find($this->admin_id);
        
        if (!$user) {
            session()->flash('error', 'User not found.');
            return;
        }
        
        // Cannot promote to a higher rank than self
        if ($this->admin_rank > Auth::user()->admin_rank) {
            session()->flash('error', 'You cannot promote a user to a rank higher than your own.');
            return;
        }
        
        // Store previous rank for logging
        $previousRank = $user->admin_rank;
        $previousRankTitle = $this->getAdminRankTitle($previousRank)['title'];
        $newRankTitle = $this->getAdminRankTitle($this->admin_rank)['title'];
        
        $user->admin_rank = $this->admin_rank;
        $user->save();
        
        AdminLogs::create([
            'admin_id' => Auth::id(),
            'action' => "Changed {$user->name}'s rank from {$previousRankTitle} to {$newRankTitle}",
        ]);
        
        session()->flash('addmessage', "User's rank updated successfully to {$newRankTitle}.");
        $this->reset('admin_id', 'admin_rank');
        $this->cancelAction();
    }
    
    public function demoteAdmin($userId): void
    {
        if (!$this->hasPermission('manage_admins')) {
            session()->flash('error', 'You do not have permission to manage admins.');
            return;
        }
        
        $user = User::find($userId);
        
        if (!$user) {
            session()->flash('error', 'User not found.');
            return;
        }
        
        // Cannot demote admins with higher or equal rank
        if ($user->admin_rank >= Auth::user()->admin_rank) {
            session()->flash('error', 'You cannot demote an admin with equal or higher rank.');
            return;
        }
        
        $previousRank = $user->admin_rank;
        $previousRankTitle = $this->getAdminRankTitle($previousRank)['title'];
        
        $user->admin_rank = 0;
        $user->save();
        
        AdminLogs::create([
            'admin_id' => Auth::id(),
            'action' => "Demoted {$user->name} from {$previousRankTitle} to Regular User",
        ]);
        
        session()->flash('success', "User demoted successfully.");
        $this->cancelAction();
    }
    
    public function getStatistics(): array
    {
        return [
            'users' => [
                'total' => User::count(),
                'admins' => User::where('admin_rank', '>=', 1)->count(),
                'banned' => Banned::count(),
            ],
            'content' => [
                'posts' => Post::count(),
                'comments' => Comment::count(),
                'likes' => Like::count(),
            ],
            'logs' => [
                'total' => AdminLogs::count(),
                'recent' => AdminLogs::where('created_at', '>=', now()->subDays(7))->count(),
            ],
        ];
    }

    public function render(): View|Response
    {
        $authUser = Auth::user();
        
        if (!$authUser || $authUser->admin_rank < 1) {
            return abort(403, 'You are not authorized to view this page.');
        }

        $logs = AdminLogs::when($this->searchTerm, function($query) {
                $query->where('action', 'like', '%' . $this->searchTerm . '%');
            })
            ->orderBy('created_at', 'desc')
            ->paginate(15);
        
        $bannedUsers = $this->getBannedUsers();
        $statistics = $this->getStatistics();
        
        return view('livewire.Admin.admin-dashboard', [
            'admins' => $this->admins(),
            'logs' => $logs,
            'bannedUsers' => $bannedUsers,
            'statistics' => $statistics,
            'adminRanks' => $this->adminRanks,
            'userRank' => $authUser->admin_rank,
        ])->layout('layouts.app');
    }
}
