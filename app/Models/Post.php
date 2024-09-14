<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Http;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Post extends Model
{
    protected $fillable = ['title', 'content'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function likes(): HasMany
    {
        return $this->hasMany(Like::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(PostImage::class);
    }

    public function hasProfanity($handlingOption = 'hide_clickable')
    {
        if ($handlingOption === 'show') {
            return false;
        }

        $textToCheck = $this->title . ' ' . $this->content;

        $response = Http::get('https://api.zanderlewis.dev/profane_check.php', [
            'text' => $textToCheck,
        ]);

        if ($response->successful()) {
            $result = $response->json();
            return $result['containsBadWords'];
        } else {
            return false;
        }
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class)->orderBy('created_at', 'desc');
    }
}
