<?php

namespace App\Livewire;

use App\Models\Post;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Like;
use App\Models\Notification;
use Parsedown;
use Illuminate\Support\Facades\Session;

class Feed extends Component
{
    use WithPagination;

    public $view = 'feed'; // Default to feed

    public function likePost($postId)
    {
        $user = auth()->user();
        $like = Like::where('post_id', $postId)->where('user_id', $user->id)->first();

        if ($like) {
            $like->delete();
        } else {
            Like::create([
                'post_id' => $postId,
                'user_id' => $user->id,
            ]);
        }

        // Clear the session cache for feed to refresh the data
        Session::forget('feed_posts');
    }

    public function setView($view)
    {
        $this->view = $view;
    }

    public function render()
    {
        $userId = auth()->id(); // Get the current user's ID

        if ($this->view === 'notifications') {
            // Check if notifications are cached in the session
            if (Session::has('notifications')) {
                $notifications = Session::get('notifications');
            } else {
                // Fetch notifications for the current user
                $notifications = Notification::where('user_id', $userId)->orderByDesc('created_at')->paginate(20);
                // Store notifications in the session
                Session::put('notifications', $notifications);
            }
            return view('livewire.feed', compact('notifications'))->layout('layouts.app');
        } else {
            // Check if feed posts are cached in the session
            if (Session::has('feed_posts')) {
                $posts = Session::get('feed_posts');
            } else {
                // Fetch IDs of posts that the current user has liked
                $likedPostIds = Like::where('user_id', $userId)->pluck('post_id');

                // Fetch posts from users the current user is following or liked by the user with pagination
                $posts = Post::query()
                    ->leftJoin('followers', 'posts.user_id', '=', 'followers.following_id')
                    ->where(function ($query) use ($userId, $likedPostIds) {
                        $query->where('followers.follower_id', $userId)
                            ->orWhereIn('posts.id', $likedPostIds)
                            ->orWhere('posts.user_id', $userId); // Include the current user's own posts
                    })
                    ->orderByDesc('posts.created_at')
                    ->select('posts.*') // Ensure only columns from the posts table are selected
                    ->distinct()
                    ->paginate(20);

                foreach ($posts as $post) {
                    $post->hasProfanity = $post->hasProfanity();
                }

                // Store feed posts in the session
                Session::put('feed_posts', $posts);
            }

            $parsedown = new Parsedown();

            return view('livewire.feed', compact('posts', 'parsedown'))->layout('layouts.app');
        }
    }
}
