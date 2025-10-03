<?php

namespace App\Livewire;

use App\Models\Comment;
use App\Models\Like;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\Attributes\On;

class PostActions extends Component
{
    public Post $post;
    public $isLiked = false;
    public $isSaved = false;
    public $showShareModal = false;
    public $shareMessage = '';

    public function mount(Post $post)
    {
        $this->post = $post;
        if (Auth::check()) {
            $this->isLiked = $this->post->isLikedBy(Auth::user());
            $this->isSaved = Auth::user()->savedPosts()->where('post_id', $this->post->id)->exists();
        }
    }

    #[On('commentAdded')]
    public function handleCommentAdded($postId)
    {
        // Refresh post data if this comment was added to our post
        if ($postId === $this->post->id) {
            $this->post = $this->post->fresh();
        }
    }

    public function toggleLike()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $this->isLiked = Like::toggle(Auth::user(), $this->post);
        $this->post = $this->post->fresh();
        
        $this->dispatch('likeToggled', [
            'postId' => $this->post->id,
            'liked' => $this->isLiked,
            'likesCount' => $this->post->likes_count
        ]);
    }

    public function toggleSave()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        if ($this->isSaved) {
            $user->savedPosts()->detach($this->post->id);
            $this->isSaved = false;
        } else {
            $user->savedPosts()->attach($this->post->id);
            $this->isSaved = true;
        }

        $this->dispatch('postSaveToggled', [
            'postId' => $this->post->id,
            'saved' => $this->isSaved
        ]);
    }

    public function openComments()
    {
        // Scroll to comments section using JavaScript
        $this->dispatch('scrollToComments', ['postId' => $this->post->id]);
    }

    public function openShareModal()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        
        $this->showShareModal = true;
    }

    public function closeShareModal()
    {
        $this->showShareModal = false;
        $this->shareMessage = '';
    }

    public function sharePost()
    {
        if (!Auth::check()) {
            return;
        }

        $this->validate([
            'shareMessage' => 'nullable|string|max:512'
        ]);

        // Create a new post that shares the original
        $sharedPost = Post::create([
            'user_id' => Auth::id(),
            'content' => $this->shareMessage,
            'shared_post_id' => $this->post->id,
            'visibility' => 'public',
        ]);

        // Increment shares count
        $this->post->incrementShares();
        $this->post = $this->post->fresh();

        $this->closeShareModal();
        
        $this->dispatch('postShared', [
            'postId' => $this->post->id,
            'sharesCount' => $this->post->shares_count
        ]);

        session()->flash('message', 'Post shared successfully!');
    }

    public function copyLink()
    {
        $link = route('posts.show', $this->post->id);
        
        $this->dispatch('linkCopied', [
            'link' => $link
        ]);
    }

    public function render()
    {
        return view('livewire.post-actions');
    }
}
