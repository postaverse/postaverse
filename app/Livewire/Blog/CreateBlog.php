<?php

namespace App\Livewire\Blog;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Cache\RateLimiter;
use App\Models\User\User;
use App\Models\Interaction\Notification;
use App\Services\Profanity;

class CreateBlog extends Component
{
    use WithFileUploads;

    public string $title = '';
    public string $content = '';
    public $user;
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

        // Check if the user has exceeded their rate limit for blog submissions
        $rateLimitKey = 'blog-submission:' . auth()->id();
        if (!$rateLimiter->remaining($rateLimitKey, 10)) {
            $this->addError('rateLimit', 'You have reached the blog submission limit. Please try again later.');
            return;
        }

        $this->validate([
            'title' => 'required|min:6|max:256',
            'content' => 'required|min:6',
            'photos.*' => 'image|max:5120', // 5MB max per image
        ]);

        // Hit the rate limiter
        $rateLimiter->hit($rateLimitKey, 60 * 5); // 5 minutes

        $blog = auth()->user()->blogs()->create([
            'title' => $this->title,
            'user_id' => auth()->id(),
            'content' => $this->content,
            'has_profanity' => $profane,
        ]);

        // Save any uploaded photos
        foreach ($this->photos as $photo) {
            $path = $photo->store('public/blog-images');
            $blog->images()->create([
                'path' => $path,
            ]);
        }

        // Notify people mentioned in the blog
        $mentionedUsers = $this->getMentionedUsers($this->content);

        foreach ($mentionedUsers as $mentionedUser) {
            Notification::create([
                'user_id' => $mentionedUser->id,
                'message' => $this->user->name . ' mentioned you in a blog',
                'link' => route('blog', ['blogId' => $blog->id]),
            ]);
        }

        $this->title = '';
        $this->content = '';
        $this->photos = [];

        session()->flash('message', 'Blog created successfully.');
    }

    public function render()
    {
        return view('livewire.Blog.create-blog');
    }
}
