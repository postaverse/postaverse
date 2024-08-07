<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Badge extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'icon'];

    /**
     * The users that belong to the badge.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    /**
     * Check if the badge is awarded based on a specific condition.
     *
     * @param User $user
     * @return bool
     */
    public function isAwarded(User $user): bool
    {
        // Implement your logic to check if the badge is awarded to the user
        // For example, check if the user has achieved a certain milestone
        return true; // Placeholder return value
    }
}