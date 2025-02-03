<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use App\Models\Badge;
use App\Models\Banned;
use App\Models\AdminLogs;
use App\Models\Whitelisted;

class AdminDashboard extends Component
{
    public $user_id;
    public $reason;
    public $uid;
    public $admin_id;
    public $admin_rank;
    public $email;
    public $confirmBan = false;

    public function admins()
    {
        return User::where('admin_rank', '>=', 1)->get();
    }

    public function addEmail()
    {
        if (Whitelisted::where('email', $this->email)->exists()) {
            session()->flash('whitelistmessage', 'Email already whitelisted.');
        } else {
            Whitelisted::create([
                'email' => $this->email,
            ]);
            session()->flash('whitelistmessage', 'Email whitelisted successfully.');
            AdminLogs::create([
                'admin_id' => auth()->user()->id,
                'action' => 'Whitelisted email ' . $this->email,
            ]);
            $this->reset('email');
        }
    }

    public function giveBadge($userId, $badgeId)
    {
        $user = User::find($userId);
        $badge = Badge::find($badgeId);

        if ($user && $badge) {
            $user->badges()->attach($badge);
        }
    }

    public function confirmBanUser()
    {
        $this->confirmBan = true;
        $this->banUser();
    }

    public function banUser()
    {
        if (!$this->confirmBan) {
            session()->flash('banmessage', 'Please confirm the ban action.');
            return;
        }

        $user = User::find($this->user_id);

        if ($user) {
            $user->bans()->create([
                'reason' => $this->reason,
            ]);
            session()->flash('banmessage', 'User banned successfully.');
            AdminLogs::create([
                'admin_id' => auth()->user()->id,
                'action' => 'Banned user ' . $user->username,
            ]);
            $this->reset('user_id', 'reason', 'confirmBan');
        } else {
            return abort(404, 'User not found.');
        }
    }

    public function unbanUser()
    {
        $uid = $this->uid;

        $user = User::find($uid);

        if ($user) {
            $ban = Banned::where('user_id', $user->id)->first();
            if ($ban) {
                $ban->delete();
                session()->flash('unbanmessage', 'User unbanned successfully.');
                AdminLogs::create([
                    'admin_id' => auth()->user()->id,
                    'action' => 'Unbanned user ' . $user->username,
                ]);
                $this->reset('uid');
            }
        }
    }

    public function addAdmin()
    {
        $user = User::find($this->admin_id);
        if ($user) {
            $user->admin_rank = $this->admin_rank;
            $user->save();
            session()->flash('addmessage', 'Admin added successfully.');
            AdminLogs::create([
                'admin_id' => auth()->user()->id,
                'action' => 'Added admin ' . $user->username,
            ]);
            $this->reset('admin_id', 'admin_rank');
        } else {
            session()->flash('addmessage', 'User not found.');
        }
    }

    public function reportUser($userId, $reason)
    {
        // Logic to report a user
        AdminLogs::create([
            'admin_id' => auth()->user()->id,
            'action' => 'Reported user ' . $userId . ' for ' . $reason,
        ]);
    }

    public function reportPost($postId, $reason)
    {
        // Logic to report a post
        AdminLogs::create([
            'admin_id' => auth()->user()->id,
            'action' => 'Reported post ' . $postId . ' for ' . $reason,
        ]);
    }

    public function render()
    {
        if (!auth()->user()->badges->contains(1)) {
            $this->giveBadge(auth()->user()->id, 1);
        }

        $user = auth()->user()->id;

        $logs = AdminLogs::where('admin_id', $user)->orderBy('created_at', 'desc')->get();

        if (auth()->user()->admin_rank >= 1) {
            return view('livewire.admin-dashboard', [
                'admins' => $this->admins(),
                'logs' => $logs,
                'confirmBan' => $this->confirmBan,
            ])->layout('layouts.app');
        } else {
            return abort(403, 'You are not authorized to view this page.');
        }
    }
}
