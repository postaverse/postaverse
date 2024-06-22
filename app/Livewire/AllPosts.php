<?php

namespace App\Livewire;

use App\Models\Post;
use Livewire\Component;
use Livewire\WithPagination;

class AllPosts extends Component
{
    use WithPagination;

    public function delete(int $postId)
    {
        Post::query()
            ->where('user_id', auth()->id())
            ->where('id', $postId)
            ->delete();
    }

    public function render()
    {
        return view('livewire.all-posts', [
            'posts' => Post::query()
                ->orderByDesc('id')
                ->paginate(20),
        ]);
    }
}
