<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GroupPost extends Model
{
    use HasFactory;

    protected $fillable = [
        'group_id',
        'user_id',
        'title',
        'content',
        'image_path',
        'link_url',
        'link_title',
        'link_description',
        'link_image',
        'type',
        'is_pinned',
        'is_locked',
        'upvotes',
        'downvotes',
        'comments_count',
    ];

    protected $casts = [
        'is_pinned' => 'boolean',
        'is_locked' => 'boolean',
        'upvotes' => 'integer',
        'downvotes' => 'integer',
        'comments_count' => 'integer',
    ];

    // Relationships
    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function votes(): HasMany
    {
        return $this->hasMany(GroupPostVote::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(GroupPostComment::class);
    }

    // Helper methods
    public function getScoreAttribute(): int
    {
        // Prefer canonical likes_count if present (after migration). Fall back to legacy upvotes/downvotes.
        if (property_exists($this, 'likes_count') && isset($this->likes_count)) {
            return (int) $this->likes_count;
        }

        $up = property_exists($this, 'upvotes') ? ($this->upvotes ?? 0) : 0;
        $down = property_exists($this, 'downvotes') ? ($this->downvotes ?? 0) : 0;
        return $up - $down;
    }

    public function getUserVoteAttribute(): ?string
    {
        if (!auth()->check()) {
            return null;
        }

        $vote = $this->votes()->where('user_id', auth()->id())->first();
        return $vote?->vote;
    }

    public function hasUserUpvoted(): bool
    {
        return $this->getUserVoteAttribute() === 'up';
    }

    public function hasUserDownvoted(): bool
    {
        return $this->getUserVoteAttribute() === 'down';
    }
}
