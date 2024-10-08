<?php

namespace App\Livewire;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Post;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Http;
use App\Livewire\Profanity;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\DeleteController;
use Parsedown;

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
        $insult = ''; // Initialize an empty insult string

        if ($this->query === '??ramsay??') {
            $response = Http::get('https://api.zanderlewis.dev/ramsay_insult.php');

            if ($response->successful()) {
                $data = $response->json();
                $insult = $data['insult']; // Store the insult from the API response
            }
        }

        // Regular search logic
        $users = User::where('name', 'LIKE', "%{$this->query}%")->orderByDesc('id')->paginate(5);
        $posts = Post::where('title', 'LIKE', "%{$this->query}%")
            ->orWhere('content', 'LIKE', "%{$this->query}%")
            ->orderByDesc('id')
            ->paginate(5);

        // Pass the insult along with users and posts to the view
        return view('livewire.search', compact('users', 'posts', 'insult'))->layout('layouts.app');
    }
}
