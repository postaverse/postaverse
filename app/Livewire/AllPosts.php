<?php

namespace App\Livewire;

use App\Models\Post;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;
use App\Models\Badge;
use App\Models\Like;
use App\Models\Site;
use App\Models\Comment;
use App\Livewire\Profanity;
use Parsedown;

class AllPosts extends Component
{
    use WithPagination;

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
    }

    public function delete(int $postId)
    {
        // Batch delete associated likes and comments
        Like::where('post_id', $postId)->delete();
        Comment::where('post_id', $postId)->delete();

        // Delete the post
        Post::where('id', $postId)->delete();
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

        $posts = Post::with('user', 'comments', 'likes')->orderByDesc('id')->paginate(20);

        $checker = new Profanity();
        $parsedown = new Parsedown();

        if ($user) {
            $profanityOption = $user->profanity_block_type;

            return view('livewire.all-posts', compact('posts', 'profanityOption', 'parsedown', 'checker'))->layout('layouts.app');
        }
        return view('livewire.all-posts', compact('posts', 'parsedown'))->layout('layouts.app');
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
        $badges = [
            1, // Admin
            5, // Verified
        ];

        foreach ($badges as $badgeId) {
            if ($user->is_admin && !$user->badges->contains($badgeId)) {
                $this->giveBadge($user->id, $badgeId);
            } elseif (!$user->is_admin && $user->badges->contains($badgeId)) {
                $user->badges()->detach($badgeId);
            }
        }
    }
}
