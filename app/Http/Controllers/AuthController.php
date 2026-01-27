<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Role; // เพิ่ม
use App\Models\AuditLog; // เพิ่ม
use App\Models\SsoClient; // เพิ่ม
use App\Models\Setting; // เพิ่ม
use Illuminate\Support\Facades\Mail; // เพิ่ม
use App\Mail\NewDeviceLoginNotification; // เพิ่ม
use Laravel\Socialite\Facades\Socialite; // เพิ่ม Socialite Facade
use Illuminate\Auth\Events\PasswordReset; // เพิ่ม Event
use Illuminate\Support\Facades\URL;
use App\Mail\VerifyEmail;

class AuthController extends Controller
{
    public function showLoginForm(Request $request)
    {
        $client = null;
        
        // Check if there is a client_id in the query string
        if ($request->has('client_id')) {
            $client = SsoClient::where('client_id', $request->client_id)->first();
        } 
        // Or check if we are in an OIDC flow (url.intended)
        elseif (session()->has('url.intended')) {
            $url = session('url.intended');
            $query = parse_url($url, PHP_URL_QUERY);
            if ($query) {
                parse_str($query, $params);
                if (isset($params['client_id'])) {
                    $client = SsoClient::where('client_id', $params['client_id'])->first();
                }
            }
        }

        // Load Social Login Settings and registration flag
        $settings = Setting::whereIn('key', [
            'social_login_google_enable',
            'social_login_facebook_enable',
            'allow_registration'
        ])->pluck('value', 'key');

        return view('auth.login', compact('client', 'settings'));
    }

    public function showRegisterForm()
    {
        // Only show if registration is allowed
        $allow = Setting::where('key', 'allow_registration')->value('value') ?? '1';
        if ($allow !== '1') {
            abort(404);
        }

        return view('auth.register');
    }

    public function register(Request $request)
    {
        $allow = Setting::where('key', 'allow_registration')->value('value') ?? '1';
        if ($allow !== '1') {
            abort(403, 'Registration is disabled');
        }

        $data = $request->validate([
            'first_name' => ['required','string','max:255'],
            'last_name' => ['nullable','string','max:255'],
            'username' => ['nullable','string','max:50','unique:users,username'],
            'email' => ['required','email','max:255','unique:users,email'],
            'password' => ['required','string','min:8','confirmed'],
        ]);

        $name = trim($data['first_name'] . ' ' . ($data['last_name'] ?? ''));
        $user = User::create([
            'name' => $name,
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'] ?? null,
            'username' => isset($data['username']) && $data['username'] !== '' ? strtolower(trim($data['username'])) : strtolower(explode('@', $data['email'])[0]),
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'is_active' => 1,
        ]);

        // Send verification email
        try {
            $this->sendVerificationEmail($user);
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('Failed to send verification email: ' . $e->getMessage());
        }

        // Attach default 'user' role if present
        $role = Role::where('slug', 'user')->first();
        if ($role) {
            $user->roles()->attach($role);
        }

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('user.dashboard')->with('alert', 'ยินดีต้อนรับ! สร้างบัญชีเรียบร้อยแล้ว');
    }

    protected function sendVerificationEmail(User $user)
    {
        $url = URL::temporarySignedRoute('verification.verify', now()->addHours(24), ['id' => $user->id, 'hash' => sha1($user->email)]);
        Mail::to($user->email)->send(new VerifyEmail($user, $url));
    }

    public function verifyEmail(Request $request, $id, $hash)
    {
        $user = User::findOrFail($id);

        // Validate hash and signature
        if (!hash_equals((string) $hash, sha1($user->email))) {
            abort(403, 'Invalid verification link');
        }

        // Optionally check signature
        if (!$request->hasValidSignature()) {
            // allow if hash matches but signature expired? we'll require signature valid
            abort(403, 'Invalid or expired verification link');
        }

        $user->email_verified_at = now();
        $user->save();

        return redirect()->route('login.page')->with('alert', 'อีเมลของคุณได้รับการยืนยันแล้ว สามารถล็อกอินได้');
    }

