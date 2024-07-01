<?php
namespace App\Livewire;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class Profile extends Component
{
    use WithPagination;

    public $userId;

    public function mount($userId)
    {
        $this->userId = $userId;
    }

    public function render()
    {
        $user = User::find($this->userId);

        // Correctly apply pagination on the query builder
        $posts = $user->posts()->orderBy('created_at', 'desc')->paginate(20);

        return view('livewire.user-profile', [
            'user' => $user,
            'posts' => $posts,
        ])->layout('layouts.app');
    }
}