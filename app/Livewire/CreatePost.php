<?php

namespace App\Livewire;

use App\Models\User;
use Illuminate\Cache\RateLimiter;
use Livewire\Component;

class CreatePost extends Component
{
    public string $title = '';
    public string $content = '';

    // Computed property for title character count
    public function getTitleCountProperty()
    {
        return strlen($this->title);
    }

    // Computed property for content character count
    public function getContentCountProperty()
    {
        return strlen($this->content);
    }

    public function submit()
    {
        // Check if the user has exceeded their rate limit for post submissions
        $rateLimiter = app('Illuminate\Cache\RateLimiter');

        // Check if the user has exceeded their rate limit for post submissions
        $rateLimitKey = 'post-submission:' . auth()->id();
        if (!$rateLimiter->remaining($rateLimitKey, 10)) {
            $this->addError('rateLimit', 'You have reached the post submission limit. Please try again later.');
            return;
        }

        $this->validate([
            'title' => 'required|max:100',
            'content' => 'required|max:500',
        ]);

        /** @var User $authUser */
        $authUser = auth()->user();

        $authUser->posts()->create([
            'title' => $this->title,
            'content' => $this->content,
        ]);

        // Increment the rate limiting counter
        $rateLimiter->hit($rateLimitKey, 3600); // 3600 seconds = 1 hour

        return $this->redirect('/home');
    }

    public function render()
    {
        return view('livewire.create-post');
    }
}
