<?php

namespace App\Livewire;

use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ShowPost extends Component
{
    public Post $post;

    public function mount(Post $post)
    {
        $this->post = $post->load([
            'user', 
            'comments.user', 
            'comments.replies.user',
            'likes',
            'sharedPost.user'
        ]);

        // Increment views for this post
        $this->post->incrementViews();
    }

    public function render()
    {
        return view('livewire.show-post')
            ->layout('components.layouts.app')
            ->title($this->post->user->display_name . ' on Postaverse');
    }
}
