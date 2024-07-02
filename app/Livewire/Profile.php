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

    public function followUser()
    {
        $user = User::find($this->userId);
        if (!$user) {
            return;
        }

        $currentUser = auth()->user();
        $currentUser->follows()->attach($this->userId);
    }

    public function unfollowUser()
    {
        $user = User::find($this->userId);
        if (!$user) {
            return;
        }

        $currentUser = auth()->user();
        $currentUser->follows()->detach($this->userId);
    }

    public function isFollowing()
    {
        return auth()->user()->follows()->where('following_id', $this->userId)->exists();
    }
}
