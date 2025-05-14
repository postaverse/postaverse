<?php

namespace App\Livewire\Blog;

use App\Livewire\Content\BaseList;
use App\Models\Blog\Blog;
use Illuminate\Support\Str;

class AllBlogs extends BaseList
{
    protected function getModel(): string
    {
        return Blog::class;
    }

    protected function getRelations(): array
    {
        return ['user', 'comments', 'likes'];
    }

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
        $like = $blog->likes()->where('user_id', auth()->id())->first();
        if ($like) {
            $like->delete();
        } else {
            $blog->likes()->create(['user_id' => auth()->id()]);
        }
        // Refresh count or similar if needed
    }

    protected function view(): string
    {
        return 'livewire.Blog.all-blogs';
    }

    protected function getVariableName(): string
    {
        return 'blogs';
    }

    protected function transformItem($blog)
    {
        $blog->content = Str::markdown($blog->content);
        return $blog;
    }
}