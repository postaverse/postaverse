<?php

namespace App\Livewire\Admin;

use App\Models\Post;
use App\Models\Report;
use App\Models\User;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.app')]
class Moderation extends Component
{
    use WithPagination;

    public $filter = 'all'; // all, reported, flagged

    public function removePost($postId)
    {
        $this->checkAdminAccess();
        
        $post = Post::findOrFail($postId);
        $post->delete();
        
        session()->flash('message', 'Post removed successfully');
    }

    public function suspendUser($userId)
    {
        $this->checkAdminAccess();
        
        $user = User::findOrFail($userId);
        $user->update(['is_suspended' => true]);
        
        session()->flash('message', 'User suspended successfully');
    }

    public function render()
    {
        $this->checkAdminAccess();

        $posts = Post::query()
            ->with(['user', 'reports'])
            ->when($this->filter === 'reported', function ($query) {
                $query->whereHas('reports');
            })
            ->when($this->filter === 'flagged', function ($query) {
                $query->where('is_flagged', true);
            })
            ->latest()
            ->paginate(20);

        $reports = Report::with(['user', 'reportedUser', 'post'])
            ->where('status', 'pending')
            ->latest()
            ->take(10)
            ->get();

        return view('livewire.admin.moderation', [
            'posts' => $posts,
            'reports' => $reports,
        ]);
    }

    private function checkAdminAccess()
    {
        if (!auth()->user()->admin_level) {
            abort(403, 'Unauthorized access');
        }
    }
}
