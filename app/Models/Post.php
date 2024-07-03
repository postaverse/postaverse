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

    public function hasProfanity($handlingOption = 'show')
    {
        // If the option is to show all content, no need to check for profanity
        if ($handlingOption === 'show') {
            return false;
        }

        // Path to the text file containing profane words and phrases
        $filePath = base_path('resources/blocked.txt'); // Adjust the path as necessary

        // Read the file lines into an array, trimming whitespace and skipping empty lines
        $profaneWordsPhrases = array_filter(array_map('trim', file($filePath)));

        // Check for profane words
        $contentWords = preg_split('/\s+/', strtolower($this->content)); // Split content into words based on whitespace and convert to lowercase
        foreach ($profaneWordsPhrases as $wordPhrase) {
            $wordPhrase = strtolower($wordPhrase); // Convert to lowercase for case-insensitive comparison
            if (strpos($this->content, $wordPhrase) !== false) {
                return true; // Found a profane word or phrase in the content
            }
        }

        // No profanity found, or the handling option does not require checking
        return false;
    }
}
