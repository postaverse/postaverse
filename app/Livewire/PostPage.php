<?php

namespace App\Livewire;

use App\Models\Post;
use Illuminate\Support\Collection;
use Livewire\Component;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use App\Models\Comment;
use App\Models\User;
use App\Models\Notification;
use App\Http\Controllers\DeleteController;
use App\Http\Controllers\LikeController;
use App\Livewire\Profanity;

class PostPage extends Component
{
    public $post;
    public $comments;
    public $content = '';
    public $user;
    public ?Collection $photos = null;
    
    // New properties for reply functionality
    public $replyingTo = null;
    public $replyContent = '';

    public function mount($postId)
    {
        if (Auth::check()) {
            $this->user = Auth::user();
        }
        
        $blockedUsers = [];
        if (Auth::check()) {
            $blockedUsers = $this->user->blockedUsers->pluck('blocked_users')->toArray();
            $blockedUsers = array_map('trim', explode(',', implode(',', $blockedUsers)));
        }

        $this->post = Post::query()
            ->with('images')
            ->where('id', $postId)
            ->firstOrFail();

        // Load comments with nested replies of any depth
        $this->comments = $this->post->comments()
            ->with(['user', 'replies.user', 'replies.replies.user', 'replies.replies.replies.user'])
            ->whereNull('parent_id') // Only root comments
            ->whereNotIn('user_id', $blockedUsers)
            ->orderBy('created_at', 'desc')
            ->get();
        
        $this->photos = $this->post->images;
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
        $parentComment = Comment::findOrFail($this->replyingTo);
        
        $profanityChecker = new Profanity();
        $hasProfanity = $profanityChecker->hasProfanity($this->replyContent);

        // Create the reply comment
        $reply = $this->user->comments()->create([
            'content' => $this->replyContent,
            'post_id' => $this->post->id,
            'parent_id' => $this->replyingTo,
            'user_id' => $this->user->id,
            'has_profanity' => $hasProfanity,
        ]);

        // Send notification to the parent comment author
        if ($parentComment->user_id != $this->user->id) {
            Notification::create([
                'user_id' => $parentComment->user_id,
                'message' => $this->user->name . ' replied to your comment',
                'link' => route('post', ['postId' => $this->post->id]),
            ]);
        }

        // Notify mentioned users
        $mentionedUsers = $this->getMentionedUsers($this->replyContent);
        
        foreach ($mentionedUsers as $mentionedUser) {
            if ($mentionedUser->id != $parentComment->user_id && $mentionedUser->id != $this->user->id) {
                Notification::create([
                    'user_id' => $mentionedUser->id,
                    'message' => $this->user->name . ' mentioned you in a comment reply',
                    'link' => route('post', ['postId' => $this->post->id]),
                ]);
            }
        }

        // Reset reply state
        $this->replyingTo = null;
        $this->replyContent = '';

        // Refresh comments
        $this->comments = $this->post->comments()
            ->with('replies.user')
            ->whereNull('parent_id')
            ->orderBy('created_at', 'desc')
            ->get();

        // Dispatch an event to reset the form
        $this->dispatch('replyAdded');
    }

    public function likePost($postId)
    {
        $likeController = new LikeController();
        $likeController->likePost($postId);
    }

    private function getMentionedUsers($text)
    {
        $mention = preg_match_all('/@(\w+)/', $text, $matches);
        // Handle or ID
        $users = User::whereIn('handle', $matches[1])->orWhereIn('id', $matches[1])->get();
        return $users;
    }

    public function delete(int $postId)
    {
        $deleteController = new DeleteController();
        $deleteController->deletePost($postId);

        return redirect()->route('home');
    }

    public function submit()
    {
        $this->validate([
            'content' => 'required|max:1024',
        ]);

        $profanityChecker = new Profanity();
        $hasProfanity = $profanityChecker->hasProfanity($this->content);

        $comment = $this->user->comments()->create([
            'content' => $this->content,
            'post_id' => $this->post->id,
            'user_id' => $this->user->id,
            'has_profanity' => $hasProfanity,
        ]);

        // Notify post author if they're not the comment author
        if ($this->post->user_id != $this->user->id) {
            Notification::create([
                'user_id' => $this->post->user_id,
                'message' => $this->user->name . ' commented on your post',
                'link' => route('post', ['postId' => $this->post->id]),
            ]);
        }

        // Refresh comments
        $this->comments = $this->post->comments()
            ->with('replies.user')
            ->whereNull('parent_id')
            ->orderBy('created_at', 'desc')
            ->get();

        // Notify people mentioned in the comment
        $mentionedUsers = $this->getMentionedUsers($this->content);

        foreach ($mentionedUsers as $mentionedUser) {
            if ($mentionedUser->id != $this->post->user_id && $mentionedUser->id != $this->user->id) {
                Notification::create([
                    'user_id' => $mentionedUser->id,
                    'message' => $this->user->name . ' mentioned you in a comment',
                    'link' => route('post', ['postId' => $this->post->id]),
                ]);
            }
        }
        $this->reset('content');
        
        // Dispatch an event to reset the form
        $this->dispatch('commentAdded');
    }

    public function deleteComment(int $commentId)
    {
        $deleteController = new DeleteController();
        $deleteController->deleteComment($commentId);

        // Refresh comments with the updated structure
        $this->comments = $this->post->comments()
            ->with('replies.user')
            ->whereNull('parent_id')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    private function convertMentionsToLinks($text)
    {
        $mention = preg_replace_callback('/@(\w+)/', function ($matches) {
            $u = $matches[1];
            $user = User::where('handle', $u)->first();
            if ($user) {
                return "<a class='hyperlink' href='/@{$user->id}'>@{$u}</a>";
            } else {
                $uid = User::where('id', $u)->first();
                if ($uid) {
                    return "<a class='hyperlink' href='/@{$uid->id}'>@{$u}</a>";
                } else {
                    return "@{$u}";
                }
            }
        }, $text);
        return $mention;
    }

    public function render()
    {
        $postContent = $this->convertMentionsToLinks(Str::markdown($this->post->content));
        
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

        return view('livewire.post-page', [
            'post' => $this->post,
            'postContent' => $postContent,
            'comments' => $comments,
        ])->layout('layouts.app');
    }
}
