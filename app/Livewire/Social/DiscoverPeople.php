<?php

namespace App\Livewire\Social;

use App\Models\User;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.app')]
class DiscoverPeople extends Component
{
    use WithPagination;

    public string $search = '';

    public function mount()
    {
        $this->resetPage();
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function followUser($userId)
    {
        $user = User::findOrFail($userId);
        
        if (!auth()->user()->following()->where('following_id', $userId)->exists()) {
            auth()->user()->following()->attach($userId);
            
            // Create notification for the followed user
            $user->notifications()->create([
                'type' => 'follow',
                'title' => 'New Follower',
                'message' => auth()->user()->username . ' started following you',
                'data' => [
                    'follower_id' => auth()->id(),
                    'follower_username' => auth()->user()->username,
                ],
                'notifiable_type' => User::class,
                'notifiable_id' => $userId
            ]);
        }
    }

    public function unfollowUser($userId)
    {
        auth()->user()->following()->detach($userId);
    }

    public function render()
    {
        $users = User::query()
            ->where('id', '!=', auth()->id())
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('username', 'like', '%' . $this->search . '%')
                      ->orWhere('bio', 'like', '%' . $this->search . '%');
                });
            })
            ->withCount(['followers', 'following', 'posts'])
            ->latest()
            ->paginate(12);

        return view('livewire.social.discover-people', [
            'users' => $users
        ]);
    }
}
