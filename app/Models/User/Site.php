<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Site extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'domain', 'is_verified'];

    /**
     * Get the user that owns the site.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}