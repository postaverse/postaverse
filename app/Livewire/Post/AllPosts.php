<?php

namespace App\Livewire\Post;

use App\Livewire\Content\BaseList;
use App\Models\Post\Post;

class AllPosts extends BaseList
{
    protected function getModel(): string
    {
        return Post::class;
    }

    protected function getRelations(): array
    {
        return ['user', 'comments', 'likes'];
    }

    public function likePost($postId)
    {
        app(\App\Http\Controllers\LikeController::class)->likePost($postId);
    }

    public function delete(int $postId)
    {
        app(\App\Http\Controllers\DeleteController::class)->deletePost($postId);
    }

    protected function applyFilters($query)
    {
        // Exclude blocked users
        if (auth()->check()) {
            $blockedUsers = auth()->user()->blockedUsers->pluck('blocked_users')->toArray();
            $blockedUsers = array_map('trim', explode(',', implode(',', $blockedUsers)));
            $query->whereNotIn('user_id', $blockedUsers);
        }
        return $query;
    }

    protected function view(): string
    {
        return 'livewire.Post.all-posts';
    }

    protected function getVariableName(): string
    {
        return 'posts';
    }
}
