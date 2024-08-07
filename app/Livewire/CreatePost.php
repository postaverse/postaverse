<?php

namespace App\Livewire;

use App\Models\User;
use Illuminate\Cache\RateLimiter;
use Livewire\Component;
use App\Models\Notification;

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

    private function getMentionedUsers($text)
    {
        $mention = preg_match_all('/@(\w+)/', $text, $matches);
        // Handle or ID
        $users = User::whereIn('handle', $matches[1])->orWhereIn('id', $matches[1])->get();
        return $users;
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

        $mentionedUsers = $this->getMentionedUsers($this->content);

        foreach ($mentionedUsers as $mentionedUser) {
            Notification::create([
                'user_id' => $mentionedUser->id,
                'message' => $this->user->name . ' mentioned you in a comment',
                'link' => route('post', ['postId' => $this->post->id]),
            ]);
        }

        return $this->redirect('/home');
    }

    public function render()
    {
        return view('livewire.create-post');
    }
}
