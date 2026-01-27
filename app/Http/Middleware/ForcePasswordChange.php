<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use App\Models\Setting;
use Carbon\Carbon;

class ForcePasswordChange
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if ($user) {
            // 1. Check Force Change Flag
            if ($user->must_change_password) {
                if (!$request->routeIs('password.change.*') && !$request->routeIs('logout')) {
                    // Store the intended URL before redirecting to password change
                    session(['url.password_change_intended' => $request->fullUrl()]);
                    return redirect()->route('password.change.form')->with('warning', 'กรุณาเปลี่ยนรหัสผ่านเพื่อความปลอดภัย');
                }
            }

            // 2. Check Password Expiry
            $expiryDays = Setting::where('key', 'password_expiry_days')->value('value');
            if ($expiryDays && is_numeric($expiryDays) && $expiryDays > 0) {
                $lastChange = $user->password_changed_at ?? $user->created_at;
                if (Carbon::parse($lastChange)->addDays((int)$expiryDays)->isPast()) {
                    if (!$request->routeIs('password.change.*') && !$request->routeIs('logout')) {
                        // Store the intended URL before redirecting to password change
                        session(['url.password_change_intended' => $request->fullUrl()]);
                        return redirect()->route('password.change.form')->with('warning', 'รหัสผ่านของคุณหมดอายุ กรุณาเปลี่ยนรหัสผ่านใหม่');
                    }
                }
            }
        }

        return $next($request);
    }
}
