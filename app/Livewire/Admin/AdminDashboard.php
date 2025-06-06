<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\User\User;
use App\Models\Badge;
use App\Models\User\Banned;
use App\Models\BannedIp;
use App\Models\Admin\AdminLogs;
use App\Models\Admin\Whitelisted;
use App\Models\Post\Post;
use App\Models\Post\Comment;
use App\Models\Post\Like;
use App\Services\IpBanService;
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
    
    // New properties for enhanced ban functionality
    public string $ban_user_search = '';
    public string $unban_user_search = '';
    public array $searchResults = [];
    public array $bannedSearchResults = [];
    public ?User $selectedUser = null;
    public ?User $selectedUnbanUser = null;
    public bool $showBanModal = false;
    public bool $showUnbanModal = false;
    
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
    
    public function resetBanForm()
    {
        $this->selectedUser = null;
        $this->user_id = null;
        $this->reason = '';
        $this->ban_user_search = '';
        $this->searchResults = [];
    }

    public function resetUnbanForm()
    {
        $this->selectedUnbanUser = null;
        $this->uid = null;
        $this->unban_user_search = '';
        $this->bannedSearchResults = [];
    }

    public function canBan(): bool
    {
        return $this->selectedUser && !empty(trim($this->reason ?? ''));
    }
    
    public function canUnban(): bool
    {
        return $this->selectedUnbanUser !== null;
    }
    
    public function getAdminRankTitle($rank): array
    {
        return $this->adminRanks[$rank] ?? ['title' => 'Unknown', 'color' => 'gray'];
    }
    
    public function setActiveTab($tab): void
    {
        // Redirect old ban/unban tabs to users tab
        if ($tab === 'ban' || $tab === 'unban') {
            $tab = 'users';
        }
        
        $this->activeTab = $tab;
        $this->resetPage();
        
        // Clear search term when switching tabs to avoid confusion
        if ($tab !== 'logs') {
            $this->searchTerm = '';
        }
    }
    
    public function clearSearch(): void
    {
        $this->searchTerm = '';
        $this->resetPage();
    }
    
    // Add this method to handle search term updates
    public function updatedSearchTerm(): void
    {
        $this->resetPage();
    }
    
    // Add lifecycle methods for user search functionality
    public function updatedBanUserSearch(): void
    {
        $this->searchResults = $this->searchUsers();
    }
    
    public function updatedUnbanUserSearch(): void
    {
        $this->bannedSearchResults = $this->searchBannedUsers();
    }
    
    // Add a manual search method for testing
    public function performSearch($term = null): void
    {
        if ($term !== null) {
            $this->searchTerm = $term;
        }
        $this->resetPage();
    }
    
    public function highlightSearchTerm($text, $searchTerm = null): string
    {
        if (empty($searchTerm)) {
            $searchTerm = $this->searchTerm;
        }
        
        if (empty($searchTerm) || empty($text)) {
            return e($text);
        }
        
        $escapedText = e($text);
        $escapedSearchTerm = preg_quote($searchTerm, '/');
        
        return preg_replace(
            '/(' . $escapedSearchTerm . ')/i',
            '<mark class="bg-yellow-400/30 text-yellow-200 rounded px-1">$1</mark>',
            $escapedText
        );
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
        $query = Banned::with(['user', 'bannedIps']);
        
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
        // Check if the user is already banned
        if (Banned::where('user_id', $this->user_id)->exists()) {
            session()->flash('error', 'User is already banned.');
            return null;
        }

        // Check if the user is an admin
        $user = User::find($this->user_id);
        if ($user && $user->admin_rank > 0) {
            session()->flash('error', 'You cannot ban an admin.');
            return null;
        }

        $ipBanService = new IpBanService();
        $result = $ipBanService->banUser($this->user_id, $this->reason);
        
        if ($result['success']) {
            session()->flash('banmessage', $result['message']);
            $this->reset('user_id', 'reason');
            $this->cancelAction();
        } else {
            session()->flash('error', $result['message']);
        }

        return null;
    }

    public function unbanUser(): void
    {
        // Check if the user is not banned
        if (!Banned::where('user_id', $this->uid)->exists()) {
            session()->flash('error', 'User is not banned.');
            return;
        }

        $ipBanService = new IpBanService();
        $result = $ipBanService->unbanUser($this->uid);
        
        if ($result['success']) {
            session()->flash('unbanmessage', $result['message']);
            $this->reset('uid');
            $this->cancelAction();
        } else {
            session()->flash('error', $result['message']);
        }
    }

    // New enhanced ban functionality
    public function searchUsers()
    {
        if (strlen($this->ban_user_search) < 2) {
            return [];
        }

        return User::where(function($query) {
            $query->where('name', 'like', '%' . $this->ban_user_search . '%')
                  ->orWhere('handle', 'like', '%' . $this->ban_user_search . '%')
                  ->orWhere('email', 'like', '%' . $this->ban_user_search . '%')
                  ->orWhere('id', $this->ban_user_search);
        })
        ->whereNotIn('id', function($query) {
            $query->select('user_id')->from('banned');
        })
        // For testing: allow searching for admins (normally you'd want to exclude them)
        // ->where('admin_rank', '=', 0) 
        ->limit(10)
        ->get(['id', 'name', 'handle', 'email', 'admin_rank', 'profile_photo_path'])
        ->toArray();
    }

    public function selectUserForBan($userId)
    {
        $this->selectedUser = User::find($userId);
        $this->user_id = $userId;
        $this->ban_user_search = '';
        $this->searchResults = [];
    }

    public function openBanModal($userId = null)
    {
        if ($userId) {
            $this->selectUserForBan($userId);
        }
        $this->showBanModal = true;
    }

    public function closeBanModal()
    {
        $this->showBanModal = false;
        $this->resetBanForm();
    }

    public function confirmBanUser()
    {
        $this->validate([
            'user_id' => 'required|exists:users,id',
            'reason' => 'required|string|min:5',
        ]);

        $ipBanService = new IpBanService();
        $result = $ipBanService->banUser($this->user_id, $this->reason);
        
        if ($result['success']) {
            session()->flash('banmessage', $result['message']);
            $this->closeBanModal();
        } else {
            session()->flash('error', $result['message']);
        }
    }

    public function searchBannedUsers()
    {
        if (strlen($this->unban_user_search) < 2) {
            return [];
        }

        return User::whereIn('id', function($query) {
            $query->select('user_id')->from('banned');
        })
        ->where(function($query) {
            $query->where('name', 'like', '%' . $this->unban_user_search . '%')
                  ->orWhere('handle', 'like', '%' . $this->unban_user_search . '%')
                  ->orWhere('email', 'like', '%' . $this->unban_user_search . '%')
                  ->orWhere('id', $this->unban_user_search);
        })
        ->limit(10)
        ->get(['id', 'name', 'handle', 'email', 'profile_photo_path'])
        ->toArray();
    }

    public function selectUserForUnban($userId)
    {
        $this->selectedUnbanUser = User::find($userId);
        $this->uid = $userId;
        $this->unban_user_search = '';
        $this->bannedSearchResults = [];
    }

    public function openUnbanModal($userId = null)
    {
        if ($userId) {
            $this->selectUserForUnban($userId);
        }
        $this->showUnbanModal = true;
    }

    public function closeUnbanModal()
    {
        $this->showUnbanModal = false;
        $this->resetUnbanForm();
    }

    public function confirmUnbanUser()
    {
        $this->validate([
            'uid' => 'required|exists:users,id',
        ]);

        $ipBanService = new IpBanService();
        $result = $ipBanService->unbanUser($this->uid);
        
        if ($result['success']) {
            session()->flash('unbanmessage', $result['message']);
            $this->closeUnbanModal();
        } else {
            session()->flash('error', $result['message']);
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

        $logs = AdminLogs::with('admin')
            ->when(!empty($this->searchTerm), function($query) {
                $searchTerm = trim($this->searchTerm);
                $query->where(function($q) use ($searchTerm) {
                    // Search by action content
                    $q->where('action', 'like', '%' . $searchTerm . '%')
                      // Search by admin ID
                      ->orWhere('admin_id', 'like', '%' . $searchTerm . '%')
                      // Search by admin name or email
                      ->orWhereHas('admin', function($adminQuery) use ($searchTerm) {
                          $adminQuery->where('name', 'like', '%' . $searchTerm . '%')
                                    ->orWhere('email', 'like', '%' . $searchTerm . '%')
                                    ->orWhere('handle', 'like', '%' . $searchTerm . '%');
                      });
                });
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
