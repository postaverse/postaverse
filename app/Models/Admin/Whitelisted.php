<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Whitelisted extends Model
{
    use HasFactory;

    protected $table = 'whitelisted';

    protected $fillable = [
        'email'
    ];
}
