<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use App\Models\Badge;
use App\Models\Banned;
use App\Models\AdminLogs;
use App\Models\Whitelisted;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Http\Response;

class AdminDashboard extends Component
{
    public ?string $user_id = null;
    public ?string $reason = null;
    public ?string $uid = null;
    public ?string $admin_id = null;
    public ?int $admin_rank = null;
    public ?string $email = null;

    public function admins()
    {
        return User::where('admin_rank', '>=', 1)->get();
    }

    public function addEmail(): void
    {
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
        $user = User::find($userId);
        $badge = Badge::find($badgeId);

        if ($user && $badge && !$user->badges()->where('badge_id', $badgeId)->exists()) {
            $user->badges()->attach($badge);
            
            AdminLogs::create([
                'admin_id' => Auth::id(),
                'action' => "Gave '{$badge->name}' badge to {$user->username}"
            ]);
        }
    }

    public function banUser(): Response|null
    {
        $user = User::find($this->user_id);

        if (!$user) {
            return abort(404, 'User not found.');
        }
        
        $user->bans()->create([
            'reason' => $this->reason,
        ]);
        
        AdminLogs::create([
            'admin_id' => Auth::id(),
            'action' => 'Banned user ' . $user->username,
        ]);
        
        session()->flash('banmessage', 'User banned successfully.');
        $this->reset('user_id', 'reason');
        
        return null;
    }

    public function unbanUser(): void
    {
        $user = User::find($this->uid);

        if (!$user) {
            return;
        }
        
        $ban = Banned::where('user_id', $user->id)->first();
        
        if ($ban) {
            $ban->delete();
            
            AdminLogs::create([
                'admin_id' => Auth::id(),
                'action' => 'Unbanned user ' . $user->username,
            ]);
            
            session()->flash('unbanmessage', 'User unbanned successfully.');
            $this->reset('uid');
        }
    }

    public function addAdmin(): void
    {
        $user = User::find($this->admin_id);
        
        if (!$user) {
            session()->flash('addmessage', 'User not found.');
            return;
        }
        
        $user->admin_rank = $this->admin_rank;
        $user->save();
        
        AdminLogs::create([
            'admin_id' => Auth::id(),
            'action' => 'Added admin ' . $user->username,
        ]);
        
        session()->flash('addmessage', 'Admin added successfully.');
        $this->reset('admin_id', 'admin_rank');
    }

    public function render(): View|Response
    {
        $authUser = Auth::user();
        
        if (!$authUser || $authUser->admin_rank < 1) {
            return abort(403, 'You are not authorized to view this page.');
        }
        
        // Give admin badge if needed
        if (!$authUser->badges->contains('id', 1)) {
            $this->giveBadge($authUser->id, 1);
        }

        $logs = AdminLogs::where('admin_id', $authUser->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('livewire.admin-dashboard', [
            'admins' => $this->admins(),
            'logs' => $logs,
        ])->layout('layouts.app');
    }
}
