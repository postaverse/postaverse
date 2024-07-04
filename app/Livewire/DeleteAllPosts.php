<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Post;

class deleteAllPosts extends Component
{
    public function deleteAllPosts()
    {
        Post::query()->delete();
        return redirect()->route('home');
    }
}
