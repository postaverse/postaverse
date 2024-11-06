<?php

namespace App\Livewire;

use App\Models\Blog;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Str;

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
        $blogs = Blog::latest()->paginate(5);
        $blogs->getCollection()->transform(function ($blog) {
            $blog->content = Str::markdown($blog->content);
            return $blog;
        });
        return view('livewire.all-blogs', compact('blogs'))->layout('layouts.app');
    }
}