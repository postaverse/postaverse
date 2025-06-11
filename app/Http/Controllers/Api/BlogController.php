<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\BlogResource;
use App\Models\Blog\Blog;
use App\Models\Blog\BlogLike;
use App\Models\Interaction\Notification;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    /**
     * Display a listing of blogs
     */
    public function index()
    {
        $blogs = Blog::with(['user', 'likes', 'comments.user', 'images'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return BlogResource::collection($blogs);
    }

    /**
     * Display a specific blog
     */
    public function show($id)
    {
        $blog = Blog::with(['user', 'likes', 'comments.user', 'images'])->findOrFail($id);
        return new BlogResource($blog);
    }

    /**
     * Store a newly created blog
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string|max:5000',
        ]);

        $blog = $request->user()->blogs()->create([
            'title' => $request->title,
            'content' => $request->content,
        ]);

        return new BlogResource($blog->load(['user', 'likes', 'comments.user', 'images']));
    }

    /**
     * Update the specified blog
     */
    public function update(Request $request, $id)
    {
        $blog = Blog::findOrFail($id);

        // Check if user owns the blog or is admin
        if ($blog->user_id !== $request->user()->id && $request->user()->admin_rank < 3) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'title' => 'sometimes|string|max:255',
            'content' => 'sometimes|string|max:5000',
        ]);

        $blog->update($request->only(['title', 'content']));

        return new BlogResource($blog->load(['user', 'likes', 'comments.user', 'images']));
    }

    /**
     * Remove the specified blog
     */
    public function destroy(Request $request, $id)
    {
        $blog = Blog::findOrFail($id);

        // Check if user owns the blog or is admin
        if ($blog->user_id !== $request->user()->id && $request->user()->admin_rank < 3) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // Delete related records
        $blog->likes()->delete();
        $blog->comments()->delete();
        $blog->images()->delete();
        $blog->delete();

        return response()->json(['message' => 'Blog deleted successfully'], 204);
    }

    /**
     * Like/Unlike a blog
     */
    public function toggleLike(Request $request, $id)
    {
        $blog = Blog::findOrFail($id);
        $user = $request->user();

        $like = BlogLike::where('blog_id', $id)->where('user_id', $user->id)->first();

        if ($like) {
            $like->delete();
            $liked = false;
        } else {
            BlogLike::create([
                'blog_id' => $id,
                'user_id' => $user->id,
            ]);
            $liked = true;

            // Create notification if not liking own blog
            if ($blog->user_id !== $user->id) {
                Notification::create([
                    'user_id' => $blog->user_id,
                    'message' => $user->name . ' liked your blog',
                    'link' => '/blog/' . $blog->id,
                ]);
            }
        }

        return response()->json([
            'liked' => $liked,
            'likes_count' => $blog->likes()->count(),
            'message' => $liked ? 'Blog liked' : 'Blog unliked'
        ]);
    }
}
