<?php

namespace App\Livewire;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Post;
use Livewire\Component;
use Livewire\WithPagination;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\DeleteController;

class Search extends Component
{
    use WithPagination;

    public $query = '';

    public function mount(Request $request)
    {
        $this->query = $request->input('query', '');
    }

    public function likePost($postId)
    {
        $likeController = new LikeController();
        $likeController->likePost($postId);
    }

    public function delete(int $postId)
    {
        $deleteController = new DeleteController();
        $deleteController->deletePost($postId);
    }

    public function render()
    {
        // Regular search logic
        $users = User::where('name', 'LIKE', "%{$this->query}%")->orderByDesc('id')->paginate(5);
        $posts = Post::where('title', 'LIKE', "%{$this->query}%")
            ->orWhere('content', 'LIKE', "%{$this->query}%")
            ->orderByDesc('id')
            ->paginate(5);

        return view('livewire.search', compact('users', 'posts'))->layout('layouts.app');
    }
}
