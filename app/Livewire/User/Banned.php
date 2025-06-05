<?php

namespace App\Livewire\User;

use Livewire\Component;
use App\Models\User\User;

class Banned extends Component
{
    public function render()
    {
        $user = User::find(auth()->id());
        $reason = $user && $user->bans && $user->bans->first() ? $user->bans->first()->reason : 'Reason not provided';
        return view('livewire.User.banned', ['reason' => $reason])->layout('layouts.app');
    }
}
