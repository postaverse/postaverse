<?php

namespace App\Models\Blog;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BlogImage extends Model
{
    use HasFactory;

    protected $fillable = ['blog_id', 'path'];

    public function blog(): BelongsTo
    {
        return $this->belongsTo(Blog::class);
    }
}
