<?php

namespace App\Livewire\Social;

use App\Models\Post;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.app')]
class SavedPosts extends Component
{
    use WithPagination;

    public function unsavePost($postId)
    {
        auth()->user()->savedPosts()->detach($postId);
        session()->flash('message', 'Post removed from saved items');
    }

    public function likePost($postId)
    {
        $post = Post::findOrFail($postId);
        
        if (auth()->user()->likes()->where('post_id', $postId)->exists()) {
            auth()->user()->likes()->where('post_id', $postId)->delete();
        } else {
            auth()->user()->likes()->create(['post_id' => $postId]);
        }
    }

    public function toggleLike($postId)
    {
        // Alias for likePost to match post-card component expectations
        $this->likePost($postId);
    }

    public function render()
    {
        $savedPosts = auth()->user()->savedPosts()
            ->with(['user', 'likes', 'comments'])
            ->orderByPivot('created_at', 'desc')
            ->paginate(10);

        return view('livewire.social.saved-posts', [
            'savedPosts' => $savedPosts,
        ]);
    }
}
