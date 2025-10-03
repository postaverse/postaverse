<?php

namespace App\Livewire;

use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class PostFeedItem extends Component
{
    public Post $post;
    public $showComments = false;

    public function mount(Post $post)
    {
        $this->post = $post->load(['user', 'likes', 'comments.user']);
    }

    public function toggleLike()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $existingLike = $this->post->likes()->where('user_id', Auth::id())->first();
        
        if ($existingLike) {
            $existingLike->delete();
        } else {
            $this->post->likes()->create([
                'user_id' => Auth::id()
            ]);
            
            // Create notification if not own post
            if ($this->post->user_id !== Auth::id()) {
                $this->post->user->notifications()->create([
                    'type' => 'like',
                    'data' => [
                        'liker_id' => Auth::id(),
                        'liker_name' => Auth::user()->display_name,
                        'post_id' => $this->post->id,
                        'message' => Auth::user()->display_name . ' liked your post'
                    ],
                    'notifiable_type' => get_class($this->post->user),
                    'notifiable_id' => $this->post->user_id
                ]);
            }
        }

        // Refresh the post to get updated like count
        $this->post->refresh();
        $this->post->load(['likes']);
    }

    public function toggleComments()
    {
        $this->showComments = !$this->showComments;
    }

    public function render()
    {
        return view('livewire.post-feed-item');
    }
}
