<?php

namespace App\Livewire;

use App\Models\Comment;
use App\Models\Like;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\Attributes\On;

class PostComments extends Component
{
    public Post $post;
    public $newComment = '';
    public $showAllComments = false;
    public $replyTo = null;
    public $replyContent = '';

    public function mount(Post $post)
    {
        $this->post = $post;
    }

    #[On('openComments')]
    public function handleOpenComments($postId)
    {
        // Only handle if this is for our post
        if ($postId === $this->post->id) {
            // Show all comments if there are more than 3
            if ($this->post->comments_count > 3) {
                $this->showAllComments = true;
            }
            
            // Dispatch browser event to focus and scroll
            $this->dispatch('focusCommentInput', ['postId' => $this->post->id]);
        }
    }

    public function addComment()
    {
        if (!Auth::check()) {
            return;
        }

        $this->validate([
            'newComment' => 'required|string|max:1000'
        ]);

        $comment = Comment::create([
            'user_id' => Auth::id(),
            'post_id' => $this->post->id,
            'content' => $this->newComment,
        ]);

        $this->post->incrementComments();
        $this->newComment = '';
        
        // Refresh the post to get updated comments
        $this->post = $this->post->fresh(['comments.user']);
        
        $this->dispatch('commentAdded', [
            'postId' => $this->post->id,
            'commentsCount' => $this->post->comments_count
        ]);
    }

    public function addReply()
    {
        if (!Auth::check() || !$this->replyTo) {
            return;
        }

        $this->validate([
            'replyContent' => 'required|string|max:1000'
        ]);

        $reply = Comment::create([
            'user_id' => Auth::id(),
            'post_id' => $this->post->id,
            'parent_id' => $this->replyTo,
            'content' => $this->replyContent,
        ]);

        // Increment replies count on parent comment
        $parentComment = Comment::find($this->replyTo);
        if ($parentComment) {
            $parentComment->increment('replies_count');
        }

        $this->replyContent = '';
        $this->replyTo = null;
        
        // Refresh the post
        $this->post = $this->post->fresh(['comments.user', 'comments.replies.user']);
    }

    public function toggleCommentLike($commentId)
    {
        if (!Auth::check()) {
            return;
        }

        $comment = Comment::findOrFail($commentId);
        $liked = Like::toggle(Auth::user(), $comment);

        // Refresh the post to get updated comment likes
        $this->post = $this->post->fresh(['comments.user', 'comments.replies.user']);
    }

    public function setReplyTo($commentId)
    {
        $this->replyTo = $this->replyTo === $commentId ? null : $commentId;
        $this->replyContent = '';
    }

    public function toggleShowAllComments()
    {
        $this->showAllComments = !$this->showAllComments;
    }

    public function render()
    {
        $comments = $this->showAllComments 
            ? $this->post->comments()->with(['user', 'replies.user'])->get()
            : $this->post->comments()->with(['user', 'replies.user'])->take(3)->get();

        return view('livewire.post-comments', [
            'comments' => $comments
        ]);
    }
}
