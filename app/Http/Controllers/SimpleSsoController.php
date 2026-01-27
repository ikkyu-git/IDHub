<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SsoClient;
use App\Models\SsoAuthCode;
use App\Models\SsoAccessToken;
use App\Models\SsoRefreshToken;
use App\Models\SsoConsent;
use App\Models\AuditLog; // เพิ่ม AuditLog
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Firebase\JWT\JWT;
use Firebase\JWT\JWK; // Import JWK

class SimpleSsoController extends Controller
{
    public function discovery()
    {
        return response()->json([
            'issuer' => url('/'),
            'authorization_endpoint' => route('oauth.authorize'),
            'token_endpoint' => route('oauth.token'),
            'userinfo_endpoint' => route('oauth.userinfo'),
            'end_session_endpoint' => route('oauth.logout'),
            'revocation_endpoint' => route('oauth.revoke'),
            'introspection_endpoint' => route('oauth.introspect'),
            'jwks_uri' => route('oauth.jwks'),
            'response_types_supported' => ['code'],
            'subject_types_supported' => ['public'],
            'id_token_signing_alg_values_supported' => ['RS256'],
            'scopes_supported' => ['openid', 'profile', 'email', 'offline_access', 'roles'],
            'token_endpoint_auth_methods_supported' => ['client_secret_post', 'client_secret_basic'],
            'claims_supported' => ['sub', 'iss', 'aud', 'exp', 'iat', 'auth_time', 'nonce', 'at_hash', 'name', 'given_name', 'family_name', 'preferred_username', 'first_name', 'last_name', 'username', 'email', 'email_verified', 'picture', 'updated_at', 'amr', 'azp', 'roles'],
            'code_challenge_methods_supported' => ['plain', 'S256'],
        ]);
    }

    public function webfinger(Request $request)
    {
        // RFC 7033 WebFinger
        $resource = $request->input('resource');
        $rel = $request->input('rel');

        // Basic validation
        if (!$resource || !$rel) {
            return response()->json(['error' => 'invalid_request'], 400);
        }

        // In a real implementation, we should check if $resource matches our issuer or a user
        // For now, we just confirm we are the issuer.

        return response()->json([
            'subject' => $resource,
            'links' => [
                [
                    'rel' => 'http://openid.net/specs/connect/1.0/issuer',
                    'href' => url('/'),
                ]
            ]
        ])->header('Content-Type', 'application/jrd+json');
    }

    public function jwks()
    {
        $publicKey = file_get_contents(storage_path('oauth-public.key'));
        if (!$publicKey) {
            return response()->json(['keys' => []]);
        }

        // Convert PEM to JWK
        $res = openssl_pkey_get_public($publicKey);
        $details = openssl_pkey_get_details($res);
        
        return response()->json([
            'keys' => [
                [
                    'kty' => 'RSA',
                    'alg' => 'RS256',
                    'use' => 'sig',
                    'kid' => '1', // Key ID (Rotate this when changing keys)
                    'n' => rtrim(strtr(base64_encode($details['rsa']['n']), '+/', '-_'), '='),
                    'e' => rtrim(strtr(base64_encode($details['rsa']['e']), '+/', '-_'), '='),
                ]
            ]
        ]);
    }

