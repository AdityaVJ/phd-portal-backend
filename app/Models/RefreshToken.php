<?php

namespace App\Models;

use hisorange\BrowserDetect\Parser as Browser;
use Illuminate\Database\Eloquent\Model;

class RefreshToken extends Model
{
    protected $fillable = [
        'user_id',
        'token',
        'expires_at',
        'last_used_at',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'last_used_at' => 'datetime',
    ];

    protected $appends = ['device_name'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getDeviceNameAttribute(): ?string
    {
        if (! $this->user_agent) {
            return null;
        }

        $result = Browser::parse($this->user_agent);

        $browser = $result->browserName() ?? 'Unknown Browser';
        $os = $result->platformName() ?? 'Unknown OS';
        $device = $result->deviceFamily() ?: '';

        return trim("{$browser} on {$os} {$device}");
    }
}
