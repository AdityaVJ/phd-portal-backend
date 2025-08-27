<?php

namespace App\Traits;

use App\Models\RefreshToken;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

trait HandlesAuth
{
    protected function createAccessToken($user, string $guard)
    {
        // Use guard as ability (e.g. "admin", "scholar", etc.)
        $token = $user->createToken(
            $guard . '-api-token',
            [$guard] // token abilities
        );

        $ttl = config('sanctum.expiration', 60); // default 60 mins
        return [
            'token' => $token->plainTextToken,
            'expires_at' => now()->addMinutes(intval($ttl)),
        ];
    }

    protected function createRefreshToken($user, Request $request, string $guard)
    {
        $refreshToken = Str::random(64);

        $refreshTokenExpiresAt = now()->addMinutes(
            intval(config('auth.refresh_token_ttl', 30 * 24 * 60)) // configurable in config/auth.php
        );

        RefreshToken::create([
            'user_id' => $user->id,
            'guard' => $guard,
            'token' => hash('sha256', $refreshToken),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'device_name' => $this->getDeviceName($request->userAgent()),
            'last_used_at' => now(),
            'expires_at' => $refreshTokenExpiresAt,
        ]);

        return [
            'token' => $refreshToken,
            'expires_at' => $refreshTokenExpiresAt,
        ];
    }

    protected function getDeviceName($userAgent)
    {
        if (!$userAgent) {
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
        $access = $this->createAccessToken($user, $guard);
        $refresh = $this->createRefreshToken($user, $request, $guard);

        return response()->json([
            'access_token' => $access['token'],
            'refresh_token' => $refresh['token'],
            'token_type' => 'Bearer',
            'access_token_expires_at' => $access['expires_at']->toIso8601String(),
            'refresh_token_expires_at' => $refresh['expires_at']->toIso8601String(),
        ]);
    }
}