    public function authorizePage(Request $request)
    {
        $request->validate([
            'client_id' => 'required',
            'redirect_uri' => 'required|url',
            'response_type' => 'required|in:code',
            'code_challenge' => 'nullable|string',
            'code_challenge_method' => 'nullable|in:plain,S256',
        ]);

        $client = SsoClient::where('client_id', $request->client_id)->first();

        if (!$client) {
            return response()->json(['error' => 'invalid_client'], 400);
        }

        if (!in_array($request->redirect_uri, $client->redirect_uris ?? [])) {
            return response()->json(['error' => 'invalid_redirect_uri'], 400);
        }

        // Handle 'prompt' parameter
        $prompt = $request->input('prompt');
        if ($prompt === 'none') {
            if (!Auth::check()) {
                $query = http_build_query([
                    'error' => 'login_required',
                    'state' => $request->state ?? '',
                ]);
                $separator = str_contains($request->redirect_uri, '?') ? '&' : '?';
                return redirect($request->redirect_uri . $separator . $query);
            }
        } elseif ($prompt === 'login') {
            Auth::logout();
            session(['url.intended' => $request->fullUrl()]);
            return redirect()->route('login.page');
        }

        if (!Auth::check()) {
            session(['url.intended' => $request->fullUrl()]);
            return redirect()->route('login.page');
        }

        // If user has previously granted consent for this client and requested scopes
        // are a subset of the stored consent scopes, auto-approve and skip consent UI.
        $requestedScopes = array_filter(explode(' ', trim((string) $request->input('scope', ''))));
        if (!empty($requestedScopes)) {
            $existingConsent = SsoConsent::where('user_id', Auth::id())
                ->where('client_id', $client->id)
                ->first();

            if ($existingConsent) {
                $grantedScopes = array_filter(explode(' ', trim((string) $existingConsent->scopes)));
                $diff = array_diff($requestedScopes, $grantedScopes);
                if (empty($diff)) {
                    // Already consented to required scopes — auto-approve
                    $code = Str::random(40);
                    SsoAuthCode::create([
                        'id' => $code,
                        'client_id' => $client->id,
                        'user_id' => Auth::id(),
                        'scopes' => $request->scope ?? '',
                        'nonce' => $request->nonce,
                        'expires_at' => Carbon::now()->addMinutes(10),
                        'code_challenge' => $request->code_challenge,
                        'code_challenge_method' => $request->code_challenge_method,
                    ]);

                    AuditLog::create([
                        'user_id' => Auth::id(),
                        'action' => 'sso_auto_approve',
                        'details' => [
                            'client_id' => $client->client_id,
                            'client_name' => $client->name,
                            'scopes' => $request->scope,
                        ],
                        'ip_address' => $request->ip(),
                    ]);

                    $query = http_build_query([
                        'code' => $code,
                        'state' => $request->state ?? '',
                    ]);
                    $separator = str_contains($request->redirect_uri, '?') ? '&' : '?';
                    return redirect($request->redirect_uri . $separator . $query);
                }
            }
        }

        return response()
            ->view('auth.authorize', [
                'client' => $client,
                'request' => $request->all(),
            ])
            ->header('X-Frame-Options', 'DENY');
    }

