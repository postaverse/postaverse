<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
use App\Http\Resources\UserResource;
use App\Http\Resources\BlogResource;
use App\Models\User\User;
use App\Models\Post\Post;
use App\Models\Blog\Blog;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    /**
     * Search for users, posts, and blogs
     */
    public function search(Request $request)
    {
        $request->validate([
            'q' => 'required|string|min:3|max:255',
            'type' => 'sometimes|string|in:all,users,posts,blogs',
        ]);

        $query = $request->q;
        $type = $request->get('type', 'all');
        $results = [];

        if ($type === 'all' || $type === 'users') {
            $users = User::where('name', 'LIKE', "%{$query}%")
                ->orWhere('handle', 'LIKE', "%{$query}%")
                ->orderBy('id', 'desc')
                ->limit(10)
                ->get();

            $results['users'] = ['data' => UserResource::collection($users)->toArray($request)];
        }

        if ($type === 'all' || $type === 'posts') {
            $posts = Post::where('title', 'LIKE', "%{$query}%")
                ->orWhere('content', 'LIKE', "%{$query}%")
                ->with(['user', 'likes', 'comments.user', 'images'])
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get();

            $results['posts'] = ['data' => PostResource::collection($posts)->toArray($request)];
        }

        if ($type === 'all' || $type === 'blogs') {
            $blogs = Blog::where('title', 'LIKE', "%{$query}%")
                ->orWhere('content', 'LIKE', "%{$query}%")
                ->with(['user', 'likes', 'comments.user', 'images'])
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get();

            $results['blogs'] = ['data' => BlogResource::collection($blogs)->toArray($request)];
        }

        return response()->json($results);
    }

    /**
     * Search for users only
     */
    public function searchUsers(Request $request)
    {
        $request->validate([
            'q' => 'required|string|min:1|max:255',
        ]);

        $query = $request->q;
        
        $users = User::where('name', 'LIKE', "%{$query}%")
            ->orWhere('handle', 'LIKE', "%{$query}%")
            ->orWhere('bio', 'LIKE', "%{$query}%")
            ->orderBy('id', 'desc')
            ->paginate(20);

        return UserResource::collection($users);
    }

    /**
     * Search for posts only
     */
    public function searchPosts(Request $request)
    {
        $request->validate([
            'q' => 'required|string|min:1|max:255',
        ]);

        $query = $request->q;
        
        $posts = Post::where('title', 'LIKE', "%{$query}%")
            ->orWhere('content', 'LIKE', "%{$query}%")
            ->with(['user', 'likes', 'comments.user', 'images'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return PostResource::collection($posts);
    }

    /**
     * Search for blogs only
     */
    public function searchBlogs(Request $request)
    {
        $request->validate([
            'q' => 'required|string|min:1|max:255',
        ]);

        $query = $request->q;
        
        $blogs = Blog::where('title', 'LIKE', "%{$query}%")
            ->orWhere('content', 'LIKE', "%{$query}%")
            ->with(['user', 'likes', 'comments.user', 'images'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return BlogResource::collection($blogs);
    }

    /**
     * Get trending posts
     */
    public function trending()
    {
        // Get posts with most likes in the last 7 days
        $posts = Post::with(['user', 'likes', 'comments.user', 'images'])
            ->withCount('likes')
            ->where('created_at', '>=', now()->subDays(7))
            ->orderBy('likes_count', 'desc')
            ->orderBy('created_at', 'desc')
            ->limit(20)
            ->get();

        return PostResource::collection($posts);
    }

    /**
     * Get trending blogs
     */
    public function trendingBlogs()
    {
        // Get blogs with most likes in the last 7 days
        $blogs = Blog::with(['user', 'likes', 'comments.user', 'images'])
            ->withCount('likes')
            ->where('created_at', '>=', now()->subDays(7))
            ->orderBy('likes_count', 'desc')
            ->orderBy('created_at', 'desc')
            ->limit(20)
            ->get();

        return BlogResource::collection($blogs);
    }
}
