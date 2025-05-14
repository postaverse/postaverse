<?php

namespace App\Livewire\Content;

use App\Livewire\Content\BasePage;
use App\Livewire\Content\HandlesReplies;
use App\Models\Post\Post;
use App\Models\Post\PostComment;
use App\Models\Blog\Blog;
use App\Models\Blog\BlogLike;
use App\Models\Blog\BlogComment;
use App\Models\User\User;
use App\Models\Interaction\Notification;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class ContentPage extends BasePage
{
    use HandlesReplies;

    public $content;
    public $contentType;
    public ?Collection $images = null;

    // We use commentText instead of content to avoid conflicts with the content model
    public $commentText = '';
    
    public function mount($contentId, $type = null)
    {
        $this->initializeBasePage();
        
        // Determine content type (post or blog)
        $this->contentType = $type ?? request()->route()->getName();
        
        // Get blocked users
        $blockedUsers = [];
        if (auth()->check()) {
            $blockedUsers = $this->user->blockedUsers->pluck('blocked_users')->toArray();
            $blockedUsers = array_map('trim', explode(',', implode(',', $blockedUsers)));
        }

        // Load appropriate content
        if ($this->contentType === 'blog' || $this->contentType === 'blogs') {
            $this->content = Blog::with(['images', 'likes.user'])->findOrFail($contentId);
            $this->images = $this->content->images;
            $this->comments = $this->content->comments()
                ->with(['user','replies.user','replies.replies.user','replies.replies.replies.user'])
                ->whereNull('parent_id')
                ->whereNotIn('user_id', $blockedUsers)
                ->orderBy('created_at','desc')
                ->get();
        } else {
            $this->content = Post::with(['images', 'likes.user'])->findOrFail($contentId);
            $this->images = $this->content->images;
            $this->comments = $this->content->comments()
                ->with(['user','replies.user','replies.replies.user','replies.replies.replies.user'])
                ->whereNull('parent_id')
                ->whereNotIn('user_id', $blockedUsers)
                ->orderBy('created_at','desc')
                ->get();
        }
    }

    // Like content (post or blog)
    public function likeContent($contentId)
    {
        if (!auth()->check()) {
            return;
        }
        
        if ($this->contentType === 'blog' || $this->contentType === 'blogs') {
            $blog = Blog::find($contentId);
            if (!$blog) {
                return;
            }
            
            $like = BlogLike::where('blog_id', $contentId)
                ->where('user_id', auth()->id())
                ->first();
                
            if ($like) {
                $like->delete();
            } else {
                BlogLike::create([
                    'blog_id' => $contentId,
                    'user_id' => auth()->id()
                ]);
            }
            
            $this->content = Blog::with('likes')->find($contentId);
        } else {
            // Handle post like
            $likeController = new \App\Http\Controllers\LikeController();
            $likeController->likePost($contentId);
            $this->content = Post::with('likes')->find($contentId);
        }
    }

    // Delete content (post or blog)
    public function delete($contentId)
    {
        if ($this->contentType === 'blog' || $this->contentType === 'blogs') {
            $blog = Blog::find($contentId);
            if (auth()->check() && ($blog->user_id == auth()->id() || auth()->user()->admin_rank >= 3)) {
                $blog->delete();
                return redirect()->route('blogs');
            }
        } else {
            $deleteController = new \App\Http\Controllers\DeleteController();
            $deleteController->deletePost($contentId);
            return redirect()->route('home');
        }
    }

    // Submit a comment on either post or blog
    public function submit()
    {
        $this->validate([
            'commentText' => 'required|max:1024',
        ]);

        if ($this->contentType === 'blog' || $this->contentType === 'blogs') {
            $comment = $this->content->comments()->create([
                'user_id' => auth()->id(),
                'content' => $this->commentText,
                'blog_id' => $this->content->id
            ]);
            
            $this->comments = $this->content->comments()
                ->with('replies.user')
                ->whereNull('parent_id')
                ->orderBy('created_at','desc')
                ->get();
                
            $this->dispatchNotifications(
                $this->getMentionedUsers($this->commentText), 
                'mentioned you in a blog comment', 
                route('blog', ['contentId' => $this->content->id])
            );
            
            $this->commentText = '';
            $this->emit('commentAdded');
        } else {
            $comment = $this->content->comments()->create([
                'user_id' => auth()->id(),
                'content' => $this->commentText,
                'post_id' => $this->content->id
            ]);
            
            $this->comments = $this->content->comments()
                ->with('replies.user')
                ->whereNull('parent_id')
                ->orderBy('created_at','desc')
                ->get();
                
            $this->dispatchNotifications(
                $this->getMentionedUsers($this->commentText), 
                'mentioned you in a comment', 
                route('post', ['contentId' => $this->content->id])
            );
            
            $this->commentText = '';
            $this->emit('commentAdded');
        }
    }

    // Delete a comment
    public function deleteComment($commentId)
    {
        if ($this->contentType === 'blog' || $this->contentType === 'blogs') {
            $comment = BlogComment::find($commentId);
            if (auth()->check() && ($comment->user_id == auth()->id() || 
                $this->content->user_id == auth()->id() || 
                auth()->user()->admin_rank > 2)) {
                $comment->delete();
                
                // Refresh comments with the updated structure
                $this->comments = $this->content->comments()
                    ->with('replies.user')
                    ->whereNull('parent_id')
                    ->orderBy('created_at', 'desc')
                    ->get();
            }
        } else {
            // Handle post comment deletion
            $comment = PostComment::find($commentId);
            if (auth()->check() && ($comment->user_id == auth()->id() || 
                $this->content->user_id == auth()->id() || 
                auth()->user()->admin_rank > 2)) {
                $comment->delete();
                
                // Refresh comments with the updated structure
                $this->comments = $this->content->comments()
                    ->with('replies.user')
                    ->whereNull('parent_id')
                    ->orderBy('created_at', 'desc')
                    ->get();
            }
        }
    }

    // Helper to format mentions as links
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
        $formattedContent = $this->convertMentionsToLinks(Str::markdown($this->content->content));
        
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

        return view('livewire.Content.content-page', [
            'content' => $this->content,
            'formattedContent' => $formattedContent,
            'comments' => $comments,
            'contentType' => $this->contentType,
            'images' => $this->images
        ])->layout('layouts.app');
    }
}
