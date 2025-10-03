<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use App\Models\User;
use App\Models\Post as PostModel;

class Report extends Model
{
    use HasFactory;

    protected $fillable = [
        'reporter_id',
        'reportable_id',
        'reportable_type',
        'reason',
        'description',
        'status',
        'reviewed_by',
        'reviewed_at',
        'admin_notes',
        'action_taken',
    ];

    protected $casts = [
        'reviewed_at' => 'datetime',
    ];

    // Relationships
    public function reporter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reporter_id');
    }

    // Backwards-compatible alias used in some views
    public function user(): BelongsTo
    {
        return $this->reporter();
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function reportable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * If the report targets a user, return that user relation.
     * Always returns a BelongsTo relation so it can be eager-loaded.
     */
    public function reportedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reportable_id');
    }

    /**
     * Convenience relation when the report targets a Post.
     * Always returns a BelongsTo relation so it can be eager-loaded.
     */
    public function post(): BelongsTo
    {
        return $this->belongsTo(PostModel::class, 'reportable_id');
    }

    // Helper methods
    public function markAsReviewed(User $admin, string $actionTaken, ?string $notes = null): void
    {
        $this->update([
            'status' => 'reviewed',
            'reviewed_by' => $admin->id,
            'reviewed_at' => now(),
            'action_taken' => $actionTaken,
            'admin_notes' => $notes,
        ]);
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isReviewed(): bool
    {
        return $this->status === 'reviewed';
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeReviewed($query)
    {
        return $query->where('status', 'reviewed');
    }

    public function scopeByReason($query, $reason)
    {
        return $query->where('reason', $reason);
    }
}
