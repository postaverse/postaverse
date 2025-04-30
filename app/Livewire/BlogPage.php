<?php

namespace App\Livewire;

use App\Models\Blog;
use App\Models\BlogComment;
use App\Models\BlogLike;
use App\Models\BlogImage;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Livewire\Component;

class BlogPage extends Component
{
    public $blog;
    public $comments;
    public $content = '';
    public $user;
    public ?Collection $images = null;

    public function mount($blogId)
    {
        if (Auth::check()) {
            $this->user = Auth::user();
        }
        
        $blockedUsers = [];
        if (Auth::check()) {
            $blockedUsers = $this->user->blockedUsers->pluck('blocked_users')->toArray();
            $blockedUsers = array_map('trim', explode(',', implode(',', $blockedUsers)));
        }

        $this->blog = Blog::query()
            ->with('images')
            ->where('id', $blogId)
            ->firstOrFail();
            
        $this->comments = $this->blog->comments()->whereNotIn('user_id', $blockedUsers)->get();
        $this->images = $this->blog->images;
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

    private function getMentionedUsers($text)
    {
        $mention = preg_match_all('/@(\w+)/', $text, $matches);
        // Handle or ID
        $users = User::whereIn('handle', $matches[1])->orWhereIn('id', $matches[1])->get();
        return $users;
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

        $this->comments = BlogComment::where('blog_id', $this->blog->id)->orderBy('created_at', 'desc')->get();

        // Notify people mentioned in the comment
        $mentionedUsers = $this->getMentionedUsers($this->content);

        foreach ($mentionedUsers as $mentionedUser) {
            Notification::create([
                'user_id' => $mentionedUser->id,
                'message' => $this->user->name . ' mentioned you in a blog comment',
                'link' => route('blog', ['blogId' => $this->blog->id]),
            ]);
        }
        
        // Also notify the blog author if they're not the comment author
        if ($this->blog->user_id != $this->user->id) {
            Notification::create([
                'user_id' => $this->blog->user_id,
                'message' => $this->user->name . ' commented on your blog',
                'link' => route('blog', ['blogId' => $this->blog->id]),
            ]);
        }
        
        $this->reset('content');
    }

    public function deleteComment(int $commentId)
    {
        $comment = BlogComment::find($commentId);
        if (!$comment) {
            return;
        }
        
        if (auth()->check() && ($comment->user_id == auth()->id() || $this->blog->user_id == auth()->id() || auth()->user()->admin_rank > 2)) {
            $comment->delete();
            $this->comments = BlogComment::where('blog_id', $this->blog->id)->orderBy('created_at', 'desc')->get();
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
        $comments = $this->comments->map(function ($comment) {
            $comment->content = $this->convertMentionsToLinks(Str::markdown($comment->content));
            return $comment;
        });

        return view('livewire.blog-page', [
            'blog' => $this->blog,
            'blogContent' => $blogContent,
            'comments' => $comments,
        ])->layout('layouts.app');
    }
}
