<?php

namespace App\Actions\Postaverse;

use App\Models\Post;
use App\Models\User;

class CreatePost
{
    public function create(string $title, string $content): Post
    {
        /** @var User $authUser */
        $authUser = auth()->user();

        /** @var Post $post */
        $post = $authUser->posts()->create([
            'title' => $title,
            'content' => $content,
        ]);

        return $post;
    }
}
