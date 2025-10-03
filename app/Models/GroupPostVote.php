<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GroupPostVote extends Model
{
    use HasFactory;

    protected $fillable = [
        'group_post_id',
        'user_id',
        'vote',
    ];

    // Relationships
    public function groupPost(): BelongsTo
    {
        return $this->belongsTo(GroupPost::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
