<?php

namespace App\Livewire;

use App\Models\Post;
use Livewire\Component;
use Livewire\WithPagination;
use Parsedown;

class AllPosts extends Component
{
    use WithPagination;

    public function delete(int $postId)
    {
        Post::query()
            ->where('id', $postId)
            ->delete();
    }

    public function render()
    {
        $posts = Post::query()->orderByDesc('id')->paginate(20);

        foreach ($posts as $post) {
            $post->hasProfanity = $post->hasProfanity();
        }
        $profanityOption = auth()->user()->profanity_block_type;

        $parsedown = new Parsedown();

        return view('livewire.all-posts', compact('posts', 'profanityOption', 'parsedown'))->layout('layouts.app');
    }
}
