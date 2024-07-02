<?php
namespace App\Livewire;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class Profile extends Component
{
    use WithPagination;

    public $userHandle;

    public $newHandle;

    public function updateHandle()
    {
        $this->validate([
            'newHandle' => ['required', 'string', 'max:255', 'unique:users,handle'],
        ]);

        $user = User::find(auth()->id());
        $user->handle = $this->newHandle;
        $user->save();

        session()->flash('message', 'Handle updated successfully.');
    }

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