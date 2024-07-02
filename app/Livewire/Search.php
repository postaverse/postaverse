<?php
namespace App\Livewire;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Post;
use Livewire\Component;

class Search extends Component
{
    public $query = '';

    public function mount(Request $request)
    {
        $this->query = $request->input('query', '');
    }

    public function render()
    {
        // Search users
        $users = User::where('name', 'LIKE', "%{$this->query}%")->get();
        // Search posts
        $posts = Post::where('title', 'LIKE', "%{$this->query}%")
                     ->orWhere('content', 'LIKE', "%{$this->query}%")
                     ->get();

        return view('livewire.search', compact('users', 'posts'))->layout('layouts.app');
    }
}