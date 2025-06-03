<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User\Banned;

class BannedIp extends Model
{
    protected $fillable = [
        'banned_id',
        'ip_address',
    ];

    public function banned(): BelongsTo
    {
        return $this->belongsTo(Banned::class);
    }
}
