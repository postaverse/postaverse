<?php

namespace App\Livewire;

use App\Models\Post;
use Illuminate\Support\Collection;
use Livewire\Component;
use Parsedown;
use Illuminate\Support\Facades\Auth;
use App\Models\Comment;
use App\Models\Like;
use App\Models\User;
use App\Models\Notification;
use App\Http\Controllers\DeleteController;
use App\Http\Controllers\LikeController;

class PostPage extends Component
{
    public $post;
    public $comments;
    public $content = '';
    public $user;
    public ?Collection $photos = null;

    public function mount($postId)
    {
        if (Auth::check()) {
            $this->user = Auth::user();
        }

        $this->post = Post::query()
            ->with('images', 'comments')
            ->where('id', $postId)
            ->firstOrFail();

        $this->comments = $this->post->comments;
        $this->photos = $this->post->images;
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
            'content' => 'required|max:500',
        ]);

        $this->user->comments()->create([
            'content' => $this->content,
            'post_id' => $this->post->id,
            'user_id' => $this->user->id,
        ]);

        $this->comments = Comment::where('post_id', $this->post->id)->orderBy('created_at', 'desc')->get();

        // Notify people mentioned in the comment

        $mentionedUsers = $this->getMentionedUsers($this->content);

        foreach ($mentionedUsers as $mentionedUser) {
            Notification::create([
                'user_id' => $mentionedUser->id,
                'message' => $this->user->name . ' mentioned you in a comment',
                'link' => route('post', ['postId' => $this->post->id]),
            ]);
        }
        $this->reset('content');
    }

    public function deleteComment(int $commentId)
    {
        $deleteController = new DeleteController();
        $deleteController->deleteComment($commentId);

        $this->comments = Comment::where('post_id', $this->post->id)->orderBy('created_at', 'desc')->get();
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
        $parsedown = new Parsedown();
        $postContent = $this->convertMentionsToLinks($parsedown->text(e($this->post->content)));
        $comments = $this->comments->map(function ($comment) use ($parsedown) {
            $comment->content = $this->convertMentionsToLinks($parsedown->text(e($comment->content)));
            return $comment;
        });

        return view('livewire.post-page', [
            'post' => $this->post,
            'parsedown' => $parsedown,
            'postContent' => $postContent,
            'comments' => $comments,
        ])->layout('layouts.app');
    }
}
