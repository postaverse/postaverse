<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Attributes\Validate;
use Livewire\Component;

class CreatePost extends Component
{
    public string $title = '';
    public string $content = '';

    // Computed property for title character count
    public function getTitleCountProperty()
    {
        return strlen($this->title);
    }

    // Computed property for content character count
    public function getContentCountProperty()
    {
        return strlen($this->content);
    }

    public function submit()
    {
        $this->validate([
            'title' => 'required|max:100',
            'content' => 'required|max:500',
        ]);

        /** @var User $authUser */
        $authUser = auth()->user();

        $authUser->posts()->create([
            'title' => $this->title,
            'content' => $this->content,
        ]);

        return $this->redirect('/home');
    }

    public function render()
    {
        return view('livewire.create-post');
    }
}
