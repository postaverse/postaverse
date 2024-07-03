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
        $userId = auth()->id(); // Get the current user's ID
        $posts = Post::query()
            ->join('followers', 'posts.user_id', '=', 'followers.following_id')
            ->where('followers.follower_id', $userId) // Filter posts by users the current user is following
            ->orderByDesc('posts.created_at')
            ->select('posts.*') // Ensure only columns from the posts table are selected
            ->paginate(20);
        foreach ($posts as $post) {
            $post->hasProfanity = $post->hasProfanity();
        }
        $profanityOption = 'hide_clickable'; // Define $profanityOption with a default value or fetch from user settings
        return view('livewire.feed', compact('posts', 'profanityOption'))->layout('layouts.app');
    }
}
