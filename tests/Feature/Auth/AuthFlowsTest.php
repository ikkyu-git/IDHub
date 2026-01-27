<?php

use App\Mail\VerifyEmail;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;

beforeEach(function () {
    Mail::fake();
});

test('user can register and receives verification email', function () {
    $response = $this->post(route('register.submit'), [
        'first_name' => 'Jane',
        'last_name' => 'Doe',
        'username' => 'janedoe',
        'email' => 'jane@example.com',
        'password' => 'secret123',
        'password_confirmation' => 'secret123',
    ]);

    $response->assertRedirect(route('user.dashboard'));
    $this->assertDatabaseHas('users', ['email' => 'jane@example.com', 'is_active' => 1]);
    Mail::assertQueued(VerifyEmail::class, function ($mail) {
        return $mail->hasTo('jane@example.com');
    });
});

test('user can login with email and password', function () {
    $user = User::factory()->create([
        'email' => 'john@example.com',
        'password' => Hash::make('password123'),
        'is_active' => 1,
    ]);

    $response = $this->post(route('login.submit'), [
        'login' => 'john@example.com',
        'password' => 'password123',
    ]);

    $response->assertRedirect(route('user.dashboard'));
    $this->assertAuthenticatedAs($user);
});

test('login is throttled after too many attempts', function () {
    $user = User::factory()->create([
        'email' => 'throttle@example.com',
        'password' => Hash::make('password123'),
        'is_active' => 1,
    ]);

    $payload = ['login' => $user->email, 'password' => 'wrong-password'];
    $key = Str::lower($user->email).'|127.0.0.1';
    RateLimiter::clear($key);

    // 5 invalid attempts allowed; 6th should trigger lockout
    for ($i = 0; $i < 5; $i++) {
        $this->post(route('login.submit'), $payload);
    }

    $response = $this->post(route('login.submit'), $payload);
    $response->assertSessionHasErrors('login');
    $this->assertStringContainsString(
        'พยายามเข้าสู่ระบบมากเกินไป',
        $response->getSession()->get('errors')->first('login')
    );
});

test('user can verify email via signed link', function () {
    $user = User::factory()->create([
        'email' => 'verifyme@example.com',
        'email_verified_at' => null,
        'is_active' => 1,
    ]);

    $url = URL::temporarySignedRoute(
        'verification.verify',
        now()->addMinutes(30),
        ['id' => $user->id, 'hash' => sha1($user->email)]
    );

    $response = $this->get($url);
    $response->assertRedirect(route('login.page'));
    $this->assertNotNull($user->fresh()->email_verified_at);
});

test('user can reset password with valid token', function () {
    $user = User::factory()->create([
        'email' => 'resetme@example.com',
        'password' => Hash::make('oldpassword'),
        'is_active' => 1,
    ]);

    $token = Password::broker()->createToken($user);

    $response = $this->post(route('password.update'), [
        'token' => $token,
        'email' => $user->email,
        'password' => 'newpassword123',
        'password_confirmation' => 'newpassword123',
    ]);

    $response->assertRedirect(route('login.page'));
    $this->assertTrue(Hash::check('newpassword123', $user->fresh()->password));
});
