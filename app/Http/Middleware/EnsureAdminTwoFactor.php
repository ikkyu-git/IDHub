<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Setting;

class EnsureAdminTwoFactor
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        if (!$user) {
            return $next($request);
        }

        // Allow access to 2FA routes to avoid redirect loops
        if ($request->is('user/two-factor*') || $request->is('two-factor-challenge') || $request->is('two-factor-challenge/*')) {
            return $next($request);
        }

        $force = Setting::where('key', 'force_2fa')->value('value') ?? '0';

        if ($force === '1' && ($user->hasRole('admin') || $user->hasRole('super-admin'))) {
            if (empty($user->two_factor_confirmed_at)) {
                return redirect()->route('user.2fa.show')->with('alert', 'นโยบายระบบกำหนดให้ผู้ดูแลต้องเปิดใช้งาน Two-Factor Authentication ก่อนใช้งาน');
            }
        }

        return $next($request);
    }
}