    public function resendVerification(Request $request)
    {
        $user = $request->user();
        if (!$user) return back()->withErrors(['msg' => 'Unauthorized']);

        if ($user->email_verified_at) {
            return back()->with('alert', 'อีเมลของคุณยืนยันแล้ว');
        }

        try {
            $this->sendVerificationEmail($user);
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('Failed to resend verification email: ' . $e->getMessage());
            return back()->withErrors(['msg' => 'ไม่สามารถส่งอีเมลได้ในขณะนี้']);
        }

        return back()->with('alert', 'ส่งอีเมลยืนยันเรียบร้อยแล้ว โปรดตรวจสอบกล่องจดหมาย');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'login' => ['required'],
            'password' => ['required'],
        ]);

        // Normalize login input and build throttle key (IP + login)
        $loginInput = trim($request->input('login'));
        $loginLower = Str::lower($loginInput);
        $throttleKey = $loginLower . '|' . $request->ip();

        // ตรวจสอบว่าถูกบล็อกหรือไม่
        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            
            // Log การถูกบล็อก
            AuditLog::create([
                'user_id' => null,
                'action' => 'login_locked_out',
                'model_type' => 'Auth',
                'model_id' => $loginInput,
                'details' => [
                    'ip' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                    'reason' => 'Too many attempts',
                    'seconds_remaining' => $seconds
                ],
                'ip_address' => $request->ip(),
            ]);

            return back()->withErrors([
                'login' => 'มีการพยายามเข้าสู่ระบบมากเกินไป กรุณารอ ' . $seconds . ' วินาที',
            ])->onlyInput('login');
        }
        // ตรวจสอบรหัสผ่านก่อน (แต่ยังไม่ Login จริง)
        $loginVal = $credentials['login'];
        $user = null;

        // If looks like an email, search by email; otherwise search by username (normalized)
        if (filter_var($loginVal, FILTER_VALIDATE_EMAIL)) {
            $user = User::where('email', $loginVal)->first();
        } else {
            $user = User::where('username', Str::lower($loginVal))->first();
        }

        if ($user && Hash::check($credentials['password'], $user->password)) {
            RateLimiter::clear($throttleKey);

            // Check 2FA
            if ($user->two_factor_confirmed_at) {
                $request->session()->put('auth.2fa.pending', $user->id);
                return redirect()->route('2fa.challenge');
            }

            Auth::login($user);
            $request->session()->regenerate();

            // Update Last Login
            $user->update(['last_login_at' => now()]);

            // Check for New Device (ถ้าไม่เคยมี User Agent นี้มาก่อน)
            $isNewDevice = !AuditLog::where('user_id', $user->id)
                ->where('action', 'login_success')
                ->where('details->user_agent', $request->userAgent())
                ->exists();

            if ($isNewDevice) {
                try {
                    Mail::to($user->email)->send(new NewDeviceLoginNotification(
                        $user, 
                        $request->ip(), 
                        $request->userAgent()
                    ));
                } catch (\Exception $e) {
                    // Log error but don't stop login
                    \Illuminate\Support\Facades\Log::error('Failed to send new device email: ' . $e->getMessage());
                }
            }

            // Log ล็อกอินสำเร็จ
            AuditLog::create([
                'user_id' => $user->id,
                'action' => 'login_success',
                'model_type' => 'Auth',
                'model_id' => $user->id,
                'details' => [
                    'ip' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                    'login' => $loginInput
                ],
                'ip_address' => $request->ip(),
            ]);

            // If system forces 2FA for admins, require setup first
            $force2fa = Setting::where('key', 'force_2fa')->value('value') ?? '0';
            if (($user->hasRole('super-admin') || $user->hasRole('admin')) && $force2fa === '1' && empty($user->two_factor_confirmed_at)) {
                // Create persistent DB alert requiring action
                try {
                    \App\Models\PersistentAlert::create([
                        'user_id' => $user->id,
                        'type' => 'warning',
                        'title' => 'ต้องเปิดใช้งาน 2FA',
                        'message' => 'นโยบายระบบกำหนดให้ผู้ดูแลต้องเปิดใช้งาน Two-Factor Authentication ก่อนใช้งาน ระบบจะนำท่านไปยังหน้าตั้งค่า 2FA',
                        'require_action' => true,
                    ]);
                } catch (\Throwable $e) {
                    // ignore if DB not ready
                }

                return redirect()->route('user.2fa.show')->with('alert', 'นโยบายระบบกำหนดให้ผู้ดูแลต้องเปิดใช้งาน Two-Factor Authentication ก่อนใช้งาน');
            }

            // If there's an intended URL from an OIDC/OAuth flow, resume it
            if (session()->has('url.intended')) {
                return redirect()->intended($user->hasRole('super-admin') || $user->hasRole('admin') ? route('admin.dashboard') : route('user.dashboard'));
            }

            if ($user->hasRole('super-admin') || $user->hasRole('admin')) {
                return redirect()->route('admin.dashboard');
            }

            return redirect()->route('user.dashboard');
        }

        // เพิ่มจำนวนครั้งที่ผิดพลาด
        RateLimiter::hit($throttleKey, 60); // บล็อก 60 วินาทีหลังจากครบ 5 ครั้ง

        // Log ล็อกอินล้มเหลว
        AuditLog::create([
            'user_id' => null,
            'action' => 'login_failed',
            'model_type' => 'Auth',
            'model_id' => $loginInput,
            'details' => [
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'reason' => 'Invalid credentials',
                'attempts_left' => RateLimiter::remaining($throttleKey, 5)
            ],
            'ip_address' => $request->ip(),
        ]);

        return back()->withErrors([
            'login' => 'ข้อมูลการเข้าสู่ระบบไม่ถูกต้อง',
        ])->onlyInput('login');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login.page')->with('alert', 'ออกจากระบบแล้ว');
    }

    public function sendReset(Request $request)
    {
        $request->validate(['email' => ['required','email']]);

        $email = $request->input('email');
        $user = User::where('email', $email)->first();

        if (!$user) {
            // Do not reveal missing emails; behave like link sent
            return back()->with('alert', 'หากอีเมลมีอยู่ในระบบ เราได้ส่งลิงก์รีเซ็ตให้แล้ว');
        }

        // Create a reset token and send via our mailable
        $token = Password::broker()->createToken($user);
        $resetUrl = url('/password/reset/' . $token . '?email=' . urlencode($user->email));

        try {
            \Illuminate\Support\Facades\Mail::to($user->email)->send(new \App\Mail\PasswordResetMail($user, $resetUrl));
        } catch (\Throwable $e) {
            // fallback to framework default
            $status = Password::sendResetLink($request->only('email'));
            return $status === Password::RESET_LINK_SENT
                ? back()->with('alert', __($status))
                : back()->withErrors(['email' => __($status)]);
        }

        return back()->with('alert', 'หากอีเมลมีอยู่ในระบบ เราได้ส่งลิงก์รีเซ็ตให้แล้ว');
    }

    public function showResetForm(Request $request, $token = null)
    {
        return view('auth.reset')->with(
            ['token' => $token, 'email' => $request->email]
        );
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed|min:8',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                ])->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('login.page')->with('alert', __($status))
            : back()->withErrors(['email' => [__($status)]]);
    }

    public function createFirstAdmin(Request $request)
    {
        if (!Schema::hasTable('roles')) {
            abort(500, 'ไม่พบตาราง roles กรุณารัน php artisan migrate');
        }

        // Seed Default Roles if not exist
        if (Role::count() === 0) {
            Role::create(['name' => 'Super Admin', 'slug' => 'super-admin', 'permissions' => ['*']]);
            Role::create(['name' => 'Admin', 'slug' => 'admin', 'permissions' => ['access_admin', 'manage_users']]);
            Role::create(['name' => 'User', 'slug' => 'user', 'permissions' => []]);
        }

        $superAdminRole = Role::where('slug', 'super-admin')->first();

        if (User::whereHas('roles', fn($q) => $q->where('slug', 'super-admin'))->exists()) {
            abort(403, 'มี Super Admin อยู่แล้ว');
        }

        $data = $request->validate([
            'name' => ['required','string','max:255'],
            'email' => ['required','email','max:255','unique:users,email'],
            'password' => ['required','string','min:8'],
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'is_active' => 1,
            // 'is_admin' => true, // เลิกใช้ is_admin แบบเดิม หรือคงไว้เพื่อ compatibility
        ]);

        $user->roles()->attach($superAdminRole);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->intended('/admin/console')->with('alert', 'สร้าง Super Admin และ Role เริ่มต้นสำเร็จ');
    }

    public function redirectToProvider($provider)
    {
        // อนุญาตเฉพาะ Google ตามที่ขอ
        if ($provider !== 'google') {
            abort(404);
        }

        $settings = Setting::pluck('value', 'key');
        
        if (($settings["social_login_{$provider}_enable"] ?? '0') != '1') {
            abort(404);
        }

        config([
            "services.{$provider}.client_id" => $settings["social_login_{$provider}_client_id"],
            "services.{$provider}.client_secret" => $settings["social_login_{$provider}_client_secret"],
            "services.{$provider}.redirect" => route('social.callback', $provider),
        ]);

        // Bypass SSL verification for local development (Fix cURL error 60)
        return Socialite::driver($provider)
                ->setHttpClient(new \GuzzleHttp\Client(['verify' => false]))
                ->redirect();
    }

    public function handleProviderCallback($provider)
    {
        if ($provider !== 'google') {
            abort(404);
        }

        $settings = Setting::pluck('value', 'key');

        config([
            "services.{$provider}.client_id" => $settings["social_login_{$provider}_client_id"],
            "services.{$provider}.client_secret" => $settings["social_login_{$provider}_client_secret"],
            "services.{$provider}.redirect" => route('social.callback', $provider),
        ]);

        try {
            // Bypass SSL verification for local development
            $socialUser = Socialite::driver($provider)
                ->setHttpClient(new \GuzzleHttp\Client(['verify' => false]))
                ->user();
        } catch (\Exception $e) {
            return redirect()->route('login.page')->withErrors(['email' => 'Login failed: ' . $e->getMessage()]);
        }

        $user = User::where('email', $socialUser->getEmail())->first();

        if (!$user) {
            return redirect()->route('login.page')->withErrors(['email' => 'ท่านยังไม่มีบัญชีผู้ใช้งานที่เชื่อมกับ Gmail นี้ กรุณาติดต่อผู้ดูแลระบบของท่าน']);
        }

        // Check 2FA
        if ($user->two_factor_confirmed_at) {
            session()->put('auth.2fa.pending', $user->id);
            return redirect()->route('2fa.challenge');
        }

        Auth::login($user);

        AuditLog::create([
            'user_id' => $user->id,
            'action' => 'login',
            'model_type' => 'User',
            'model_id' => $user->id,
            'details' => ['provider' => $provider, 'ip' => request()->ip()],
            'ip_address' => request()->ip(),
        ]);

        return redirect()->intended(route('user.dashboard'));
    }
}
