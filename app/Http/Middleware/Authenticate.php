<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Closure;

class Authenticate extends Middleware
{
    /**
     * Handle unauthenticated requests.
     */
    protected function redirectTo($request): ?string
    {
        if (!$request->expectsJson()) {
            // Return a JSON response instead of redirecting to 'login'
            abort(response()->json([
                'message' => 'Unauthenticated.'
            ], 401));
        }

        return null;
    }

    /**
     * Optional: customize unauthenticated guard handling
     */
    protected function unauthenticated($request, array $guards)
    {
        abort(response()->json([
            'message' => 'Unauthenticated.',
            'guards' => $guards, // useful for debugging which guard failed
        ], 401));
    }
}
