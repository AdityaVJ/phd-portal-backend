<?php

namespace App\Http\Controllers;

use App\Traits\HandlesAuth;
use App\Models\Admin;
use App\Models\RefreshToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminAuthController extends Controller
{
    use HandlesAuth;

    protected string $guard = 'admin';

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $admin = Admin::where('email', $request->email)->first();

        if (!$admin || !Hash::check($request->password, $admin->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        // Get the token array from issueTokens
        $tokenResponse = $this->issueTokens($admin, $request, $this->guard);
        $tokens = $tokenResponse->getData(true);

        // Add the user object at the top level
        $tokens['user'] = [
            'id'    => $admin->id,
            'name'  => $admin->name,
            'email' => $admin->email,
        ];

        return response()->json($tokens);
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

        $user = Admin::find($token->user_id);

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
}
