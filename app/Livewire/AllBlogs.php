<?php

namespace App\Livewire;

use App\Models\Blog;
use Livewire\Component;
use Livewire\WithPagination;
use Parsedown;

class AllBlogs extends Component
{
    use WithPagination;

    public function delete(int $blogId)
    {
        Blog::query()
            ->where('id', $blogId)
            ->delete();
    }

    public function render()
    {
        $parsedown = new Parsedown();
        $blogs = Blog::latest()->paginate(5);
        return view('livewire.all-blogs', compact('blogs', 'parsedown'))->layout('layouts.app');
    }
}