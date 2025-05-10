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
        
        // Check if the post exists
        if (!$post) {
            return response()->json(['error' => 'Post not found'], 404);
        }
        
        // Check if the user is authorized to delete this post
        if (auth()->check() && (auth()->id() == $post->user_id || auth()->user()->admin_rank >= 3)) {
            // Delete related records first
            Like::where('post_id', $postId)->delete();
            Comment::where('post_id', $postId)->delete();
            
            // Delete the post
            $post->delete();
            
            return response()->json(['success' => 'Post deleted successfully']);
        } else {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        
    }

    // Delete a comment
    public function deleteComment($commentId)
    {
        $comment = Comment::find($commentId);
        $comment->delete();
    }
}
