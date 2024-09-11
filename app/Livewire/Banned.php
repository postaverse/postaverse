<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;

class Banned extends Component
{
    public function render()
    {
        $reason = User::find(auth()->id())->bans->first()->reason;
        return view('livewire.banned', ['reason' => $reason])->layout('layouts.app');
    }
}
