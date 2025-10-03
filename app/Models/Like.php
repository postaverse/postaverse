<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Like extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'likeable_id',
        'likeable_type',
    ];

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function likeable(): MorphTo
    {
        return $this->morphTo();
    }

    // Helper methods
    public static function toggle(\App\Models\User $user, Model $likeable): bool
    {
        $like = static::where([
            'user_id' => $user->id,
            'likeable_id' => $likeable->id,
            'likeable_type' => get_class($likeable),
        ])->first();

        if ($like) {
            $like->delete();
            if (method_exists($likeable, 'decrementLikes')) {
                $likeable->decrementLikes();
            }
            return false; // Unlike
        } else {
            static::create([
                'user_id' => $user->id,
                'likeable_id' => $likeable->id,
                'likeable_type' => get_class($likeable),
            ]);
            if (method_exists($likeable, 'incrementLikes')) {
                $likeable->incrementLikes();
            }
            return true; // Like
        }
    }
}
