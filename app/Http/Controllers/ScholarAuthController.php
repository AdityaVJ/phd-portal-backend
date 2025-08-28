<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Traits\HandlesAuth;
use App\Models\RefreshToken;
use App\Models\Scholar;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ScholarAuthController extends Controller
{
    use HandlesAuth;

    protected string $guard = 'scholar';

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'remember_me' => 'nullable'
        ]);

        $scholar = Scholar::where('email', $request->email)->first();

        if (! $scholar || ! Hash::check($request->password, $scholar->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        return $this->issueTokens($scholar, $request, $this->guard);
    }

    public function refresh(Request $request)
    {
        $request->validate(['refresh_token' => 'required']);

        $hashed = hash('sha256', $request->refresh_token);
        $token = RefreshToken::where('token', $hashed)
            ->where('guard', $this->guard)
            ->first();

        if (! $token || $token->expires_at->isPast()) {
            return response()->json(['message' => 'Invalid or expired refresh token'], 401);
        }

        $token->update(['last_used_at' => now()]);

        $user = Scholar::find($token->user_id);

        return $this->issueTokens($user, $request, $this->guard);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        if ($request->refresh_token) {
            RefreshToken::where('token', hash('sha256', $request->refresh_token))
                ->where('guard', $this->guard)
                ->delete();
        }

        return response()->json(['message' => 'Logged out']);
    }

    public function logoutAll(Request $request)
    {
        $user = $request->user();
        $user->tokens()->delete();
        RefreshToken::where('user_id', $user->id)
            ->where('guard', $this->guard)
            ->delete();

        return response()->json(['message' => 'Logged out from all devices']);
    }

    public function sessions(Request $request)
    {
        $sessions = RefreshToken::where('user_id', $request->user()->id)
            ->where('guard', $this->guard)
            ->get(['id', 'device_name', 'ip_address', 'user_agent', 'last_used_at', 'expires_at']);

        return response()->json($sessions);
    }

    public function revokeSession(Request $request, $id)
    {
        $session = RefreshToken::where('id', $id)
            ->where('user_id', $request->user()->id)
            ->where('guard', $this->guard)
            ->firstOrFail();

        $session->delete();

        return response()->json(['message' => 'Session revoked']);
    }

    public function forgot(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $supervisor = Scholar::where('email', $request->email)->first();
        if (!$supervisor) {
            return response()->json(['message' => 'Email not found'], 404);
        }

        // Create token
        $token = Str::random(60);

        DB::table('scholar_password_reset_tokens')->updateOrInsert(
            ['email' => $request->email],
            [
                'email' => $request->email,
                'token' => Hash::make($token),
                'created_at' => Carbon::now(),
            ]
        );

        // TODO: send $token via email/notification
        return response()->json(['message' => 'Reset link sent', 'token' => $token]);
    }

    public function reset(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'token' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $record = DB::table('scholar_password_reset_tokens')
            ->where('email', $request->email)
            ->first();

        if (!$record || !Hash::check($request->token, $record->token)) {
            return response()->json(['message' => 'Invalid token'], 400);
        }

        // Expiry check (optional: e.g. 60 minutes)
        if (Carbon::parse($record->created_at)->addMinutes(intval(config('auth.reset_token_ttl', 60)))->isPast()) {
            return response()->json(['message' => 'Token expired'], 400);
        }

        // Reset password
        $supervisor = Scholar::where('email', $request->email)->first();
        if (!$supervisor) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $supervisor->password = Hash::make($request->password);
        $supervisor->save();

        // Delete reset record
        DB::table('scholar_password_reset_tokens')->where('email', $request->email)->delete();

        return response()->json(['message' => 'Password reset successful']);
    }
}
