<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use App\Models\Badge;

class AdminDashboard extends Component
{
    public function admins()
    {
        // Get all admins where the column in the `users` table in the database is 1 or more.
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

    public function render()
    {
        $this->giveBadge(auth()->user()->id, 1);

        if (auth()->user()->admin_rank >= 1) {
            return view('livewire.admin-dashboard', [
                'admins' => $this->admins(),
            ])->layout('layouts.app');
        } else {
            return abort(403, 'You are not authorized to view this page.');
        }
    }
}