<?php

namespace App\Livewire\User;

use Livewire\Component;
use App\Models\User\User;

class Banned extends Component
{
    public function render()
    {
        $reason = User::find(auth()->id())->bans->first()->reason;
        return view('livewire.User.banned', ['reason' => $reason])->layout('layouts.app');
    }
}
