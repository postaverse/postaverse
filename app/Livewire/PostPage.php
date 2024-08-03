<?php

namespace App\Livewire;

use App\Models\Post;
use Livewire\Component;
use Parsedown;
use Illuminate\Support\Facades\Auth;
use App\Models\Comment;
use App\Models\Like;
use App\Models\User;

class PostPage extends Component
{
    public $post;
    public $comments;
    public $content = '';
    public $user;

    public function mount($postId)
    {
        if (Auth::check()) {
            $this->user = Auth::user();
        }
        $this->post = Post::find($postId);
        if (!$this->post) {
            abort(404);
        }
        $this->comments = Comment::where('post_id', $this->post->id)->orderBy('created_at', 'desc')->get();
    }

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

        // Clear the textarea
        $this->content = '';

        $this->comments = $this->user->comments()->where('post_id', $this->post->id)->get();
    }

    private function convertMentionsToLinks($text)
    {
        return preg_replace_callback('/@(\w+)/', function ($matches) {
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