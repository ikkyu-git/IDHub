<?php

use App\Http\Middleware\ForcePasswordChange;
use App\Models\User;
use App\Models\Setting;
use Illuminate\Http\Request;
use Carbon\Carbon;

it('redirects user with must_change_password to password change form', function () {
    $user = User::factory()->create(['must_change_password' => 1]);
    $this->actingAs($user);

    $middleware = new ForcePasswordChange();
    $request = Request::create('/some-protected', 'GET');

    $response = $middleware->handle($request, function ($req) {
        return response('ok');
    });

    expect($response->isRedirection())->toBeTrue();
    expect(str_contains($response->headers->get('Location'), route('password.change.form')))->toBeTrue();
});

it('redirects when password expired according to settings', function () {
    Setting::create(['key' => 'password_expiry_days', 'value' => '1']);

    $user = User::factory()->create(['password_changed_at' => Carbon::now()->subDays(2)]);
    $this->actingAs($user);

    $middleware = new ForcePasswordChange();
    $request = Request::create('/some-protected', 'GET');

    $response = $middleware->handle($request, function ($req) {
        return response('ok');
    });

    expect($response->isRedirection())->toBeTrue();
    expect(str_contains($response->headers->get('Location'), route('password.change.form')))->toBeTrue();
});
