<?php

namespace App\Livewire;

use App\Models\Post;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Like;
use Parsedown;

class Feed extends Component
{
    use WithPagination;

    public function likePost($postId)
    {
        $user = auth()->user();
        $like = Like::where('post_id', $postId)->where('user_id', $user->id)->first();

        if ($like) {
            $like->delete();
        } else {
            Like::create([
                'post_id' => $postId,
                'user_id' => $user->id,
            ]);
        }
    }

    public function render()
    {
        $userId = auth()->id(); // Get the current user's ID

        // Fetch IDs of posts that the current user has liked
        $likedPostIds = Like::where('user_id', $userId)->pluck('post_id');

        // Fetch posts from users the current user is following or liked by the user with pagination
        $posts = Post::query()
            ->leftJoin('followers', 'posts.user_id', '=', 'followers.following_id')
            ->where(function ($query) use ($userId, $likedPostIds) {
                $query->where('followers.follower_id', $userId)
                    ->orWhereIn('posts.id', $likedPostIds)
                    ->orWhere('posts.user_id', $userId); // Include the current user's own posts
            })
            ->orderByDesc('posts.created_at')
            ->select('posts.*') // Ensure only columns from the posts table are selected
            ->distinct()
            ->paginate(20);

        foreach ($posts as $post) {
            $post->hasProfanity = $post->hasProfanity();
        }

        $parsedown = new Parsedown();

        return view('livewire.feed', compact('posts', 'parsedown'))->layout('layouts.app');
    }
}
