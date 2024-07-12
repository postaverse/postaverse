<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Parsedown;

class Profile extends Component
{
    use WithPagination;

    public $userId;

    public function mount($handle)
    {
        if (is_numeric($handle)) {
            $this->userId = $handle;

            $user = User::find($handle);
            try {
                if ($user) {
                    return redirect()->route('user-profile', $user->handle);
                }
            } catch (\Exception $e) {
                error_log('.');
            }
        }
        else {
            $this->userId = User::where('handle', $handle)->first()->id;
        }
    }

    public function render()
    {
        $user = User::find($this->userId);
        if (!$user) {
            return abort(404, 'User not found');
        }

        $parsedown = new Parsedown();

        $posts = $user->posts()->orderBy('created_at', 'desc')->paginate(20);
    
        return view('livewire.user-profile', [
            'user' => $user,
            'posts' => $posts,
            'badges' => $user->badges,
            'parsedown' => $parsedown,
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
