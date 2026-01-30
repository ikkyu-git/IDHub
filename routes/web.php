<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\SSOController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SimpleSsoController; // เพิ่ม Controller ใหม่
use App\Http\Middleware\LogGlobalActivity;
use Illuminate\Support\Facades\Route;

Route::middleware([LogGlobalActivity::class])->group(function () {
    Route::get('/', function () {
        return redirect()->route('login.page');
    });

    // ...เส้นทางเข้าสู่ระบบ/ออกจากระบบเดิม...
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login.page');
    // Registration (public) - enabled only if allowed in settings
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register.page');
    Route::post('/register', [AuthController::class, 'register'])->middleware('throttle:5,1')->name('register.submit');
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:10,1')->name('login.submit');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::view('/forgot-password', 'auth.forgot')->name('forgot.page');
    Route::post('/forgot-password', [AuthController::class, 'sendReset'])->middleware('throttle:3,1')->name('forgot.submit');
    Route::get('/reset-password/{token}', [AuthController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [AuthController::class, 'resetPassword'])->middleware('throttle:5,1')->name('password.update');
    Route::view('/setup/admin', 'auth.setup-admin')->name('setup.admin.page');
    Route::post('/setup/admin', [AuthController::class, 'createFirstAdmin'])->name('setup.admin.submit');

    Route::view('/policy', 'policy')->name('policy');
    Route::view('/terms', 'terms')->name('terms');
    Route::view('/help', 'help')->name('help');

    // Social Login
    Route::get('login/{provider}', [AuthController::class, 'redirectToProvider'])->name('social.redirect');
    Route::get('login/{provider}/callback', [AuthController::class, 'handleProviderCallback'])->name('social.callback');

    // --- เส้นทาง Custom OAuth2 / OIDC (ระบบที่เขียนเอง) ---
    Route::middleware(['web'])->group(function () {
        // 0. OIDC Discovery & JWKS & WebFinger
        Route::get('/.well-known/openid-configuration', [SimpleSsoController::class, 'discovery'])->name('oauth.discovery');
        Route::get('/.well-known/jwks.json', [SimpleSsoController::class, 'jwks'])->name('oauth.jwks');
        Route::get('/.well-known/webfinger', [SimpleSsoController::class, 'webfinger'])->name('oauth.webfinger');

        // 1. หน้าขออนุญาตสิทธิ์ (Authorize Endpoint) - ต้อง Login ก่อน
        Route::get('/oauth/authorize', [SimpleSsoController::class, 'authorizePage'])->name('oauth.authorize');
        Route::post('/oauth/authorize', [SimpleSsoController::class, 'approve'])->name('oauth.approve');
        Route::post('/oauth/deny', [SimpleSsoController::class, 'deny'])->name('oauth.deny');
        
        // 2. จุดแลก Token (Token Endpoint) - ไม่ต้อง Login แต่ต้องส่ง Client Secret
        Route::middleware('throttle:sso-api')->post('/oauth/token', [SimpleSsoController::class, 'token'])->name('oauth.token');
        Route::post('/oauth/revoke', [SimpleSsoController::class, 'revoke'])->name('oauth.revoke');
        Route::post('/oauth/introspect', [SimpleSsoController::class, 'introspect'])->name('oauth.introspect');

        // 5. End Session Endpoint (Logout)
        Route::get('/oauth/logout', [SimpleSsoController::class, 'endSession'])->name('oauth.logout');
    });

    // 3. จุดดึงข้อมูลผู้ใช้ (User Info Endpoint) - API
    Route::middleware('throttle:sso-api')->get('/api/user', [SimpleSsoController::class, 'userInfo'])->name('oauth.userinfo');

    // 4. Developer Documentation (Restricted to Client Managers)
    Route::get('/developers', function () {
        if (!Auth::check() || !Auth::user()->hasPermission('manage_clients')) {
            abort(403, 'Access Denied: คุณไม่มีสิทธิ์เข้าถึงคู่มือนักพัฒนา');
        }
        return view('developer.index');
    })->middleware(['web', 'auth'])->name('developer.docs');

    // SAML IdP endpoints (minimal)
    Route::get('/saml/metadata', [App\Http\Controllers\SamlController::class, 'metadata'])->name('saml.metadata');
    Route::get('/saml/login', [App\Http\Controllers\SamlController::class, 'login'])->name('saml.login');
    Route::post('/saml/acs', [App\Http\Controllers\SamlController::class, 'acs'])->name('saml.acs');

    // Internal endpoint used by IdP service to validate credentials (use X-Internal-Token header)
    Route::post('/internal/saml/auth', [App\Http\Controllers\InternalSamlController::class, 'authenticate'])->name('internal.saml.auth');


    // เส้นทางที่ต้องยืนยันตัวตน (Protected Routes)
    Route::middleware(['web', 'auth'])->group(function () {
        // ...เส้นทางของผู้ดูแลระบบและผู้ใช้ทั่วไป...
        Route::prefix('admin')->name('admin.')->group(function () {
            // Dashboard
            Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
            Route::get('/console', function() { return redirect()->route('admin.dashboard'); })->name('console'); // Redirect old route

            // Users
            Route::get('/users', [AdminController::class, 'users'])->name('users');
            Route::post('/users', [AdminController::class, 'storeUser'])->name('users.store');
            Route::patch('/users/{user}', [AdminController::class, 'updateUser'])->name('users.update');
            Route::post('/users/{user}/quick-reset', [AdminController::class, 'quickResetPassword'])->name('users.quick_reset');
            Route::post('/users/{user}/impersonate', [AdminController::class, 'impersonate'])->name('users.impersonate');
            Route::post('/users/stop-impersonate', [AdminController::class, 'stopImpersonating'])->name('users.stop_impersonate');
            Route::get('/users/export', [AdminController::class, 'exportUsers'])->name('users.export');
            Route::patch('/users/{user}/role', [AdminController::class, 'updateUserRole'])->name('users.role');
            Route::patch('/users/{user}/status', [AdminController::class, 'toggleUserStatus'])->name('users.toggle_status');
            Route::get('/users/{user}/attributes', [AdminController::class, 'editAttributes'])->name('users.attributes.edit');
            Route::post('/users/{user}/attributes', [AdminController::class, 'updateAttributes'])->name('users.attributes.update');
            Route::get('/users/{user}/sessions', [AdminController::class, 'getUserSessions'])->name('users.sessions');
            Route::delete('/users/{user}/sessions/{sessionId}', [AdminController::class, 'destroyUserSession'])->name('users.sessions.destroy');
            Route::delete('/users/{user}', [AdminController::class, 'destroyUser'])->name('users.delete');

            // Roles
            Route::get('/roles', [AdminController::class, 'roles'])->name('roles');
            Route::post('/roles', [AdminController::class, 'storeRole'])->name('roles.store');
            Route::patch('/roles/{role}', [AdminController::class, 'updateRole'])->name('roles.update');
            Route::delete('/roles/{role}', [AdminController::class, 'destroyRole'])->name('roles.delete');

            // Settings
            Route::get('/settings', [AdminController::class, 'settings'])->name('settings');
            Route::post('/settings', [AdminController::class, 'updateSettings'])->name('settings.update');

            // Logs
            Route::get('/logs', [AdminController::class, 'logs'])->name('logs');
            Route::delete('/logs/clear', [AdminController::class, 'clearLogs'])->name('logs.clear');
            
            // SSO Clients
            Route::get('/clients', [AdminController::class, 'clients'])->name('clients');
            Route::post('/clients', [AdminController::class, 'storeClient'])->name('clients.store');
            Route::patch('/clients/{id}', [AdminController::class, 'updateClient'])->name('clients.update');
            Route::post('/clients/{id}/regenerate', [AdminController::class, 'regenerateClientSecret'])->name('clients.regenerate');
            Route::delete('/clients/{id}', [AdminController::class, 'destroyClient'])->name('clients.destroy');
            Route::put('/clients/{id}/revoke', [AdminController::class, 'revokeClient'])->name('clients.revoke'); // Keep for backward compatibility if needed, but view uses destroy now
            // Persistent Alerts management (Admin)
            Route::get('/alerts', [App\Http\Controllers\AlertController::class, 'index'])->name('alerts.index');
            Route::get('/alerts/create', [App\Http\Controllers\AlertController::class, 'create'])->name('alerts.create');
            Route::post('/alerts', [App\Http\Controllers\AlertController::class, 'store'])->name('alerts.store');
            Route::delete('/alerts/{id}', [App\Http\Controllers\AlertController::class, 'destroy'])->name('alerts.destroy');
            // Admin: Reset user's Two-Factor Authentication
            Route::post('/users/{user}/reset-2fa', [AdminController::class, 'resetUserTwoFactor'])->name('users.reset_2fa');
            Route::post('/users/{user}/verify', [AdminController::class, 'toggleEmailVerified'])->name('users.toggle_verified');
        });

        Route::get('/user/dashboard', [UserController::class, 'dashboard'])->name('user.dashboard');
        Route::get('/user/profile', function () { return redirect()->route('user.dashboard'); });
        Route::post('/user/profile', [UserController::class, 'updateProfile'])->name('user.profile.update');

        // Persistent alerts
        Route::post('/alerts/{id}/dismiss', [App\Http\Controllers\AlertController::class, 'dismiss'])->name('alerts.dismiss');
        
        // Authorized Apps
        Route::get('/user/authorized-apps', [UserController::class, 'authorizedApps'])->name('user.apps.list');
        Route::delete('/user/authorized-apps/{id}', [UserController::class, 'revokeApp'])->name('user.apps.revoke');

        // Two-Factor Authentication
        Route::get('/user/two-factor', [App\Http\Controllers\TwoFactorController::class, 'show'])->name('user.2fa.show');
        Route::post('/user/two-factor/enable', [App\Http\Controllers\TwoFactorController::class, 'enable'])->name('user.2fa.enable');
        Route::post('/user/two-factor/disable', [App\Http\Controllers\TwoFactorController::class, 'disable'])->name('user.2fa.disable');
        Route::post('/user/two-factor/regenerate', [App\Http\Controllers\TwoFactorController::class, 'regenerateRecoveryCodes'])->name('user.2fa.regenerate');

        // Active Sessions
        Route::get('/user/sessions', [App\Http\Controllers\SessionController::class, 'index'])->name('user.sessions');
        Route::delete('/user/sessions/{id}', [App\Http\Controllers\SessionController::class, 'destroy'])->name('user.sessions.destroy');
        Route::delete('/user/sessions', [App\Http\Controllers\SessionController::class, 'destroyAllOthers'])->name('user.sessions.destroy_all');
        
        // Login History
        Route::get('/user/login-history', [UserController::class, 'loginHistory'])->name('user.login-history');

        Route::post('/user/two-factor/confirm', [App\Http\Controllers\TwoFactorController::class, 'confirm'])->name('user.2fa.confirm');
        // Route::delete('/user/two-factor', [App\Http\Controllers\TwoFactorController::class, 'disable'])->name('user.2fa.disable'); // ซ้ำกับข้างบน

        // Force Password Change Routes
        Route::get('/password/change', [UserController::class, 'showChangePasswordForm'])->name('password.change.form');
        Route::post('/password/change', [UserController::class, 'updatePassword'])->name('password.change.update');
    });

    // 2FA Challenge (ไม่ต้อง Auth แต่ต้องมี Session pending)
    Route::get('/two-factor-challenge', [App\Http\Controllers\TwoFactorController::class, 'showChallenge'])->name('2fa.challenge');
    Route::post('/two-factor-challenge', [App\Http\Controllers\TwoFactorController::class, 'verifyChallenge'])->name('2fa.verify');

    // Email verification links
    Route::get('/email/verify/{id}/{hash}', [App\Http\Controllers\AuthController::class, 'verifyEmail'])->name('verification.verify');
    Route::post('/email/resend', [App\Http\Controllers\AuthController::class, 'resendVerification'])->middleware('auth')->name('verification.resend');

    // --- เครื่องมือทดสอบ SSO (Test Client Simulator) ---
    Route::get('/sso-test', function () { return view('sso-test'); })->name('sso.test.form');
    Route::post('/sso-test/start', function (Illuminate\Http\Request $request) {
        session(['test_client_id' => $request->client_id, 'test_client_secret' => $request->client_secret]);
        
        $scopes = $request->input('scopes', []);
        $scopeString = implode(' ', $scopes);

        $query = http_build_query([
            'client_id' => $request->client_id,
            'redirect_uri' => route('sso.test.callback'),
            'response_type' => 'code',
            'scope' => $scopeString,
            'state' => Str::random(10),
        ]);
        return redirect('/oauth/authorize?' . $query);
    })->name('sso.test.start');

    Route::get('/sso-test/callback', function (Illuminate\Http\Request $request) {
        if (!$request->has('code')) return response()->json(['error' => 'No code'], 400);
        
        // Return view to handle token exchange via JS (avoids PHP single-thread deadlock)
        return view('sso-test-callback', ['code' => $request->code]);
    })->name('sso.test.callback');
});
