<?php

namespace App\Livewire\Admin;

use App\Models\Post;
use App\Models\User;
use App\Models\Like;
use App\Models\Comment;
use App\Models\Group;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.app')]
class Analytics extends Component
{
    public $timeframe = '30'; // 7, 30, 90 days

    public function render()
    {
        $this->checkAdminAccess();

        $days = (int) $this->timeframe;
        $startDate = now()->subDays($days);

        // User Analytics
        $totalUsers = User::count();
        $newUsers = User::where('created_at', '>=', $startDate)->count();
        $activeUsers = User::whereHas('posts', function($query) use ($startDate) {
            $query->where('created_at', '>=', $startDate);
        })->orWhereHas('comments', function($query) use ($startDate) {
            $query->where('created_at', '>=', $startDate);
        })->count();

        // Content Analytics
        $totalPosts = Post::count();
        $newPosts = Post::where('created_at', '>=', $startDate)->count();
        $totalComments = Comment::count();
        $newComments = Comment::where('created_at', '>=', $startDate)->count();
        $totalLikes = Like::count();
        $newLikes = Like::where('created_at', '>=', $startDate)->count();

        // Group Analytics
        $totalGroups = Group::count();
        $newGroups = Group::where('created_at', '>=', $startDate)->count();

        // Top Users by Posts
        $topUsersByPosts = User::withCount('posts')
            ->orderBy('posts_count', 'desc')
            ->take(10)
            ->get();

        // Top Users by Followers
        $topUsersByFollowers = User::withCount('followers')
            ->orderBy('followers_count', 'desc')
            ->take(10)
            ->get();

        // Most Popular Posts
        $popularPosts = Post::with('user')
            ->orderBy('likes_count', 'desc')
            ->take(10)
            ->get();

        // Daily Activity (last 7 days)
        $dailyActivity = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $dailyActivity[] = [
                'date' => $date->format('M j'),
                'posts' => Post::whereDate('created_at', $date)->count(),
                'users' => User::whereDate('created_at', $date)->count(),
                'likes' => Like::whereDate('created_at', $date)->count(),
            ];
        }

        return view('livewire.admin.analytics', [
            'totalUsers' => $totalUsers,
            'newUsers' => $newUsers,
            'activeUsers' => $activeUsers,
            'totalPosts' => $totalPosts,
            'newPosts' => $newPosts,
            'totalComments' => $totalComments,
            'newComments' => $newComments,
            'totalLikes' => $totalLikes,
            'newLikes' => $newLikes,
            'totalGroups' => $totalGroups,
            'newGroups' => $newGroups,
            'topUsersByPosts' => $topUsersByPosts,
            'topUsersByFollowers' => $topUsersByFollowers,
            'popularPosts' => $popularPosts,
            'dailyActivity' => $dailyActivity,
        ]);
    }

    private function checkAdminAccess()
    {
        if (!auth()->user()->admin_level) {
            abort(403, 'Unauthorized access');
        }
    }
}
