<?php

namespace App\Livewire;

use Livewire\Component;

class CreateBlog extends Component
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
            'title' => 'required|min:6|max:255',
            'content' => 'required|min:6',
        ]);

        auth()->user()->blogs()->create([
            'title' => $this->title,
            'user_id' => auth()->id(),
            'content' => $this->content,
        ]);

        $this->title = '';
        $this->content = '';

        session()->flash('message', 'Blog created successfully.');
    }

    public function render()
    {
        return view('livewire.create-blog');
    }
}
