<?php

namespace App\Http\Controllers;

use App\Models\Post\Post;
use App\Models\Post\Like;
use App\Models\Post\Comment;

class DeleteController extends Controller
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

    // Delete a comment
    public function deleteComment($commentId)
    {
        $comment = Comment::find($commentId);
        $comment->delete();
    }
}
