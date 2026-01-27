<?php

use App\Models\SsoClient;
use App\Models\SsoAuthCode;
use App\Models\SsoAccessToken;
use App\Models\SsoRefreshToken;
use App\Models\User;
use Illuminate\Support\Str;

beforeEach(function () {
    // Ensure keys exist for token signing during tests
    if (!file_exists(storage_path('oauth-private.key'))) {
        // Create temporary key pair for tests
        $res = openssl_pkey_new(['private_key_bits' => 2048, 'private_key_type' => OPENSSL_KEYTYPE_RSA]);
        openssl_pkey_export($res, $privateKey);
        $pubKey = openssl_pkey_get_details($res)['key'];
        file_put_contents(storage_path('oauth-private.key'), $privateKey);
        file_put_contents(storage_path('oauth-public.key'), $pubKey);
    }
});

it('performs authorization code grant and returns userinfo', function () {
    $user = User::factory()->create([
        'email' => 'sso_user@example.com',
        'password' => bcrypt('password'),
        'is_active' => 1,
    ]);

    $client = SsoClient::create([
        'name' => 'Test Client',
        'client_id' => 'test-client',
        'client_secret' => 'secret123',
        'redirect_uris' => ['https://app.example/callback'],
    ]);

    // Simulate user logged in and approve
    $this->actingAs($user);

    $response = $this->post('/oauth/authorize', [
        'client_id' => $client->client_id,
        'redirect_uri' => 'https://app.example/callback',
        'scope' => 'openid profile email',
    ]);

    $response->assertRedirect();
    $location = $response->headers->get('Location');
    // Extract code parameter from redirect
    $this->assertStringContainsString('code=', $location);
    parse_str(parse_url($location, PHP_URL_QUERY), $params);
    $this->assertArrayHasKey('code', $params);

    $code = $params['code'];


    // Exchange code for token
    $tokenResponse = $this->postJson('/oauth/token', [
        'grant_type' => 'authorization_code',
        'code' => $code,
        'redirect_uri' => 'https://app.example/callback',
        'client_id' => $client->client_id,
        'client_secret' => $client->client_secret,
    ]);

    if ($tokenResponse->status() !== 200) {
        fwrite(STDOUT, "TOKEN RESPONSE: " . $tokenResponse->getContent() . PHP_EOL);
    }
    $tokenResponse->assertStatus(200, $tokenResponse->getContent());
    $tokenResponse->assertJsonStructure(['access_token', 'token_type', 'expires_in', 'id_token', 'scope']);
    $accessToken = $tokenResponse->json('access_token');


    // Call userinfo endpoint
    $userInfo = $this->withHeader('Authorization', 'Bearer ' . $accessToken)
        ->getJson('/api/user');

    $userInfo->assertStatus(200);
    $userInfo->assertJsonFragment(['email' => $user->email]);
});

it('issues refresh token when offline_access requested and allows refresh', function () {
    $user = User::factory()->create([
        'email' => 'sso_refresh@example.com',
        'password' => bcrypt('password'),
        'is_active' => 1,
    ]);

    $client = SsoClient::create([
        'name' => 'Test Client 2',
        'client_id' => 'test-client-2',
        'client_secret' => 'secret456',
        'redirect_uris' => ['https://app.example/callback2'],
    ]);

    $this->actingAs($user);

    $response = $this->post('/oauth/authorize', [
        'client_id' => $client->client_id,
        'redirect_uri' => 'https://app.example/callback2',
        'scope' => 'openid offline_access',
    ]);

    $response->assertRedirect();
    $location = $response->headers->get('Location');
    parse_str(parse_url($location, PHP_URL_QUERY), $params);
    $this->assertArrayHasKey('code', $params);
    $code = $params['code'];


    $tokenResponse = $this->postJson('/oauth/token', [
        'grant_type' => 'authorization_code',
        'code' => $code,
        'redirect_uri' => 'https://app.example/callback2',
        'client_id' => $client->client_id,
        'client_secret' => $client->client_secret,
    ]);

    if ($tokenResponse->status() !== 200) {
        fwrite(STDOUT, "TOKEN RESPONSE (refresh): " . $tokenResponse->getContent() . PHP_EOL);
    }
    $tokenResponse->assertStatus(200, $tokenResponse->getContent());
    $tokenResponse->assertJsonStructure(['access_token', 'refresh_token', 'id_token']);
    $refreshToken = $tokenResponse->json('refresh_token');


    // Use refresh token
    $refreshResponse = $this->postJson('/oauth/token', [
        'grant_type' => 'refresh_token',
        'refresh_token' => $refreshToken,
        'client_id' => $client->client_id,
        'client_secret' => $client->client_secret,
    ]);

    $refreshResponse->assertJsonStructure(['access_token', 'token_type', 'expires_in', 'id_token']);
});
