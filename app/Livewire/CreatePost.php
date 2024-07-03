<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Attributes\Validate;
use Livewire\Component;

class CreatePost extends Component
{
    #[Validate('required', 'max:100')]
    public string $title = '';

    #[Validate('required', 'max:500')]
    public string $content = '';

    public function submit()
    {
        $this->validate();

        /** @var User $authUser */
        $authUser = auth()->user();

        $authUser->posts()->create(
            $this->only(['title', 'content'])
        );

        return $this->redirect('/home');
    }

    public function render()
    {
        return view('livewire.create-post');
    }
}
