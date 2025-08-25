<?php

namespace App\Traits;

use App\Models\RefreshToken;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

trait HandlesAuth
{
    protected function createAccessToken($user)
    {
        return $user->createToken('api-token')->plainTextToken;
    }

    protected function createRefreshToken($user, Request $request, string $guard)
    {
        $refreshToken = Str::random(64);

        RefreshToken::create([
            'user_id' => $user->id,
            'guard' => $guard,
            'token' => hash('sha256', $refreshToken),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'device_name' => $this->getDeviceName($request->userAgent()),
            'last_used_at' => now(),
            'expires_at' => now()->addDays(30),
        ]);

        return $refreshToken;
    }

    protected function getDeviceName($userAgent)
    {
        if (! $userAgent) {
            return 'Unknown Device';
        }

        $ua = strtolower($userAgent);

        return match (true) {
            str_contains($ua, 'iphone') => 'iPhone',
            str_contains($ua, 'ipad') => 'iPad',
            str_contains($ua, 'android') => 'Android Device',
            str_contains($ua, 'windows') => 'Windows PC',
            str_contains($ua, 'macintosh') => 'Mac',
            default => 'Other Device',
        };
    }

    protected function issueTokens($user, Request $request, string $guard)
    {
        $accessToken = $this->createAccessToken($user);
        $refreshToken = $this->createRefreshToken($user, $request, $guard);

        return response()->json([
            'access_token' => $accessToken,
            'refresh_token' => $refreshToken,
            'token_type' => 'Bearer',
            'expires_in' => 3600, // 1 hour
        ]);
    }
}
