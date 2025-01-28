<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class TokenSession
{
    public function handle(Request $request, Closure $next)
    {
        // Retrieve the authenticated user's token
        $token = $request->user()?->currentAccessToken();

        // Check if the token exists and has an expiration time
        if (!$token || !$token->expires_at) {
            return response()->json([
                'status' => false,
                'message' => 'Session expired. Please log in again.',
            ], 419); // HTTP 419: Session Expired
        }

        // Check if the token is expired
        if (now()->greaterThan($token->expires_at)) {
            // Revoke the expired token
            $token->delete();

            return response()->json([
                'status' => false,
                'message' => 'Session expired. Please log in again.',
            ], 419); // HTTP 419: Session Expired
        }

        // If token is valid, proceed with the request
        return $next($request);
    }
}
