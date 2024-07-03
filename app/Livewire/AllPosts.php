<?php

namespace App\Livewire;

use App\Models\Post;
use Livewire\Component;
use Livewire\WithPagination;

class AllPosts extends Component
{
    use WithPagination;

    public function delete(int $postId)
    {
        Post::query()
            ->where('user_id', auth()->id())
            ->where('id', $postId)
            ->delete();
    }

    public function render()
    {
        $posts = Post::query()->orderByDesc('id')->paginate(20);

        foreach ($posts as $post) {
            $post->hasProfanity = $post->hasProfanity();
        }
        $profanityOption = 'hide_clickable';

        return view('livewire.all-posts', compact('posts', 'profanityOption'))->layout('layouts.app');
    }
}
