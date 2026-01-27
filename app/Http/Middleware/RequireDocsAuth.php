<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RequireDocsAuth
{
    public function handle(Request $request, Closure $next)
    {
        // Require authenticated user with `manage_clients` permission
        $user = $request->user();
        if (!$user) {
            return redirect()->route('login.page');
        }

        if (!method_exists($user, 'hasPermission') || !$user->hasPermission('manage_clients')) {
            abort(403, 'Access Denied: คุณไม่มีสิทธิ์เข้าถึงเอกสารนี้');
        }

        return $next($request);
    }
}
