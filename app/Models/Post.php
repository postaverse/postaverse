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
        // If the option is to show all content, no need to check for profanity
        if ($handlingOption === 'show') {
            return false;
        }
    
        // Path to the text file containing profane words and phrases
        $filePath = base_path('resources/blocked.txt'); // Adjust the path as necessary
    
        // Read the file lines into an array, trimming whitespace and skipping empty lines
        $profaneWordsPhrases = array_filter(array_map('trim', file($filePath)));
    
        // Convert the content to lowercase for case-insensitive comparison
        $content = strtolower($this->content);
    
        foreach ($profaneWordsPhrases as $wordPhrase) {
            // Prepare the word or phrase for a whole word match in a case-insensitive manner
            $pattern = '/\b' . preg_quote($wordPhrase, '/') . '\b/i';
            if (preg_match($pattern, $content)) {
                return true; // Found a profane word or phrase as a whole word in the content
            }
        }
    
        // No profanity found, or the handling option does not require checking
        return false;
    }
}
