<?php

namespace App\Models;

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

    public function likes()
    {
        return $this->hasMany(Like::class);
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

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}
