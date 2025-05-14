<?php

namespace App\Livewire\Post;

use App\Livewire\Content\BasePage;
use App\Livewire\Content\HandlesReplies;
use App\Models\Post\Post;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use App\Models\Interaction\Notification;
use App\Http\Controllers\DeleteController;
use App\Http\Controllers\LikeController;

class PostPage extends BasePage
{
    use HandlesReplies;

    public $post;
    public $comments;
    public ?Collection $photos = null;

    public function mount($postId)
    {
        $this->initializeBasePage();
        // Blocked users filter
        $blockedUsers = [];
        if (auth()->check()) {
            $blockedUsers = $this->user->blockedUsers->pluck('blocked_users')->toArray();
            $blockedUsers = array_map('trim', explode(',', implode(',', $blockedUsers)));
        }

        $this->post = Post::with('images')->findOrFail($postId);
        $this->comments = $this->post->comments()
            ->with(['user','replies.user','replies.replies.user','replies.replies.replies.user'])
            ->whereNull('parent_id')
            ->whereNotIn('user_id', $blockedUsers)
            ->orderBy('created_at','desc')
            ->get();
        $this->photos = $this->post->images;
    }

    public function submitReply()
    {
        $this->validate(['replyContent' => 'required|max:1024']);
        $parent = \App\Models\Post\Comment::find($this->replyingTo);
        if (!$parent) {
            $this->content = $this->replyContent;
            $this->submit();
            return;
        }
        $hasProfanity = $this->checkProfanity($this->replyContent);
        $reply = $this->user->comments()->create([
            'content' => $this->replyContent,
            'post_id' => $this->post->id,
            'parent_id' => $this->replyingTo,
            'user_id' => $this->user->id,
            'has_profanity' => $hasProfanity,
        ]);
        // Notify parent and mentions
        if ($parent->user_id != $this->user->id) {
            Notification::create([
                'user_id' => $parent->user_id,
                'message' => $this->user->name . ' replied to your comment',
                'link' => route('post', ['postId' => $this->post->id]),
            ]);
        }
        $this->dispatchNotifications($this->getMentionedUsers($this->replyContent), 'mentioned you in a comment reply', route('post', ['postId' => $this->post->id]));
        $this->replyingTo = null;
        $this->replyContent = '';
        $this->comments = $this->comments = $this->post->comments()->with('replies.user')->whereNull('parent_id')->orderBy('created_at','desc')->get();
        $this->dispatch('replyAdded');
    }

    public function likePost($postId)
    {
        app(LikeController::class)->likePost($postId);
        $this->post = Post::with('likes')->find($postId);
    }

    public function delete(int $postId)
    {
        app(DeleteController::class)->deletePost($postId);
        return redirect()->route('home');
    }

    public function submit()
    {
        $this->validate(['content' => 'required|max:1024']);
        $hasProfanity = $this->checkProfanity($this->content);
        $comment = $this->user->comments()->create([
            'content' => $this->content,
            'post_id' => $this->post->id,
            'user_id' => $this->user->id,
            'has_profanity' => $hasProfanity,
        ]);
        if ($this->post->user_id != $this->user->id) {
            Notification::create([
                'user_id' => $this->post->user_id,
                'message' => $this->user->name . ' commented on your post',
                'link' => route('post', ['postId' => $this->post->id]),
            ]);
        }
        $this->comments = $this->post->comments()->with('replies.user')->whereNull('parent_id')->orderBy('created_at','desc')->get();
        $this->dispatchNotifications($this->getMentionedUsers($this->content), 'mentioned you in a comment', route('post', ['postId' => $this->post->id]));
        $this->content = '';
    }

    public function render()
    {
        return view('livewire.Post.post-page', [
            'post' => $this->post,
            'postContent' => Str::markdown($this->post->content),
            'comments' => $this->comments,
            'photos' => $this->photos,
        ])->layout('layouts.app');
    }
}
