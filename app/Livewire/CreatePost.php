<?php

namespace App\Livewire;

use App\Models\User;
use Illuminate\Cache\RateLimiter;
use Livewire\Component;
use App\Models\Notification;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\WithFileUploads;
use App\Livewire\Profanity;

class CreatePost extends Component
{
    use WithFileUploads;

    public string $title = '';
    public string $content = '';
    public $user;
    public $post;
    public array $photos = [];

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
        $this->user = auth()->user();

        // Check for profanity in the title/content
        $profanityChecker = new Profanity();
        $profane = 0;

        // If the title or content contains profanity, set the `has_profanity` column to true
        if ($profanityChecker->hasProfanity($this->title) || $profanityChecker->hasProfanity($this->content)) {
            $profane = 1;
        }

        // Check if the user has exceeded their rate limit for post submissions
        $rateLimiter = app(RateLimiter::class);

        // Check if the user has exceeded their rate limit for post submissions
        $rateLimitKey = 'post-submission:' . auth()->id();
        if (!$rateLimiter->remaining($rateLimitKey, 10)) {
            $this->addError('rateLimit', 'You have reached the post submission limit. Please try again later.');
            return;
        }

        $this->validate([
            'title' => 'required|max:128',
            'content' => 'required|max:2048',
            'photos.*' => 'image|max:5000', // 5MB Max
        ]);

        /** @var User $authUser */
        $authUser = auth()->user();

        $post = $authUser->posts()->create([
            'title' => $this->title,
            'content' => $this->content,
            'has_profanity' => $profane,
        ]);

        /** @var TemporaryUploadedFile $photo */
        foreach ($this->photos as $photo) {
            $post->images()->create([
                'path' => $photo->store('photos', 's3'),
            ]);
        }

        // Increment the rate limiting counter
        $rateLimiter->hit($rateLimitKey, 3600); // 3600 seconds = 1 hour

        $mentionedUsers = $this->getMentionedUsers($this->content);

        foreach ($mentionedUsers as $mentionedUser) {
            Notification::create([
                'user_id' => $mentionedUser->id,
                'message' => $this->user->name . ' mentioned you in a post',
                'link' => route('post', ['postId' => $authUser->posts()->latest()->first()->id]),
            ]);
        }

        return $this->redirect('/home');
    }

    public function render()
    {
        return view('livewire.create-post');
    }
}
