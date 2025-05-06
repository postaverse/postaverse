<?php

namespace App\Livewire\Blog;

use App\Models\Blog\Blog;
use App\Models\Blog\BlogLike;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class AllBlogs extends Component
{
    use WithPagination;

    public function delete(int $blogId)
    {
        $blog = Blog::find($blogId);
        if (auth()->check() && ($blog->user_id == auth()->id() || auth()->user()->admin_rank >= 3)) {
            $blog->delete();
        }
    }
    
    public function likeBlog(int $blogId)
    {
        if (!auth()->check()) {
            return;
        }
        
        $blog = Blog::find($blogId);
        if (!$blog) {
            return;
        }
        
        $like = BlogLike::where('blog_id', $blogId)
            ->where('user_id', auth()->id())
            ->first();
            
        if ($like) {
            $like->delete();
        } else {
            BlogLike::create([
                'blog_id' => $blogId,
                'user_id' => auth()->id()
            ]);
        }
    }

    public function render()
    {
        $blogs = Blog::with(['user', 'comments', 'likes'])->latest()->paginate(5);
        $blogs->getCollection()->transform(function ($blog) {
            $blog->content = Str::markdown($blog->content);
            return $blog;
        });
        return view('livewire.Blog.all-blogs', compact('blogs'))->layout('layouts.app');
    }
}