<?php

namespace App\Livewire;

use App\Models\Blogs;
use Livewire\Component;
use Livewire\WithPagination;

class Blog extends Component
{
    use WithPagination;

    public function render()
    {
        $blogPosts = Blogs::with('user')->orderByDesc('created_at')->paginate(10);

        return view('livewire.blogs', compact('blogPosts'))->layout('layouts.app');
    }
}
