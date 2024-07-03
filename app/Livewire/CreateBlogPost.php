<?php

namespace App\Livewire;

use App\Models\Post;
use Livewire\Component;

class CreateBlogPost extends Component
{
    public $title;
    public $content;

    protected $rules = [
        'title' => 'required|min:5',
        'content' => 'required|min:10',
    ];

    public function submit()
    {
        $this->validate();

        Post::create([
            'user_id' => auth()->id(),
            'title' => $this->title,
            'content' => $this->content,
        ]);

        session()->flash('message', 'Blog post successfully created.');

        return redirect()->to('/blog');
    }

    public function render()
    {
        return view('livewire.create-blog-post');
    }
}