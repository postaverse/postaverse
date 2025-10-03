<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Conversation extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'type',
        'created_by',
        'last_message_at',
    ];

    protected $casts = [
        'last_message_at' => 'datetime',
    ];

    // Relationships
    public function participants(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'conversation_participants')
            ->withPivot(['joined_at', 'left_at'])
            ->withTimestamps();
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class)->latest();
    }

    public function latestMessage(): HasMany
    {
        return $this->hasMany(Message::class)->latest()->limit(1);
    }

    // Helper methods
    public function addParticipant(User $user): void
    {
        if (!$this->participants()->where('user_id', $user->id)->exists()) {
            $this->participants()->attach($user->id, ['joined_at' => now()]);
        }
    }

    public function removeParticipant(User $user): void
    {
        $this->participants()->updateExistingPivot($user->id, ['left_at' => now()]);
    }

    public function isParticipant(User $user): bool
    {
        return $this->participants()
            ->where('user_id', $user->id)
            ->whereNull('conversation_participants.left_at')
            ->exists();
    }

    public function getOtherParticipant(User $currentUser): ?User
    {
        if ($this->type === 'direct') {
            return $this->participants()
                ->where('user_id', '!=', $currentUser->id)
                ->whereNull('conversation_participants.left_at')
                ->first();
        }
        return null;
    }
}
