<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlockedUser extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'blocked_users'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}