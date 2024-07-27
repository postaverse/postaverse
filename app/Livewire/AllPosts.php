<?php

namespace App\Livewire;

use App\Models\Post;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;
use App\Models\Badge;
use App\Models\Like;
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
            if ($postCount >= 50 && !$user->badges->contains(3)) {
                $this->giveBadge($user->id, 3);
            }
            if ($postCount >= 100 && !$user->badges->contains(4)) {
                $this->giveBadge($user->id, 4);
            } elseif (!$user->badges->contains(2)) {
                $this->giveBadge($user->id, 2);
            }
        }

        // If less than these, remove the badges
        if ($postCount < 10 && $user->badges->contains(2)) {
            $user->badges()->detach(2);
        }
        if ($postCount < 50 && $user->badges->contains(3)) {
            $user->badges()->detach(3);
        }
        if ($postCount < 100 && $user->badges->contains(4)) {
            $user->badges()->detach(4);
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
