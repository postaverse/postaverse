<?php
namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use App\Models\Badge;
use App\Models\Banned;

class AdminDashboard extends Component
{
    public $user_id;
    public $reason;
    public $uid;

    public function admins()
    {
        return User::where('admin_rank', '>=', 1)->get();
    }

    public function giveBadge($userId, $badgeId)
    {
        $user = User::find($userId);
        $badge = Badge::find($badgeId);

        if ($user && $badge) {
            $user->badges()->attach($badge);
        }
    }

    public function banUser()
    {
        $user = User::find($this->user_id);

        if ($user) {
            $user->bans()->create([
                'reason' => $this->reason,
            ]);
            session()->flash('banmessage', 'User banned successfully.');
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
            }
        }
    }

    public function render()
    {
        if (!auth()->user()->badges->contains(1)) {
            $this->giveBadge(auth()->user()->id, 1);
        }

        if (auth()->user()->admin_rank >= 1) {
            return view('livewire.admin-dashboard', [
                'admins' => $this->admins(),
            ])->layout('layouts.app');
        } else {
            return abort(403, 'You are not authorized to view this page.');
        }
    }
}