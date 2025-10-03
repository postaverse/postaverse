<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Hashtag extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'posts_count',
        'trending_score',
        'is_trending',
    ];

    protected $casts = [
        'posts_count' => 'integer',
        'trending_score' => 'float',
        'is_trending' => 'boolean',
    ];

    // Relationships
    public function posts(): BelongsToMany
    {
        return $this->belongsToMany(Post::class, 'post_hashtags')->withTimestamps();
    }

    // Helper methods
    public function incrementUsage(): void
    {
        $this->increment('posts_count');
        $this->updateTrendingScore();
    }

    public function decrementUsage(): void
    {
        $this->decrement('posts_count');
        $this->updateTrendingScore();
    }

    private function updateTrendingScore(): void
    {
        // Simple trending algorithm based on recent usage
        $recentPosts = $this->posts()
            ->where('created_at', '>=', now()->subHours(24))
            ->count();
        
        $this->update([
            'trending_score' => $recentPosts * 0.8 + ($this->posts_count * 0.2),
            'is_trending' => $recentPosts >= 10, // Threshold for trending
        ]);
    }

    // Scopes
    public function scopeTrending($query)
    {
        return $query->where('is_trending', true)->orderByDesc('trending_score');
    }

    public function scopePopular($query)
    {
        return $query->orderByDesc('posts_count');
    }

    // Static methods
    public static function findOrCreateByName(string $name): self
    {
        $hashtag = static::where('name', $name)->first();
        
        if (!$hashtag) {
            $hashtag = static::create([
                'name' => $name,
                'posts_count' => 0,
                'trending_score' => 0,
                'is_trending' => false,
            ]);
        }
        
        return $hashtag;
    }
}
