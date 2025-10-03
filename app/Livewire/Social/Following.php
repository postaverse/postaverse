<?php

namespace App\Livewire\Social;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class Following extends Component
{
    use WithPagination;

    public User $user;
    
    public function mount($username)
    {
        $this->user = User::where('username', $username)->firstOrFail();
    }

    public function toggleFollow($userId)
    {
        if (!Auth::check()) {
            return;
        }

        $userToFollow = User::findOrFail($userId);
        $currentUser = Auth::user();

        if ($currentUser->following()->where('following_id', $userId)->exists()) {
            $currentUser->following()->detach($userId);
        } else {
            $currentUser->following()->attach($userId);
        }

        // Refresh the component to update follow buttons
        $this->resetPage();
    }

    public function render()
    {
        $following = $this->user->following()
            ->paginate(20);

        return view('livewire.social.following', [
            'following' => $following
        ])->layout('components.layouts.app')
          ->title($this->user->display_name . "'s Following");
    }
}
