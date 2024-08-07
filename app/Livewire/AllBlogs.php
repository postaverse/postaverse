<?php

namespace App\Livewire;

use App\Models\Blog;
use Livewire\Component;
use Livewire\WithPagination;
use Parsedown;

class AllBlogs extends Component
{
    use WithPagination;

    public $expandedPosts = [];

    public function toggleExpand($postId)
    {
        if (in_array($postId, $this->expandedPosts)) {
            $this->expandedPosts = array_diff($this->expandedPosts, [$postId]);
        } else {
            $this->expandedPosts[] = $postId;
        }
    }

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