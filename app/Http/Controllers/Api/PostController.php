<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
use App\Models\Post\Post;
use App\Models\Post\Like;
use App\Models\Interaction\Notification;
use App\Services\Profanity;
use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * Display a listing of posts
     */
    public function index(Request $request)
    {
        $query = Post::with([
            'user', 
            'likes', 
            'comments' => function ($query) {
                $query->whereNull('parent_id')->with(['user', 'replies.user']);
            },
            'images'
        ])->orderBy('created_at', 'desc');

        // Apply blocked users filter
        if ($request->user()) {
            $blockedUsers = $request->user()->blockedUsers->pluck('blocked_users')->toArray();
            $blockedUsers = array_map('trim', explode(',', implode(',', $blockedUsers)));
            $query->whereNotIn('user_id', array_filter($blockedUsers));
        }

        $posts = $query->paginate(20);

        return PostResource::collection($posts);
    }

    /**
     * Display a specific post
     */
    public function show($id)
    {
        $post = Post::with([
            'user', 
            'likes', 
            'comments' => function ($query) {
                $query->whereNull('parent_id')->with(['user', 'replies.user', 'replies.replies.user']);
            },
            'images'
        ])->findOrFail($id);
        return new PostResource($post);
    }

    /**
     * Store a newly created post
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string|max:2000',
        ]);

        // Use the existing profanity service
        $profanityService = new Profanity();
        $hasProfanity = $profanityService->hasProfanity($request->content) || 
                       $profanityService->hasProfanity($request->title);

        $post = $request->user()->posts()->create([
            'title' => $request->title,
            'content' => $request->content,
            'has_profanity' => $hasProfanity,
        ]);

        return new PostResource($post->load(['user', 'likes', 'comments.user', 'images']));
    }

    /**
     * Update the specified post
     */
    public function update(Request $request, $id)
    {
        $post = Post::findOrFail($id);

        // Check if user owns the post or is admin
        if ($post->user_id !== $request->user()->id && $request->user()->admin_rank < 3) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'title' => 'sometimes|string|max:255',
            'content' => 'sometimes|string|max:2000',
        ]);

        $post->update($request->only(['title', 'content']));

        return new PostResource($post->load(['user', 'likes', 'comments.user', 'images']));
    }

    /**
     * Remove the specified post
     */
    public function destroy(Request $request, $id)
    {
        $post = Post::findOrFail($id);

        // Check if user owns the post or is admin
        if ($post->user_id !== $request->user()->id && $request->user()->admin_rank < 3) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // Delete related records
        $post->likes()->delete();
        $post->comments()->delete();
        $post->images()->delete();
        $post->delete();

        return response()->json(['message' => 'Post deleted successfully'], 204);
    }

    /**
     * Like/Unlike a post
     */
    public function toggleLike(Request $request, $id)
    {
        $post = Post::findOrFail($id);
        $user = $request->user();

        $like = Like::where('post_id', $id)->where('user_id', $user->id)->first();

        if ($like) {
            $like->delete();
            $liked = false;
        } else {
            Like::create([
                'post_id' => $id,
                'user_id' => $user->id,
            ]);
            $liked = true;

            // Create notification if not liking own post
            if ($post->user_id !== $user->id) {
                Notification::create([
                    'user_id' => $post->user_id,
                    'message' => $user->name . ' liked your post',
                    'link' => '/post/' . $post->id,
                ]);
            }
        }

        return response()->json([
            'liked' => $liked,
            'likes_count' => $post->likes()->count(),
            'message' => $liked ? 'Post liked' : 'Post unliked'
        ]);
    }

    /**
     * Get user's feed (posts from followed users)
     */
    public function feed(Request $request)
    {
        $user = $request->user();
        $followingIds = $user->follows()->pluck('following_id');

        $posts = Post::with([
            'user', 
            'likes', 
            'comments' => function ($query) {
                $query->whereNull('parent_id')->with(['user', 'replies.user']);
            },
            'images'
        ])->whereIn('user_id', $followingIds)
            ->orWhere('user_id', $user->id) // Include user's own posts
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return PostResource::collection($posts);
    }
}
