<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Post extends Model
{
    protected $fillable = ['title', 'content'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function hasProfanity($handlingOption = 'hide_clickable')
    {
        if ($handlingOption === 'show') {
            return false;
        }

        $filePath = base_path('resources/blocked.txt');

        $profaneWordsPhrases = array_filter(array_map('trim', file($filePath)));

        $content = strtolower($this->content);

        foreach ($profaneWordsPhrases as $wordPhrase) {
            $pattern = '/\b' . preg_quote($wordPhrase, '/') . '\b/i';
            if (preg_match($pattern, $content)) {
                return true;
            }
        }
        return false;
    }
}