    public function approve(Request $request)
    {
        $request->validate([
            'client_id' => 'required',
            'redirect_uri' => 'required',
        ]);

        if (!Auth::check()) {
            return redirect()->route('login.page');
        }

        $client = SsoClient::where('client_id', $request->client_id)->firstOrFail();
        
        $code = Str::random(40);
        SsoAuthCode::create([
            'id' => $code,
            'client_id' => $client->id,
            'user_id' => Auth::id(),
            'scopes' => $request->scope ?? '',
            'nonce' => $request->nonce,
            'expires_at' => Carbon::now()->addMinutes(10),
            'code_challenge' => $request->code_challenge,
            'code_challenge_method' => $request->code_challenge_method,
        ]);

        // Log Audit
        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => 'sso_authorize',
            'details' => [
                'client_id' => $client->client_id,
                'client_name' => $client->name,
                'scopes' => $request->scope,
            ],
            'ip_address' => $request->ip(),
        ]);

        // Persist consent: merge existing scopes with newly granted scopes
        try {
            $requested = array_filter(explode(' ', trim((string) $request->input('scope', ''))));
            $consent = SsoConsent::where('user_id', Auth::id())->where('client_id', $client->id)->first();
            if ($consent) {
                $existing = array_filter(explode(' ', trim((string) $consent->scopes)));
                $merged = array_unique(array_merge($existing, $requested));
                $consent->scopes = implode(' ', $merged);
                $consent->granted_at = now();
                $consent->save();
            } else {
                SsoConsent::create([
                    'user_id' => Auth::id(),
                    'client_id' => $client->id,
                    'scopes' => implode(' ', $requested),
                    'granted_at' => now(),
                ]);
            }
        } catch (\Throwable $e) {
            // don't block authorization if consent persistence fails
        }

        $query = http_build_query([
            'code' => $code,
            'state' => $request->state ?? '',
        ]);

        $separator = str_contains($request->redirect_uri, '?') ? '&' : '?';
        return redirect($request->redirect_uri . $separator . $query);
    }

    public function deny(Request $request)
    {
        $request->validate([
            'redirect_uri' => 'required',
            'state' => 'nullable|string',
        ]);

        // Log Audit
        if (Auth::check()) {
            AuditLog::create([
                'user_id' => Auth::id(),
                'action' => 'sso_deny',
                'details' => [
                    'redirect_uri' => $request->redirect_uri,
                ],
                'ip_address' => $request->ip(),
            ]);
        }

        $query = http_build_query([
            'error' => 'access_denied',
            'error_description' => 'The resource owner denied the request.',
            'state' => $request->state ?? '',
        ]);

        $separator = str_contains($request->redirect_uri, '?') ? '&' : '?';
        return redirect($request->redirect_uri . $separator . $query);
    }

    public function token(Request $request)
    {
        // Handle Client Authentication (Basic Auth or Post Body)
        $clientId = $request->input('client_id');
        $clientSecret = $request->input('client_secret');

        if ($request->header('Authorization')) {
            if (str_starts_with($request->header('Authorization'), 'Basic ')) {
                $credentials = base64_decode(substr($request->header('Authorization'), 6));
                if (str_contains($credentials, ':')) {
                    [$clientId, $clientSecret] = explode(':', $credentials, 2);
                }
            }
        }

        if (!$clientId || !$clientSecret) {
             return response()->json(['error' => 'invalid_client'], 401)
                ->header('WWW-Authenticate', 'Basic realm="SSO"');
        }

        $grantType = $request->input('grant_type');
        if (!$grantType) {
             return response()->json(['error' => 'invalid_request', 'error_description' => 'The grant_type parameter is missing.'], 400);
        }

        if (!in_array($grantType, ['authorization_code', 'refresh_token'])) {
            return response()->json(['error' => 'unsupported_grant_type'], 400);
        }

        $client = SsoClient::where('client_id', $clientId)
            ->where('client_secret', $clientSecret)
            ->first();

        if (!$client) {
            return response()->json(['error' => 'invalid_client'], 401)
                ->header('WWW-Authenticate', 'Basic realm="SSO"');
        }

        $userId = null;
        $scopes = '';
        $nonce = null;

        if ($grantType === 'authorization_code') {
            $request->validate([
                'code' => 'required',
                'redirect_uri' => 'required',
            ]);

            $authCode = SsoAuthCode::where('id', $request->code)
                ->where('client_id', $client->id)
                ->where('revoked', false)
                ->where('expires_at', '>', Carbon::now())
                ->first();

            if (!$authCode) {
                return response()->json(['error' => 'invalid_grant'], 400);
            }

            // PKCE Verification
            if ($authCode->code_challenge) {
                if (!$request->has('code_verifier')) {
                    return response()->json(['error' => 'invalid_request', 'error_description' => 'code_verifier is required'], 400);
                }

                $verifier = $request->code_verifier;
                $challenge = $authCode->code_challenge;
                $method = $authCode->code_challenge_method ?? 'plain';

                if ($method === 'S256') {
                    // Base64URL-encode(SHA256(verifier))
                    $hash = hash('sha256', $verifier, true);
                    $calculatedChallenge = rtrim(strtr(base64_encode($hash), '+/', '-_'), '=');
                    
                    if (!hash_equals($challenge, $calculatedChallenge)) {
                        return response()->json(['error' => 'invalid_grant', 'error_description' => 'PKCE verification failed'], 400);
                    }
                } else {
                    // plain
                    if (!hash_equals($challenge, $verifier)) {
                        return response()->json(['error' => 'invalid_grant', 'error_description' => 'PKCE verification failed'], 400);
                    }
                }
            }

            $userId = $authCode->user_id;
            $scopes = $authCode->scopes;
            $nonce = $authCode->nonce;
            $authCode->update(['revoked' => true]);

        } elseif ($grantType === 'refresh_token') {
            $request->validate(['refresh_token' => 'required']);

            $refreshToken = SsoRefreshToken::where('id', $request->refresh_token)
                ->where('revoked', false)
                ->where('expires_at', '>', Carbon::now())
                ->first();

            if (!$refreshToken) {
                return response()->json(['error' => 'invalid_grant'], 400);
            }

            $oldAccessToken = SsoAccessToken::find($refreshToken->access_token_id);
            
            if (!$oldAccessToken || $oldAccessToken->client_id !== $client->id) {
                 return response()->json(['error' => 'invalid_grant'], 400);
            }

            $userId = $oldAccessToken->user_id;
            
            // Scope narrowing logic (RFC 6749 Section 6)
            $requestedScopes = $request->input('scope');
            if ($requestedScopes) {
                // Validate that requested scopes are a subset of original scopes
                $originalScopesArray = array_filter(explode(' ', $oldAccessToken->scopes));
                $requestedScopesArray = array_filter(explode(' ', $requestedScopes));
                
                $diff = array_diff($requestedScopesArray, $originalScopesArray);
                if (!empty($diff)) {
                     return response()->json(['error' => 'invalid_scope'], 400);
                }
                $scopes = $requestedScopes;
            } else {
                $scopes = $oldAccessToken->scopes;
            }

            $refreshToken->update(['revoked' => true]);
        }

        $accessTokenId = Str::random(60);
        SsoAccessToken::create([
            'id' => $accessTokenId,
            'client_id' => $client->id,
            'user_id' => $userId,
            'scopes' => $scopes,
            'expires_at' => Carbon::now()->addDays(15),
        ]);

        $refreshTokenId = null;
        $scopesArray = explode(' ', $scopes);

        // Only issue refresh token if offline_access scope is requested
        if (in_array('offline_access', $scopesArray)) {
            $refreshTokenId = Str::random(60);
            SsoRefreshToken::create([
                'id' => $refreshTokenId,
                'access_token_id' => $accessTokenId,
                'expires_at' => Carbon::now()->addDays(30),
            ]);
        }

        // Generate OIDC ID Token (JWT)
        $user = \App\Models\User::find($userId);
        $payload = [
            'iss' => url('/'),
            'sub' => (string) $user->id,
            'aud' => $client->client_id,
            'azp' => $client->client_id,
            'iat' => time(),
            'exp' => time() + 3600,
        ];

        // Add auth_time if available
        if ($user->last_login_at) {
            $payload['auth_time'] = $user->last_login_at->getTimestamp();
        } else {
            $payload['auth_time'] = time();
        }

        // Add at_hash
        $atHash = substr(hash('sha256', $accessTokenId, true), 0, 16);
        $payload['at_hash'] = rtrim(strtr(base64_encode($atHash), '+/', '-_'), '=');

        if ($nonce) {
            $payload['nonce'] = $nonce;
        }

        // Add amr (Authentication Methods References)
        $payload['amr'] = ['pwd'];

        // Scope-based Claims (ID Token)
        // $scopesArray is already defined above

        if (in_array('profile', $scopesArray)) {
            $payload['name'] = $user->name;
            // Include standard profile claims and app-specific fields
            $payload['given_name'] = $user->first_name;
            $payload['family_name'] = $user->last_name;
            $payload['preferred_username'] = $user->username;
            $payload['first_name'] = $user->first_name;
            $payload['last_name'] = $user->last_name;
            $payload['username'] = $user->username;
            $payload['updated_at'] = $user->updated_at ? $user->updated_at->getTimestamp() : time();
            if ($user->avatar_url) {
                $payload['picture'] = asset($user->avatar_url);
            }
        }

        if (in_array('email', $scopesArray)) {
            $payload['email'] = $user->email;
            $payload['email_verified'] = !is_null($user->email_verified_at);
        }

        if (in_array('roles', $scopesArray)) {
            $payload['roles'] = $user->roles->pluck('slug')->toArray();
        }

        // Custom Attributes Mapping for ID Token
        if ($user->attributes) {
            foreach ($user->attributes as $key => $value) {
                if (in_array($key, $scopesArray)) {
                    $payload[$key] = $value;
                }
            }
        }

        // Use RSA Private Key for Signing (RS256)
        $privateKey = file_get_contents(storage_path('oauth-private.key'));
        $idToken = JWT::encode($payload, $privateKey, 'RS256', '1'); // '1' is the Key ID (kid)

        // Log Audit
        AuditLog::create([
            'user_id' => $userId,
            'action' => 'sso_token_issued',
            'details' => [
                'client_id' => $client->client_id,
                'grant_type' => $grantType,
                'scopes' => $scopes,
            ],
            'ip_address' => $request->ip(),
        ]);

        $response = [
            'access_token' => $accessTokenId,
            'token_type' => 'Bearer',
            'expires_in' => 15 * 24 * 60 * 60,
            'scope' => $scopes,
            'id_token' => $idToken,
        ];

        if ($refreshTokenId) {
            $response['refresh_token'] = $refreshTokenId;
        }

        return response()->json($response);
    }

    public function userInfo(Request $request)
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

        $user = \App\Models\User::find($token->user_id);
        $scopesArray = explode(' ', $token->scopes);

        $claims = [
            'sub' => (string) $user->id,
        ];

        if (in_array('profile', $scopesArray)) {
            $claims['name'] = $user->name;
            // Provide both standard OIDC profile claims and app-specific fields
            $claims['given_name'] = $user->first_name;
            $claims['family_name'] = $user->last_name;
            $claims['preferred_username'] = $user->username;
            $claims['first_name'] = $user->first_name;
            $claims['last_name'] = $user->last_name;
            $claims['username'] = $user->username;
            $claims['updated_at'] = $user->updated_at ? $user->updated_at->getTimestamp() : time();
            if ($user->avatar_url) {
                $claims['picture'] = asset($user->avatar_url);
            }
        }

        if (in_array('email', $scopesArray)) {
            $claims['email'] = $user->email;
            $claims['email_verified'] = !is_null($user->email_verified_at);
        }

        // Custom Attributes Mapping
        // Logic: If a scope matches a key in user->attributes, include it.
        if ($user->attributes) {
            foreach ($user->attributes as $key => $value) {
                if (in_array($key, $scopesArray)) {
                    $claims[$key] = $value;
                }
            }
        }

        if (in_array('roles', $scopesArray)) {
            $claims['roles'] = $user->roles->pluck('slug')->toArray();
        }

        return response()->json($claims);
    }

    public function endSession(Request $request)
    {
        $idTokenHint = $request->input('id_token_hint');
        $postLogoutRedirectUri = $request->input('post_logout_redirect_uri');
        $state = $request->input('state');

        // Log out the user from the SSO session
        Auth::logout();

        if ($postLogoutRedirectUri) {
            if ($idTokenHint) {
                try {
                    // Decode id_token_hint to identify the client (aud)
                    // We just decode the payload here. In a stricter implementation, verify the signature.
                    $parts = explode('.', $idTokenHint);
                    if (count($parts) === 3) {
                        $payload = json_decode(base64_decode(strtr($parts[1], '-_', '+/')), true);
                        if (isset($payload['aud'])) {
                            $clientId = $payload['aud'];
                            $client = SsoClient::where('client_id', $clientId)->first();
                            
                            if ($client && in_array($postLogoutRedirectUri, $client->redirect_uris ?? [])) {
                                $separator = str_contains($postLogoutRedirectUri, '?') ? '&' : '?';
                                $url = $postLogoutRedirectUri;
                                if ($state) {
                                    $url .= $separator . 'state=' . $state;
                                }
                                return redirect($url);
                            }
                        }
                    }
                } catch (\Exception $e) {
                    // Invalid token, ignore
                }
            }
        }

        return redirect('/'); // Default logout destination
    }

    public function revoke(Request $request)
    {
        // RFC 7009 Token Revocation
        $request->validate([
            'token' => 'required',
            'token_type_hint' => 'nullable|in:access_token,refresh_token',
        ]);

        // Client Authentication (Optional but recommended for confidential clients)
        // In this simple implementation, we allow public revocation if they have the token.
        
        $token = $request->token;
        $hint = $request->token_type_hint;

        if ($hint === 'refresh_token') {
            SsoRefreshToken::where('id', $token)->update(['revoked' => true]);
        } elseif ($hint === 'access_token') {
            SsoAccessToken::where('id', $token)->update(['revoked' => true]);
        } else {
            // Try both
            SsoAccessToken::where('id', $token)->update(['revoked' => true]);
            SsoRefreshToken::where('id', $token)->update(['revoked' => true]);
        }

        return response()->json([], 200);
    }

    public function introspect(Request $request)
    {
        // RFC 7662 Token Introspection
        $clientId = $request->input('client_id');
        $clientSecret = $request->input('client_secret');

        if ($request->header('Authorization')) {
            if (str_starts_with($request->header('Authorization'), 'Basic ')) {
                $credentials = base64_decode(substr($request->header('Authorization'), 6));
                if (str_contains($credentials, ':')) {
                    [$clientId, $clientSecret] = explode(':', $credentials, 2);
                }
            }
        }

        $client = SsoClient::where('client_id', $clientId)
            ->where('client_secret', $clientSecret)
            ->first();

        if (!$client) {
            return response()->json(['active' => false], 401);
        }

        $token = $request->input('token');
        // $hint = $request->input('token_type_hint'); // Optional hint

        $accessToken = SsoAccessToken::where('id', $token)->first();
        
        if ($accessToken && !$accessToken->revoked && $accessToken->expires_at > Carbon::now()) {
             return response()->json([
                 'active' => true,
                 'scope' => $accessToken->scopes,
                 'client_id' => $client->client_id, 
                 'sub' => (string) $accessToken->user_id,
                 'exp' => $accessToken->expires_at->getTimestamp(),
                 'iat' => $accessToken->created_at->getTimestamp(),
                 'iss' => url('/'),
                 'token_type' => 'Bearer',
             ]);
        }
        
        return response()->json(['active' => false]);
    }
}
