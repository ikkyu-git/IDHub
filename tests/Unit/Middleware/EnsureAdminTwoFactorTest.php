<?php

use App\Http\Middleware\EnsureAdminTwoFactor;
use App\Models\User;
use App\Models\Role;
use App\Models\Setting;
use Illuminate\Http\Request;

it('redirects admin user without 2fa when force_2fa is enabled', function () {
    Setting::create(['key' => 'force_2fa', 'value' => '1']);

    $role = Role::create(['name' => 'Admin', 'slug' => 'admin']);
    $user = User::factory()->create(['two_factor_confirmed_at' => null]);
    $user->roles()->attach($role);

    $this->actingAs($user);

    $middleware = new EnsureAdminTwoFactor();
    $request = Request::create('/admin/dashboard', 'GET');

    $response = $middleware->handle($request, function ($req) {
        return response('ok');
    });

    expect($response->isRedirection())->toBeTrue();
    expect(str_contains($response->headers->get('Location'), route('user.2fa.show')))->toBeTrue();
});

it('does not redirect non-admin users when force_2fa is enabled', function () {
    Setting::create(['key' => 'force_2fa', 'value' => '1']);

    $user = User::factory()->create(['two_factor_confirmed_at' => null]);
    // no admin role attached

    $this->actingAs($user);

    $middleware = new EnsureAdminTwoFactor();
    $request = Request::create('/user/dashboard', 'GET');

    $response = $middleware->handle($request, function ($req) {
        return response('ok');
    });

    expect($response->getStatusCode())->toBe(200);
});
