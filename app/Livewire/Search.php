<?php
namespace App\Livewire;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Post;
use Livewire\Component;
use Livewire\WithPagination; // Import the WithPagination trait

class Search extends Component
{
    use WithPagination; // Use the trait in your component

    public $query = '';

    public function mount(Request $request)
    {
        $this->query = $request->input('query', '');
    }

    public function render()
    {
        // Search users with pagination
        $users = User::where('name', 'LIKE', "%{$this->query}%")->orderByDesc('id')->paginate(5); // Adjust the number per page as needed
        // Search posts with pagination
        $posts = Post::where('title', 'LIKE', "%{$this->query}%")
                     ->orWhere('content', 'LIKE', "%{$this->query}%")
                     ->orderByDesc('id')
                     ->paginate(5); // Adjust the number per page as needed

        return view('livewire.search', compact('users', 'posts'))->layout('layouts.app');
    }
}