<?php
namespace App\Livewire;

use App\Models\User;
use Livewire\Component;

class Follow extends Component
{
    public $user;

    public function mount(User $user)
    {
        $this->user = $user;
    }

    public function follow()
    {
        auth()->user()->follow($this->user);
    }

    public function unfollow()
    {
        auth()->user()->unfollow($this->user);
    }

    public function render()
    {
        return view('livewire.follow');
    }
}