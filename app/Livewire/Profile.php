<?php
namespace App\Livewire;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class Profile extends Component
{
    use WithPagination;

    public $userHandle;

    public function mount($userHandle)
    {
        $this->userHandle = $userHandle;
    }

    public function render()
    {
        // Assuming you have a 'handle' column in your users table
        $user = User::where('handle', $this->userHandle)->firstOrFail();
        $posts = $user->posts()->orderBy('created_at', 'desc')->paginate(20);

        return view('livewire.user-profile', [
            'user' => $user,
            'posts' => $posts,
        ])->layout('layouts.app');
    }
}