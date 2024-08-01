<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Blog;
use Illuminate\Support\Facades\Auth;

class Blogs extends Component
{
    public $blogs;

    public function mount()
    {
        $this->blogs = Blog::all();
    }

    public function render()
    {
        return view('blogs', ['blogs' => $this->blogs, 'user' => Auth::user()])->layout('layouts.app');
    }
}
