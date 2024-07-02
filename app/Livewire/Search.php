<?php

namespace App\Livewire;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Post;
use Livewire\Component;

class Search extends Component
{
    public function index(Request $request)
    {
        $query = $request->input('query');
        // Search users
        $users = User::where('name', 'LIKE', "%{$query}%")->get();
        // Search posts
        $posts = Post::where('title', 'LIKE', "%{$query}%")
                     ->orWhere('content', 'LIKE', "%{$query}%")
                     ->get();
        // Combine users and posts into one collection if needed
        $searchResults = $users->concat($posts);
        // Alternatively, you can pass users and posts separately if they need to be handled differently in the view
        return view('search.results', compact('searchResults'));
    }
}