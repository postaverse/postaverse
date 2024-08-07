<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TextTheme extends Model
{
    use HasFactory;

    protected $fillable = ['theme_name', 'meteorPrice', 'equipped'];

    public function users()
    {
        return $this->belongsToMany(User::class, 'text_theme_user');
    }
}