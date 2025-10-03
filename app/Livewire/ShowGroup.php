<?php

namespace App\Livewire;

use App\Models\Group;
use App\Models\Post;
use App\Models\Like;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.app')]
class ShowGroup extends Component
{
    use WithPagination;

    public Group $group;
    public $sortBy = 'hot'; // hot, new, top
    public $showCreateModal = false;
    // post creation delegated to shared CreatePost component

    public function mount($group)
    {
        // Accept either a Group instance (route-model binding) or an id/slug
        if ($group instanceof Group) {
            $this->group = $group->load(['creator', 'members'])->loadCount('members');
        } else {
            $this->group = Group::with(['creator', 'members'])->withCount('members')
                ->findOrFail($group);
        }
    }

    public function joinGroup()
    {
        if (!$this->group->isMember(auth()->user())) {
            $this->group->addMember(auth()->user());
            session()->flash('message', 'Joined group successfully!');
        }
    }

    public function leaveGroup()
    {
        if ($this->group->isMember(auth()->user())) {
            $this->group->removeMember(auth()->user());
            session()->flash('message', 'Left group successfully!');
        }
    }


    public function votePost($postId, $voteType)
    {
        // Convert votes to simple like/unlike on canonical posts
        $post = Post::findOrFail($postId);

        // If user already liked the post, unlike it; otherwise like it.
        $existingLike = $post->likes()->where('user_id', auth()->id())->first();

        if ($existingLike) {
            $existingLike->delete();
            $post->decrement('likes_count');
        } else {
            Like::create([
                'user_id' => auth()->id(),
                'likeable_id' => $post->id,
                'likeable_type' => Post::class,
            ]);
            $post->increment('likes_count');
        }
    }

    // Called by included post-card components to toggle a like
    public function toggleLike($postId)
    {
        $this->votePost($postId, 'up');
    }

    public function render()
    {
    $postsQuery = $this->group->posts()->with(['user', 'likes']);

        // Apply sorting
        switch ($this->sortBy) {
            case 'new':
                $postsQuery->latest();
                break;
            case 'top':
                // Order by total likes
                $postsQuery->orderBy('likes_count', 'desc');
                break;
            case 'hot':
            default:
                // Simple hot algorithm: score divided by age in hours
                // Use SQLite-friendly expression when using sqlite driver
                if (DB::getDriverName() === 'sqlite') {
                    // likes_count / ((now_seconds - created_seconds)/3600 + 2)
                    $postsQuery->orderByRaw("(COALESCE(likes_count,0)) / (((strftime('%s', 'now') - strftime('%s', created_at))/3600.0) + 2) DESC");
                } else {
                    $postsQuery->orderByRaw('(COALESCE(likes_count,0)) / (TIMESTAMPDIFF(HOUR, created_at, NOW()) + 2) DESC');
                }
                break;
        }

        $posts = $postsQuery->paginate(10);
        $isMember = auth()->check() ? $this->group->isMember(auth()->user()) : false;

        return view('livewire.show-group', [
            'posts' => $posts,
            'isMember' => $isMember,
        ]);
    }
}
