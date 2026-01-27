<?php

namespace App\Http\Controllers;

use Carbon\CarbonImmutable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class SSOController extends Controller
{
    public function showLogin(Request $request)
    {
        $callback = $request->query('callback');
        $state = $request->query('state');

        if (!$this->isCallbackAllowed($callback)) {
            abort(403, 'Invalid callback');
        }

        return view('sso.login', compact('callback', 'state'));
    }

    public function authenticate(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
            'callback' => 'required|url',
            'state' => 'nullable|string',
        ]);

        if (!$this->isCallbackAllowed($request->input('callback'))) {
            abort(403, 'Invalid callback');
        }

        if (!Auth::attempt($request->only('email', 'password'))) {
            return back()->withErrors(['email' => 'ข้อมูลผู้ใช้ไม่ถูกต้อง']);
        }

        $user = $request->user();
        $token = $this->makeToken($user->id);

        $redirectUrl = $this->buildCallbackUrl($request->input('callback'), [
            'token' => $token,
            'state' => $request->input('state'),
        ]);

        return redirect()->away($redirectUrl);
    }

    public function validateToken(Request $request)
    {
        $request->validate(['token' => 'required|string']);

        $data = $this->parseToken($request->input('token'));
        if (!$data) {
            return response()->json(['valid' => false], 401);
        }

        return response()->json([
            'valid' => true,
            'user_id' => $data['sub'],
            'exp' => $data['exp'],
        ]);
    }

    private function isCallbackAllowed(?string $callback): bool
    {
        return $callback && in_array($callback, config('sso.allowed_callbacks'), true);
    }

    private function makeToken(int $userId): string
    {
        $now = CarbonImmutable::now();
        $exp = $now->addMinutes((int) config('sso.token_ttl_minutes'));
        $payload = [
            'iss' => config('app.url'),
            'sub' => $userId,
            'iat' => $now->timestamp,
            'exp' => $exp->timestamp,
            'jti' => (string) Str::uuid(),
        ];

        $payloadJson = json_encode($payload, JSON_UNESCAPED_SLASHES);
        $signature = hash_hmac('sha256', $payloadJson, config('sso.shared_secret'));

        return base64_encode($payloadJson) . '.' . $signature;
    }

    private function parseToken(string $token): ?array
    {
        [$b64Payload, $signature] = array_pad(explode('.', $token, 2), 2, null);
        if (!$b64Payload || !$signature) {
            return null;
        }

        $payloadJson = base64_decode($b64Payload, true);
        if ($payloadJson === false) {
            return null;
        }

        $expectedSignature = hash_hmac('sha256', $payloadJson, config('sso.shared_secret'));
        if (!hash_equals($expectedSignature, $signature)) {
            return null;
        }

        $payload = json_decode($payloadJson, true);
        if (!is_array($payload) || ($payload['exp'] ?? 0) < time()) {
            return null;
        }

        return $payload;
    }

    private function buildCallbackUrl(string $callback, array $params): string
    {
        $query = http_build_query(array_filter($params, fn($v) => $v !== null));
        return rtrim($callback, '?&') . (str_contains($callback, '?') ? '&' : '?') . $query;
    }
}
