<?php

namespace App\Models\Blog;

use App\Models\User\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BlogComment extends Model
{
    use HasFactory;

    protected $fillable = ['blog_id', 'user_id', 'content', 'has_profanity', 'parent_id'];

    public function blog(): BelongsTo
    {
        return $this->belongsTo(Blog::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    
    // Parent comment relationship
    public function parent(): BelongsTo
    {
        return $this->belongsTo(BlogComment::class, 'parent_id');
    }

    // Child comments (replies) relationship
    public function replies(): HasMany
    {
        return $this->hasMany(BlogComment::class, 'parent_id')->orderBy('created_at', 'asc');
    }

    // Check if this comment is a reply to another comment
    public function isReply(): bool
    {
        return $this->parent_id !== null;
    }

    // Get the root level comments (not replies)
    public function scopeRootComments($query)
    {
        return $query->whereNull('parent_id');
    }
}
