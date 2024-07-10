<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;

class AdminDashboard extends Component
{
    public function admins()
    {
        // Get all admins where the column in the `users` table in the database is `true` (`1`)
        return User::where('is_admin', true)->get();
    }

    public function render()
    {
        if (auth()->user()->is_admin) {
            return view('livewire.admin-dashboard', [
                'admins' => $this->admins(),
            ])->layout('layouts.app');
        } else {
            return abort(403, 'You are not authorized to view this page.');
        }
    }
}