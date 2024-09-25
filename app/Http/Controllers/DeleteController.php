<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Like;
use App\Models\Comment;

abstract class Controller
{
    // Delete a post
    public function deletePost($postId)
    {
        $post = Post::find($postId);
        $likes = Like::where('post_id', $postId)->get();
        $comments = Comment::where('post_id', $postId)->get();
        $likes->each->delete();
        $comments->each->delete();
        $post->delete();
    }
}
