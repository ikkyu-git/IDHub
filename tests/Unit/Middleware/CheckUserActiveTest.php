<?php

use App\Http\Middleware\CheckUserActive;
use App\Models\User;
use Illuminate\Http\Request;

it('logs out and redirects when user is not active', function () {
    $user = User::factory()->create(['is_active' => 0]);

    $this->actingAs($user);

    $middleware = new CheckUserActive();

    $request = Request::create('/dashboard', 'GET');
    // Attach session store so middleware can read/modify session
    $request->setLaravelSession(app('session')->driver());

    $response = $middleware->handle($request, function ($req) {
        return response('should not reach', 200);
    });

    // Should be a redirect to login page
    expect($response->isRedirection())->toBeTrue();
    expect(str_contains($response->headers->get('Location'), route('login.page')))->toBeTrue();
});
