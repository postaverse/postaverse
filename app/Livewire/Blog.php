<?php

namespace App\Livewire;

use App\Models\Post;
use Livewire\Component;
use Livewire\WithPagination;

class Blog extends Component
{
    use WithPagination;

    public function render()
    {
        $blogPosts = Post::with('user')->orderByDesc('created_at')->paginate(10);

        return view('livewire.blog', compact('blogPosts'))->layout('layouts.app');
    }
}
