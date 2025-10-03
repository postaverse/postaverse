<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Group extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'avatar',
        'cover_photo',
        'privacy',
        'created_by',
        'members_count',
        'posts_count',
        'is_verified',
        'category',
        'rules',
    ];

    protected $casts = [
        'rules' => 'array',
        'is_verified' => 'boolean',
        'members_count' => 'integer',
        'posts_count' => 'integer',
    ];

    // Relationships
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function members(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'group_members')
            ->withPivot(['role', 'joined_at', 'invited_by'])
            ->withTimestamps();
    }

    public function posts(): HasMany
    {
        return $this->hasMany(\App\Models\Post::class, 'group_id')->latest();
    }

    public function joinRequests(): HasMany
    {
        return $this->hasMany(GroupJoinRequest::class);
    }

    // Helper methods
    public function addMember(User $user, string $role = 'member', ?User $invitedBy = null): void
    {
        if (!$this->isMember($user)) {
            $this->members()->attach($user->id, [
                'role' => $role,
                'joined_at' => now(),
                'invited_by' => $invitedBy?->id,
            ]);
            $this->increment('members_count');
        }
    }

    public function removeMember(User $user): void
    {
        if ($this->isMember($user)) {
            $this->members()->detach($user->id);
            $this->decrement('members_count');
        }
    }

    public function isMember(User $user): bool
    {
        return $this->members()->where('user_id', $user->id)->exists();
    }

    public function isAdmin(User $user): bool
    {
        return $this->members()
            ->where('user_id', $user->id)
            ->wherePivotIn('role', ['admin', 'owner'])
            ->exists();
    }

    public function isModerator(User $user): bool
    {
        return $this->members()
            ->where('user_id', $user->id)
            ->wherePivotIn('role', ['moderator', 'admin', 'owner'])
            ->exists();
    }

    public function isOwner(User $user): bool
    {
        return $this->created_by === $user->id;
    }

    // Scopes
    public function scopePublic($query)
    {
        return $query->where('privacy', 'public');
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    public function scopeVerified($query)
    {
        return $query->where('is_verified', true);
    }

    // Use slug for route model binding
    public function getRouteKeyName()
    {
        return 'slug';
    }
}
