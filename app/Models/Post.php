<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'group_id',
        'content',
        'media_urls',
        'media_type',
        'visibility',
        'location',
        'tagged_users',
        'hashtags',
        'is_pinned',
        'comments_enabled',
        'likes_count',
        'comments_count',
        'shares_count',
        'views_count',
        'shared_post_id',
    ];

    protected $casts = [
        'media_urls' => 'array',
        'tagged_users' => 'array',
        'hashtags' => 'array',
        'group_id' => 'integer',
        'is_pinned' => 'boolean',
        'comments_enabled' => 'boolean',
        'likes_count' => 'integer',
        'comments_count' => 'integer',
        'shares_count' => 'integer',
        'views_count' => 'integer',
    ];

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class)->whereNull('parent_id')->latest();
    }

    public function likes(): MorphMany
    {
        return $this->morphMany(Like::class, 'likeable');
    }

    public function reports(): MorphMany
    {
        return $this->morphMany(Report::class, 'reportable');
    }

    public function hashtags(): BelongsToMany
    {
        return $this->belongsToMany(Hashtag::class, 'post_hashtags')->withTimestamps();
    }

    public function savedByUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'post_user')
            ->withTimestamps();
    }

    public function sharedPost(): BelongsTo
    {
        return $this->belongsTo(Post::class, 'shared_post_id');
    }

    public function shares(): HasMany
    {
        return $this->hasMany(Post::class, 'shared_post_id');
    }

    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class, 'group_id');
    }

    // Helper methods
    public function isLikedBy(User $user): bool
    {
        return $this->likes()->where('user_id', $user->id)->exists();
    }

    public function isSavedBy(User $user): bool
    {
        return $this->savedByUsers()->where('user_id', $user->id)->exists();
    }

    public function isSharedPost(): bool
    {
        return !is_null($this->shared_post_id);
    }

    public function getImageUrl($mediaPath): string
    {
        if (empty($mediaPath)) {
            return '';
        }
        
        // If the path already contains the full URL, return it as is
        if (str_starts_with($mediaPath, 'http')) {
            return $mediaPath;
        }
        
        // Otherwise, use Storage::url to get the full URL
        return \Storage::url($mediaPath);
    }

    public function getMediaUrls(): array
    {
        if (!$this->media_urls) {
            return [];
        }
        
        return array_map(function($url) {
            // If URL starts with http, return as is, otherwise use Storage::url
            if (str_starts_with($url, 'http')) {
                return $url;
            }
            return \Storage::url($url);
        }, $this->media_urls);
    }

    public function incrementLikes(): void
    {
        $this->increment('likes_count');
    }

    public function decrementLikes(): void
    {
        $this->decrement('likes_count');
    }

    public function incrementComments(): void
    {
        $this->increment('comments_count');
    }

    public function decrementComments(): void
    {
        $this->decrement('comments_count');
    }

    public function incrementShares(): void
    {
        $this->increment('shares_count');
    }

    public function incrementViews(): void
    {
        $this->increment('views_count');
    }

    // Scopes
    public function scopePublic($query)
    {
        return $query->where('visibility', 'public');
    }

    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopePinned($query)
    {
        return $query->where('is_pinned', true);
    }

    public function scopeWithMedia($query)
    {
        return $query->whereNotNull('media_urls');
    }
}
