<?php

namespace App\Livewire;

use App\Models\Post;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;
use App\Models\Badge;
use App\Models\Site;
use App\Http\Controllers\LikeController;
use App\Livewire\Profanity;
use App\Http\Controllers\DeleteController;
use Parsedown;

class AllPosts extends Component
{
    use WithPagination;

    public function likePost($postId)
    {
        $likeController = new LikeController();
        $likeController->likePost($postId);
    }

    public function delete(int $postId)
    {
        $deleteController = new DeleteController();
        $deleteController->deletePost($postId);
    }

    public function giveBadge($userId, $badgeId)
    {
        $user = User::find($userId);
        $badge = Badge::find($badgeId);

        if ($user && $badge) {
            $user->badges()->attach($badge);
        }
    }

    public function render()
    {
        $user = auth()->user();

        if ($user) {
            $postCount = Post::where('user_id', $user->id)->count();
            $this->managePostBadges($user, $postCount);
            $this->manageOtherBadges($user);

            $site = Site::where('user_id', $user->id)->first();

            if ($site && !$user->badges->contains(5)) {
                $this->giveBadge($user->id, 5);
            }
        }

        $blockedUsers = [];
        if (auth()->check()) {
            $blockedUsers = auth()->user()->blockedUsers->pluck('blocked_users')->toArray();
            $blockedUsers = array_map('trim', explode(',', implode(',', $blockedUsers)));
        }

        $posts = Post::with('user', 'comments', 'likes')
            ->whereNotIn('user_id', $blockedUsers)
            ->orderByDesc('id')
            ->paginate(20);
        
        return view('livewire.all-posts', compact('posts'))->layout('layouts.app');
    }

    private function managePostBadges($user, $postCount)
    {
        $badges = [
            2 => 10, // Cadet
            3 => 50, // Moonwalker
            4 => 100, // Rocketeer
        ];

        foreach ($badges as $badgeId => $threshold) {
            if ($postCount >= $threshold && !$user->badges->contains($badgeId)) {
                $this->giveBadge($user->id, $badgeId);
            } elseif ($postCount < $threshold && $user->badges->contains($badgeId)) {
                $user->badges()->detach($badgeId);
            }
        }
    }

    private function manageOtherBadges($user)
    {
        if ($user->sites->count() > 0 && !$user->badges->contains(5)) {
            $this->giveBadge($user->id, 5);
        } elseif ($user->sites->count() == 0 && $user->badges->contains(5)) {
            $user->badges()->detach(5);
        }
    }
}
