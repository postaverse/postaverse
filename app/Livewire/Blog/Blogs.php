<?php

namespace App\Livewire\Blog;

use Livewire\Component;
use App\Models\Blog\Blog;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class Blogs extends Component
{
    use WithPagination;

    public function mount()
    {
        // No need to initialize $blogs here when using pagination
    }

    public function render()
    {
        $blogs = Blog::paginate(10); // Show 10 blogs per page
        return view('blogs', ['blogs' => $blogs])->layout('layouts.app');
    }
}
