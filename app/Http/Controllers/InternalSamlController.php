<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class InternalSamlController extends BaseController
{
    public function authenticate(Request $request)
    {
        $internalToken = env('SAML_INTERNAL_TOKEN', 'CHANGE_ME_INTERNAL_TOKEN');
        $header = $request->header('X-Internal-Token');
        if (!$header || !hash_equals($internalToken, $header)) {
            return response()->json(['error' => 'unauthorized'], 401);
        }

        $data = $request->json()->all();
        $username = $data['username'] ?? $data['email'] ?? null;
        $password = $data['password'] ?? null;

        if (!$username || !$password) {
            return response()->json(['error' => 'invalid_request'], 400);
        }

        try {
            $user = \App\Models\User::where('email', $username)->orWhere('username', $username)->first();
            if (!$user) {
                return response()->json(['error' => 'invalid_credentials'], 401);
            }

            if (!password_verify($password, $user->password)) {
                return response()->json(['error' => 'invalid_credentials'], 401);
            }

            $attributes = [
                'uid' => [$user->id],
                'email' => [$user->email],
                'displayName' => [$user->name ?? ''],
                'givenName' => [$user->first_name ?? ''],
                'sn' => [$user->last_name ?? ''],
            ];

            try {
                if (method_exists($user, 'roles')) {
                    $roles = $user->roles->pluck('slug')->toArray();
                    $attributes['roles'] = $roles;
                }
            } catch (\Exception $e) {
                // ignore
            }

            return response()->json(['ok' => true, 'attributes' => $attributes]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'server_error'], 500);
        }
    }
}
