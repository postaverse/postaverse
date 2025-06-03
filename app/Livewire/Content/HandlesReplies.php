<?php

namespace App\Livewire\Content;

use App\Models\User\User;
use App\Models\Blog\BlogComment;
use App\Models\Post\Comment;
use App\Models\Interaction\Notification;
use Illuminate\Support\Facades\Auth;

trait HandlesReplies
{
    public $replyingTo = null;
    public $replyContent = '';

    public function startReply($commentId)
    {
        $this->replyingTo = $commentId;
        $this->replyContent = '';
    }

    public function cancelReply()
    {
        $this->replyingTo = null;
        $this->replyContent = '';
    }

    // Submit a reply to a comment
    public function submitReply()
    {
        $this->validate([
            'replyContent' => 'required|max:1024',
        ]);

        // Determine content type and handle reply
        $contentType = $this->contentType ?? request()->route()->getName();
        
        // Find the parent comment
        $parentComment = null;
        if ($contentType === 'blog' || $contentType === 'blogs') {
            $parentComment = BlogComment::find($this->replyingTo);
        } else {
            $parentComment = Comment::find($this->replyingTo);
        }
        
        // If parent comment doesn't exist, treat as a top-level comment
        if (!$parentComment) {
            $this->cancelReply();
            return;
        }

        // Create the reply
        $reply = null;
        if ($contentType === 'blog' || $contentType === 'blogs') {
            $reply = BlogComment::create([
                'blog_id' => $this->content->id,
                'user_id' => auth()->id(),
                'content' => $this->replyContent,
                'parent_id' => $this->replyingTo
            ]);
        } else {
            $reply = Comment::create([
                'post_id' => $this->content->id,
                'user_id' => auth()->id(),
                'content' => $this->replyContent,
                'parent_id' => $this->replyingTo
            ]);
        }

        // Process any mentioned users
        $mentionedUsers = $this->getMentionedUsers($this->replyContent);
        $contentId = $this->content->id;
        $route = $contentType === 'blog' || $contentType === 'blogs' ? 'blog' : 'post';
        $this->dispatchNotifications($mentionedUsers, "mentioned you in a reply", route($route, ['contentId' => $contentId]));

        // Notify the comment owner if it's not the current user
        if ($parentComment->user_id != auth()->id()) {
            Notification::create([
                'user_id' => $parentComment->user_id,
                'from_user_id' => auth()->id(),
                'message' => auth()->user()->name . ' replied to your comment',
                'link' => route($route, ['contentId' => $contentId])
            ]);
        }

        // Refresh content and reset reply state
        $this->cancelReply();

        $this->comments = $this->content->comments()
            ->with('replies.user')
            ->whereNull('parent_id')
            ->orderBy('created_at', 'desc')
            ->get();
        
        $this->dispatch('replyAdded');
    }
    
    protected function getMentionedUsers($text)
    {
        preg_match_all('/@(\w+)/', $text, $matches);
        return User::whereIn('handle', $matches[1])
                   ->orWhereIn('id', $matches[1])
                   ->get();
    }
    
    // Helper to send notification to mentioned users
    protected function dispatchNotifications($users, $message, $link)
    {
        if (!$users->isEmpty()) {
            foreach ($users as $user) {
                if ($user->id == auth()->id()) continue;
                
                Notification::create([
                    'user_id' => $user->id,
                    'from_user_id' => auth()->id(),
                    'message' => auth()->user()->name . ' ' . $message,
                    'link' => $link
                ]);
            }
        }
    }
}
