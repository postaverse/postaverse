<?php

namespace App\Livewire;

use App\Models\Post;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Like;
use Parsedown;
use App\Http\Controllers\DeleteController;
use App\Http\Controllers\LikeController;

class Feed extends Component
{
    use WithPagination;

    public function likePost($postId)
    {
        $likeController = new LikeController();
        $likeController->likePost($postId);
    }

    public function delete($postId)
    {
        $deleteController = new DeleteController();
        $deleteController->deletePost($postId);
    }

    public function render()
    {
        $userId = auth()->id();
        
        // Get blocked users
        $blockedUsers = [];
        if (auth()->check()) {
            $blockedUsers = auth()->user()->blockedUsers->pluck('blocked_users')->toArray();
            $blockedUsers = array_map('trim', explode(',', implode(',', $blockedUsers)));
        }
        
        // Fetch posts with eager loading to improve performance
        $posts = Post::with(['user', 'comments', 'likes', 'images'])
            ->leftJoin('followers', function($join) use ($userId) {
                $join->on('posts.user_id', '=', 'followers.following_id')
                    ->where('followers.follower_id', '=', $userId);
            })
            ->leftJoin('likes', function($join) use ($userId) {
                $join->on('posts.id', '=', 'likes.post_id')
                    ->where('likes.user_id', '=', $userId);
            })
            ->whereNotIn('posts.user_id', $blockedUsers)
            ->where(function ($query) use ($userId) {
                $query->whereNotNull('followers.follower_id')
                      ->orWhereNotNull('likes.user_id')
                      ->orWhere('posts.user_id', $userId);
            })
            ->select('posts.*')
            ->distinct()
            ->orderByDesc('posts.created_at')
            ->paginate(20);

        $parsedown = new Parsedown();

        return view('livewire.feed', compact('posts', 'parsedown'))->layout('layouts.app');
    }
}