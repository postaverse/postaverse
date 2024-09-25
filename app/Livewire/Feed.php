<?php

namespace App\Livewire;

use App\Models\Post;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Like;
use App\Models\Notification;
use Parsedown;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\DeleteController;
use App\Http\Controllers\LikeController;

class Feed extends Component
{
    use WithPagination;

    public $view = 'feed'; // Default to feed

    public function likePost($postId)
    {
        $likeController = new LikeController();
        $likeController->likePost($postId);

        // Clear the session cache for feed to refresh the data
        Session::forget('feed_posts');
    }

    public function setView($view)
    {
        $this->view = $view;
    }

    public function delete($postId)
    {
        $deleteController = new DeleteController();
        $deleteController->deletePost($postId);

        // Clear the session cache for feed to refresh the data
        Session::forget('feed_posts');
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
            foreach ($notifications as $notification) {
                // Mark the notification as read if it hasn't been read
                if (!$notification->read_at) {
                    $notification->markAsRead();
                }
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
                $posts = Post::with('user') // Eager load the user relationship
                    ->leftJoin('followers', 'posts.user_id', '=', 'followers.following_id')
                    ->where(function ($query) use ($userId, $likedPostIds) {
                        $query->where('followers.follower_id', $userId)
                            ->orWhereIn('posts.id', $likedPostIds)
                            ->orWhere('posts.user_id', $userId); // Include the current user's own posts
                    })
                    ->orderByDesc('posts.created_at')
                    ->select('posts.*') // Ensure only columns from the posts table are selected
                    ->distinct()
                    ->paginate(20); // Limit the number of posts per page

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