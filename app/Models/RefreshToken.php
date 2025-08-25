<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class RefreshToken extends Model
{
    protected $fillable = [
        'user_id',
        'guard',        // NEW: to distinguish admin/supervisor/scholar
        'token',
        'ip_address',
        'user_agent',
        'device_name',
        'last_used_at',
        'expires_at',
    ];

    protected $casts = [
        'last_used_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    public function user(): MorphTo
    {
        return $this->morphTo(__FUNCTION__, 'guard', 'user_id');
    }
}
