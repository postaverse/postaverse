<?php

namespace App\Livewire;

use App\Models\Post;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;
use App\Models\Badge;
use Parsedown;

class AllPosts extends Component
{
    use WithPagination;

    public function delete(int $postId)
    {
        Post::query()
            ->where('id', $postId)
            ->delete();
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
        // If 10 posts, give cadet badge (badge_id = 2)
        $user = auth()->user();
        // select count(*) from posts where user_id = $user->id
        $postCount = Post::query()->where('user_id', $user->id)->count();
        if ($postCount >= 10) {
            if ($postCount >= 50) {
                $this->giveBadge($user->id, 3);
            }
            if ($postCount >= 100) {
                $this->giveBadge($user->id, 4);
            } else {
                $this->giveBadge($user->id, 2);
            }
        }

        $posts = Post::query()->orderByDesc('id')->paginate(20);

        foreach ($posts as $post) {
            $post->hasProfanity = $post->hasProfanity();
        }
        $profanityOption = auth()->user()->profanity_block_type;

        $parsedown = new Parsedown();

        return view('livewire.all-posts', compact('posts', 'profanityOption', 'parsedown'))->layout('layouts.app');
    }
}
