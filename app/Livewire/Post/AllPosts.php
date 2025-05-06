<?php

namespace App\Livewire\Post;

use App\Models\Post\Post;
use Livewire\Component;
use Livewire\WithPagination;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\DeleteController;

class AllPosts extends Component
{
    use WithPagination;

    public function likePost($postId)
    {
        $likeController = new LikeController();
        $likeController->likePost($postId);
    }

    public function delete(int $postId)
    {
        $deleteController = new DeleteController();
        $deleteController->deletePost($postId);
    }

    public function render()
    {
        $blockedUsers = [];
        if (auth()->check()) {
            $blockedUsers = auth()->user()->blockedUsers->pluck('blocked_users')->toArray();
            $blockedUsers = array_map('trim', explode(',', implode(',', $blockedUsers)));
        }

        $posts = Post::with('user', 'comments', 'likes')
            ->whereNotIn('user_id', $blockedUsers)
            ->orderByDesc('id')
            ->paginate(20);
        
        return view('livewire.Post.all-posts', compact('posts'))->layout('layouts.app');
    }
}
