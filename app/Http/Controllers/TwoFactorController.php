<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PragmaRX\Google2FA\Google2FA;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;
use Illuminate\Support\Str;

class TwoFactorController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        $force2fa = \App\Models\Setting::where('key', 'force_2fa')->value('value') ?? '0';
        return view('user.two-factor', compact('user', 'force2fa'));
    }

    public function enable(Request $request)
    {
        $google2fa = new Google2FA();
        $secret = $google2fa->generateSecretKey();
        
        // Store secret in session temporarily
        $request->session()->put('2fa_secret', $secret);

        $user = Auth::user();
        $qrCodeUrl = $google2fa->getQRCodeUrl(
            config('app.name'),
            $user->email,
            $secret
        );

        $renderer = new ImageRenderer(
            new RendererStyle(200),
            new SvgImageBackEnd()
        );
        $writer = new Writer($renderer);
        $qrCodeSvg = $writer->writeString($qrCodeUrl);

        return view('user.two-factor-enable', compact('secret', 'qrCodeSvg'));
    }

    public function confirm(Request $request)
    {
        $request->validate(['code' => 'required|string']);

        $secret = $request->session()->get('2fa_secret');
        if (!$secret) {
            return redirect()->route('user.2fa.show')->with('error', 'Session expired. Please try again.');
        }

        $google2fa = new Google2FA();
        $valid = $google2fa->verifyKey($secret, $request->code);

        if ($valid) {
            $user = Auth::user();
            $user->two_factor_secret = encrypt($secret);
            $user->two_factor_confirmed_at = now();
            
            // Generate Recovery Codes
            $recoveryCodes = [];
            for ($i = 0; $i < 8; $i++) {
                $recoveryCodes[] = Str::random(10) . '-' . Str::random(10);
            }
            $user->two_factor_recovery_codes = encrypt(json_encode($recoveryCodes));
            
            $user->save();
            $request->session()->forget('2fa_secret');

            return redirect()->route('user.2fa.show')->with('success', 'Two-Factor Authentication enabled successfully.')
                ->with('recovery_codes', $recoveryCodes);
        }

        return back()->withErrors(['code' => 'Invalid verification code.']);
    }

    public function disable(Request $request)
    {
        $request->validate(['password' => 'required|current_password']);

        $user = Auth::user();
        $user->two_factor_secret = null;
        $user->two_factor_recovery_codes = null;
        $user->two_factor_confirmed_at = null;
        $user->save();

        return back()->with('success', 'Two-Factor Authentication disabled.');
    }

    public function showChallenge()
    {
        return view('auth.two-factor-challenge');
    }

    public function verifyChallenge(Request $request)
    {
        $request->validate(['code' => 'nullable|string', 'recovery_code' => 'nullable|string']);

        // Retrieve the user ID from the session (set during login)
        if (!session()->has('auth.2fa.pending')) {
            return redirect()->route('login.page');
        }

        $userId = session('auth.2fa.pending');
        $user = \App\Models\User::find($userId);

        if (!$user) {
            return redirect()->route('login.page');
        }

        if ($request->filled('recovery_code')) {
            $recoveryCodes = json_decode(decrypt($user->two_factor_recovery_codes), true);
            $code = $request->recovery_code;

            if (($key = array_search($code, $recoveryCodes)) !== false) {
                unset($recoveryCodes[$key]);
                $user->two_factor_recovery_codes = encrypt(json_encode(array_values($recoveryCodes)));
                $user->save();
                
                Auth::login($user);
                session()->forget('auth.2fa.pending');
                return redirect()->intended(route('user.dashboard'));
            }
            
            return back()->withErrors(['recovery_code' => 'Invalid recovery code.']);
        }

        $google2fa = new Google2FA();
        $secret = decrypt($user->two_factor_secret);
        $valid = $google2fa->verifyKey($secret, $request->code);

        if ($valid) {
            Auth::login($user);
            session()->forget('auth.2fa.pending');
            return redirect()->intended(route('user.dashboard'));
        }

        return back()->withErrors(['code' => 'Invalid authentication code.']);
    }
}
