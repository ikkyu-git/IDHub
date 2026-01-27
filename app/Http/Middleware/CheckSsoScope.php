<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\SsoAccessToken;
use Carbon\Carbon;

class CheckSsoScope
{
    public function handle(Request $request, Closure $next, $scope)
    {
        $tokenString = $request->bearerToken();

        if (!$tokenString) {
            return response()->json(['error' => 'unauthorized'], 401);
        }

        $token = SsoAccessToken::where('id', $tokenString)
            ->where('revoked', false)
            ->where('expires_at', '>', Carbon::now())
            ->first();

        if (!$token) {
            return response()->json(['error' => 'invalid_token'], 401);
        }

        // Check scope
        $tokenScopes = explode(' ', $token->scopes ?? '');
        // Allow if token has the requested scope OR has '*' (all scopes)
        if (!in_array($scope, $tokenScopes) && !in_array('*', $tokenScopes)) {
             return response()->json(['error' => 'insufficient_scope', 'required_scope' => $scope], 403);
        }

        // Attach user ID to request for convenience
        $request->merge(['sso_user_id' => $token->user_id]);

        return $next($request);
    }
}
