<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User\User;

class AdminLogs extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'admin_id',
        'action',
    ];

    /**
     * Get the admin user that performed the action.
     */
    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }
}
