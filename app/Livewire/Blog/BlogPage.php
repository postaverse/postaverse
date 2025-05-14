<?php

namespace App\Livewire\Blog;

use App\Livewire\Content\BasePage;
use App\Livewire\Content\HandlesReplies;
use App\Models\Blog\Blog;
use App\Models\Blog\BlogComment;
use App\Models\Blog\BlogLike;
use App\Models\Blog\BlogImage;
use App\Models\Interaction\Notification;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use App\Services\Profanity;

class BlogPage extends BasePage
{
    use HandlesReplies;

    public $blog;
    public $comments;
    public ?Collection $images = null;

    public function mount($blogId)
    {
        $this->initializeBasePage();
        // block list
        $blockedUsers = [];
        if (auth()->check()) {
            $blockedUsers = $this->user->blockedUsers->pluck('blocked_users')->toArray();
            $blockedUsers = array_map('trim', explode(',', implode(',', $blockedUsers)));
        }

        $this->blog = Blog::with('images')->findOrFail($blogId);
        $this->comments = $this->blog->comments()
            ->with(['user','replies.user','replies.replies.user','replies.replies.replies.user'])
            ->whereNull('parent_id')
            ->whereNotIn('user_id', $blockedUsers)
            ->orderBy('created_at','desc')
            ->get();
        $this->images = $this->blog->images;
    }

    // Start replying to a comment
    public function startReply($commentId)
    {
        $this->replyingTo = $commentId;
        $this->replyContent = '';
    }

    // Cancel replying
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

        // Find the parent comment
        $parentComment = BlogComment::find($this->replyingTo);
        
        // If parent comment doesn't exist, treat as a top-level comment
        if (!$parentComment) {
            $this->content = $this->replyContent;
            $this->submit();
            return;
        }
        
        $profanityChecker = new Profanity();
        $hasProfanity = $profanityChecker->hasProfanity($this->replyContent);

        // Create the reply comment
        $reply = $this->user->blogComments()->create([
            'content' => $this->replyContent,
            'blog_id' => $this->blog->id,
            'parent_id' => $this->replyingTo,
            'user_id' => $this->user->id,
            'has_profanity' => $hasProfanity,
        ]);

        // Send notification to the parent comment author
        if ($parentComment->user_id != $this->user->id) {
            Notification::create([
                'user_id' => $parentComment->user_id,
                'message' => $this->user->name . ' replied to your comment',
                'link' => route('blog', ['blogId' => $this->blog->id]),
            ]);
        }

        // Notify mentioned users
        $mentionedUsers = $this->getMentionedUsers($this->replyContent);
        
        foreach ($mentionedUsers as $mentionedUser) {
            if ($mentionedUser->id != $parentComment->user_id && $mentionedUser->id != $this->user->id) {
                Notification::create([
                    'user_id' => $mentionedUser->id,
                    'message' => $this->user->name . ' mentioned you in a comment reply',
                    'link' => route('blog', ['blogId' => $this->blog->id]),
                ]);
            }
        }

        // Reset reply state
        $this->replyingTo = null;
        $this->replyContent = '';

        // Refresh comments
        $this->comments = $this->blog->comments()
            ->with('replies.user')
            ->whereNull('parent_id')
            ->orderBy('created_at', 'desc')
            ->get();

        // Dispatch an event to reset the form
        $this->dispatch('replyAdded');
    }

    public function likeBlog($blogId)
    {
        if (!auth()->check()) {
            return;
        }
        
        $blog = Blog::find($blogId);
        if (!$blog) {
            return;
        }
        
        $like = BlogLike::where('blog_id', $blogId)
            ->where('user_id', auth()->id())
            ->first();
            
        if ($like) {
            $like->delete();
        } else {
            BlogLike::create([
                'blog_id' => $blogId,
                'user_id' => auth()->id()
            ]);
        }
        
        $this->blog = Blog::with('likes')->find($blogId);
    }

    public function delete(int $blogId)
    {
        $blog = Blog::find($blogId);
        if (auth()->check() && ($blog->user_id == auth()->id() || auth()->user()->admin_rank >= 3)) {
            $blog->delete();
            return redirect()->route('blogs');
        }
    }

    public function submit()
    {
        $this->validate([
            'content' => 'required|max:1024',
        ]);

        $profanityChecker = new Profanity();
        $hasProfanity = $profanityChecker->hasProfanity($this->content);

        $comment = $this->user->blogComments()->create([
            'content' => $this->content,
            'blog_id' => $this->blog->id,
            'user_id' => $this->user->id,
            'has_profanity' => $hasProfanity,
        ]);

        // Notify blog author if they're not the comment author
        if ($this->blog->user_id != $this->user->id) {
            Notification::create([
                'user_id' => $this->blog->user_id,
                'message' => $this->user->name . ' commented on your blog',
                'link' => route('blog', ['blogId' => $this->blog->id]),
            ]);
        }

        // Refresh comments
        $this->comments = $this->blog->comments()
            ->with('replies.user')
            ->whereNull('parent_id')
            ->orderBy('created_at', 'desc')
            ->get();

        // Notify people mentioned in the comment
        $mentionedUsers = $this->getMentionedUsers($this->content);

        foreach ($mentionedUsers as $mentionedUser) {
            if ($mentionedUser->id != $this->blog->user_id && $mentionedUser->id != $this->user->id) {
                Notification::create([
                    'user_id' => $mentionedUser->id,
                    'message' => $this->user->name . ' mentioned you in a blog comment',
                    'link' => route('blog', ['blogId' => $this->blog->id]),
                ]);
            }
        }
        
        $this->reset('content');
        
        // Dispatch an event to reset the form
        $this->dispatch('commentAdded');
    }

    public function deleteComment(int $commentId)
    {
        $comment = BlogComment::find($commentId);
        if (!$comment) {
            return;
        }
        
        if (auth()->check() && ($comment->user_id == auth()->id() || $this->blog->user_id == auth()->id() || auth()->user()->admin_rank > 2)) {
            $comment->delete();
            
            // Refresh comments with the updated structure
            $this->comments = $this->blog->comments()
                ->with('replies.user')
                ->whereNull('parent_id')
                ->orderBy('created_at', 'desc')
                ->get();
        }
    }

    private function convertMentionsToLinks($text)
    {
        $mention = preg_replace_callback('/@(\w+)/', function ($matches) {
            $u = $matches[1];
            $user = User::where('handle', $u)->first();
            if ($user) {
                return "<a class='hover:text-indigo-400 transition-colors' href='/@{$user->id}'>@{$u}</a>";
            } else {
                $uid = User::where('id', $u)->first();
                if ($uid) {
                    return "<a class='hover:text-indigo-400 transition-colors' href='/@{$uid->id}'>@{$u}</a>";
                } else {
                    return "@{$u}";
                }
            }
        }, $text);
        return $mention;
    }

    public function render()
    {
        $blogContent = $this->convertMentionsToLinks(Str::markdown($this->blog->content));
        
        // Process comment content including replies
        $comments = $this->comments->map(function ($comment) {
            $comment->content = $this->convertMentionsToLinks(Str::markdown($comment->content));
            
            // Process replies content
            if ($comment->replies && $comment->replies->count() > 0) {
                $comment->replies->map(function ($reply) {
                    $reply->content = $this->convertMentionsToLinks(Str::markdown($reply->content));
                    return $reply;
                });
            }
            
            return $comment;
        });

        return view('livewire.Blog.blog-page', [
            'blog' => $this->blog,
            'blogContent' => Str::markdown($blogContent),
            'comments' => $comments,
        ])->layout('layouts.app');
    }
}
