<?php
namespace App\Livewire\Interaction;

use App\Models\User\User;
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
        return view('livewire.Interaction.follow');
    }
}