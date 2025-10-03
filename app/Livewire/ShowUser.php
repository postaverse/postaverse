<?php

namespace App\Livewire;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class ShowUser extends Component
{
    use WithPagination;
    
    public User $user;
    public $isFollowing = false;

    public function mount($username)
    {
        $this->user = User::where('username', $username)->firstOrFail();
        
        // Refresh the user to make sure all data is loaded
        $this->user->refresh();
            
        // Check if current user is following this user
        if (Auth::check()) {
            $this->isFollowing = Auth::user()->isFollowing($this->user);
        }
    }

    public function follow()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        if ($this->user->id === Auth::id()) {
            return;
        }

        Auth::user()->following()->attach($this->user->id);
        $this->isFollowing = true;
        
        // Create notification
        $this->user->notifications()->create([
            'type' => 'follow',
            'data' => [
                'follower_id' => Auth::id(),
                'follower_name' => Auth::user()->display_name,
                'message' => Auth::user()->display_name . ' started following you'
            ],
            'notifiable_type' => User::class,
            'notifiable_id' => $this->user->id
        ]);
    }

    public function unfollow()
    {
        if (!Auth::check()) {
            return;
        }

        Auth::user()->following()->detach($this->user->id);
        $this->isFollowing = false;
    }

    public function render()
    {
        // Load user's posts with relationships for pagination
        $posts = $this->user->posts()
            ->with(['user', 'likes', 'comments'])
            ->latest()
            ->paginate(10);
            
        return view('livewire.show-user', compact('posts'))
            ->layout('components.layouts.app')
            ->title($this->user->display_name . ' (@' . $this->user->username . ') - Postaverse');
    }
}
