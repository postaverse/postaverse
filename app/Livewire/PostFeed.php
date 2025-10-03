<?php

namespace App\Livewire;

use App\Models\Like;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\On;

class PostFeed extends Component
{
    use WithPagination;

    public $feedType = 'following'; // 'following', 'discover', 'trending'

    protected $listeners = ['postCreated' => '$refresh'];

    public function mount($feedType = 'following')
    {
        $this->feedType = $feedType;
    }

    #[On('postCreated')]
    public function refreshFeed()
    {
        $this->resetPage();
    }

    public function toggleLike($postId)
    {
        if (!Auth::check()) {
            return;
        }

        $post = Post::findOrFail($postId);
        $liked = Like::toggle(Auth::user(), $post);

        // Dispatch event for real-time updates
        $this->dispatch('likeToggled', [
            'postId' => $postId,
            'liked' => $liked,
            'likesCount' => $post->refresh()->likes_count
        ]);
    }

    public function incrementViews($postId)
    {
        $post = Post::find($postId);
        if ($post) {
            $post->incrementViews();
        }
    }

    public function getPosts()
    {
        $query = Post::with([
            'user', 
            'comments.user', 
            'comments.replies.user',
            'likes',
            'sharedPost.user'
        ])
            ->where('visibility', 'public')
            ->latest();

        switch ($this->feedType) {
            case 'following':
                if (Auth::check()) {
                    $followingIds = Auth::user()->following()->pluck('following_id');
                    $followingIds->push(Auth::id()); // Include own posts
                    $query->whereIn('user_id', $followingIds);
                }
                break;
            
            case 'trending':
                $query->where('created_at', '>=', now()->subHours(24))
                      ->orderByDesc('likes_count')
                      ->orderByDesc('comments_count');
                break;
            
            case 'discover':
            default:
                // Show all public posts
                break;
        }

        return $query->paginate(10);
    }

    public function switchFeed($type)
    {
        $this->feedType = $type;
        $this->resetPage();
    }

    public function render()
    {
        return view('livewire.post-feed', [
            'posts' => $this->getPosts(),
        ]);
    }
}
