<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminLogs extends Model
{
    use HasFactory;

    protected $table = 'admin_logs';

    protected $fillable = [
        'admin_id',
        'action',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }
}
