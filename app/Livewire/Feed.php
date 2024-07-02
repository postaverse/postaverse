<?php

namespace App\Livewire;

use App\Models\Post;
use Livewire\Component;
use Livewire\WithPagination;

class Feed extends Component
{
    use WithPagination;

    public function render()
    {
        $posts = Post::query()
            ->orderByDesc('created_at')
            ->paginate(20);

        return view('livewire.feed', compact('posts'))->layout('layouts.app');
    }
}