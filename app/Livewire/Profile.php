<?php
namespace App\Livewire;

use App\Models\User;
use Livewire\Component;

class Profile extends Component
{
    public $userId;

    public function mount($userId)
    {
        $this->userId = $userId;
    }

    public function render()
    {
        $user = User::with('posts')->find($this->userId);

        return view('livewire.user-profile', [
            'user' => $user,
            'posts' => $user->posts,
        ])->layout('layouts.app');
    }
}