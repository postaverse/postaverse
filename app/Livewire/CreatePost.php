<?php

namespace App\Livewire;

use App\Models\Hashtag;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;

class CreatePost extends Component
{
    use WithFileUploads;

    public $content = '';
    public $media = [];
    public $visibility = 'public';
    public $location = null;
    public $showLocationInput = false;
    public $isLoading = false;
    public $groupId = null; // optional: when set, create a GroupPost instead
    public $availableGroups = []; // groups the user can post to (joined)

    protected $rules = [
        'content' => 'required|string|max:4096',
        'visibility' => 'in:public,friends,private',
        'location' => 'nullable|string|max:255',
        'media.*' => 'nullable|file|max:10240', // 10MB max per file
    ];

    public function createPost()
    {
        if (!Auth::check()) {
            return;
        }

        $this->validate();
        $this->isLoading = true;

        try {
            // Extract hashtags from content
            $hashtags = $this->extractHashtags($this->content);
            
            // Create a regular Post; if groupId provided, save it with group_id so groups organize posts
            if ($this->groupId) {
                // ensure user is member of selected group
                $group = \App\Models\Group::where('id', $this->groupId)->first();
                if (!$group || !$group->isMember(Auth::user())) {
                    session()->flash('error', 'You must be a member of the selected group to post there.');
                    $this->isLoading = false;
                    return;
                }
            }

            // Create the post (group_id may be null)
            $post = Post::create([
                'user_id' => Auth::id(),
                'content' => $this->content,
                'visibility' => $this->visibility,
                'location' => $this->location ?: null,
                'media_type' => !empty($this->media) ? 'image' : 'none',
                'hashtags' => $hashtags,
                'group_id' => $this->groupId,
            ]);

            // If posting to a group increment its posts_count
            if (!empty($this->groupId)) {
                \App\Models\Group::where('id', $this->groupId)->increment('posts_count');
            }

            // Process hashtags
            if (!empty($hashtags)) {
                $this->processHashtags($post, $hashtags);
            }

            // Handle media uploads if any
            if (!empty($this->media)) {
                $mediaUrls = [];
                foreach ($this->media as $file) {
                    $path = $file->store('posts', 'public');
                    $mediaUrls[] = $path;
                }
                $post->update(['media_urls' => $mediaUrls]);
            }

            // Reset form
            $this->reset(['content', 'media', 'location', 'showLocationInput', 'groupId']);
            $this->visibility = 'public';

            // Emit event to refresh feed
            $this->dispatch('postCreated');
            
            session()->flash('message', 'Post created successfully!');

        } catch (\Exception $e) {
            session()->flash('error', 'Failed to create post. Please try again.');
        } finally {
            $this->isLoading = false;
        }
    }

    public function mount($groupId = null)
    {
        $this->groupId = $groupId;
        if (Auth::check()) {
            // qualify columns with table name to avoid ambiguous column errors when the pivot
            // table also defines an `id` column (SQLite reports "ambiguous column name: id").
            $this->availableGroups = Auth::user()
                ->groups()
                ->select(['groups.id', 'groups.name', 'groups.slug'])
                ->get();
        }
    }

    public function toggleLocationInput()
    {
        $this->showLocationInput = !$this->showLocationInput;
        if (!$this->showLocationInput) {
            $this->location = null;
        }
    }

    private function extractHashtags(string $content): array
    {
        preg_match_all('/#([a-zA-Z0-9_]+)/', $content, $matches);
        return array_unique(array_map('strtolower', $matches[1]));
    }

    private function processHashtags(Post $post, array $hashtags): void
    {
        $hashtagIds = [];
        
        foreach ($hashtags as $tagName) {
            $hashtag = Hashtag::findOrCreateByName($tagName);
            $hashtag->incrementUsage();
            $hashtagIds[] = $hashtag->id;
        }
        
        $post->hashtags()->sync($hashtagIds);
    }

    public function removeMedia($index)
    {
        unset($this->media[$index]);
        $this->media = array_values($this->media);
    }

    public function render()
    {
        return view('livewire.create-post');
    }
}
