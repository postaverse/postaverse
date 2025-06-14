<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CommentResource;
use App\Models\Post\Comment;
use App\Models\Post\Post;
use App\Models\Blog\Blog;
use App\Models\Blog\BlogComment;
use App\Models\Interaction\Notification;
use Illuminate\Http\Request;
use App\Services\Profanity;

class CommentController extends Controller
{
    /**
     * Store a new comment on a post
     */
    public function storePostComment(Request $request, $postId)
    {
        $request->validate([
            'content' => 'required|string|max:1024',
            'parent_id' => 'nullable|exists:comments,id',
        ]);

        $post = Post::findOrFail($postId);

        // Use the existing profanity service
        $profanityService = new Profanity();
        $hasProfanity = $profanityService->hasProfanity($request->content);

        $comment = Comment::create([
            'content' => $request->content,
            'post_id' => $postId,
            'user_id' => $request->user()->id,
            'has_profanity' => $hasProfanity,
            'parent_id' => $request->parent_id,
        ]);

        // Create notification if not commenting on own post
        if ($post->user_id !== $request->user()->id) {
            Notification::create([
                'user_id' => $post->user_id,
                'message' => $request->user()->name . ' commented on your post',
                'link' => '/post/' . $postId,
            ]);
        }

        return new CommentResource($comment->load('user'));
    }

    /**
     * Store a new comment on a blog
     */
    public function storeBlogComment(Request $request, $blogId)
    {
        $request->validate([
            'content' => 'required|string|max:1024',
            'parent_id' => 'nullable|exists:blog_comments,id',
        ]);

        $blog = Blog::findOrFail($blogId);

        $comment = BlogComment::create([
            'content' => $request->content,
            'blog_id' => $blogId,
            'user_id' => $request->user()->id,
            'parent_id' => $request->parent_id,
        ]);

        // Create notification if not commenting on own blog
        if ($blog->user_id !== $request->user()->id) {
            Notification::create([
                'user_id' => $blog->user_id,
                'message' => $request->user()->name . ' commented on your blog',
                'link' => '/blog/' . $blogId,
            ]);
        }

        return response()->json([
            'comment' => $comment->load('user'),
            'message' => 'Comment added successfully'
        ], 201);
    }

    /**
     * Update a comment
     */
    public function update(Request $request, $id)
    {
        // Try to find comment in both tables
        $comment = Comment::find($id);
        $blogComment = null;
        
        if (!$comment) {
            $blogComment = BlogComment::find($id);
            if (!$blogComment) {
                return response()->json(['message' => 'Comment not found'], 404);
            }
        }

        $commentToUpdate = $comment ?: $blogComment;

        // Check if user owns the comment or is admin
        if ($commentToUpdate->user_id !== $request->user()->id && $request->user()->admin_rank < 3) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'content' => 'required|string|max:1024',
        ]);

        $commentToUpdate->update([
            'content' => $request->content,
        ]);

        return new CommentResource($commentToUpdate->load('user'));
    }

    /**
     * Delete a comment
     */
    public function destroy(Request $request, $id)
    {
        // Try to find comment in both tables
        $comment = Comment::find($id);
        $blogComment = null;
        
        if (!$comment) {
            $blogComment = BlogComment::find($id);
            if (!$blogComment) {
                return response()->json(['message' => 'Comment not found'], 404);
            }
        }

        $commentToDelete = $comment ?: $blogComment;

        // Check if user owns the comment or is admin
        if ($commentToDelete->user_id !== $request->user()->id && $request->user()->admin_rank < 3) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $commentToDelete->delete();

        return response()->json(['message' => 'Comment deleted successfully'], 204);
    }
}
