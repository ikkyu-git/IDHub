<?php

use App\Http\Middleware\SecurityHeaders;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

it('adds security headers to responses', function () {
    $middleware = new SecurityHeaders();

    $request = Request::create('/','GET');

    $response = $middleware->handle($request, function ($req) {
        return new Response('ok', 200);
    });

    expect($response->headers->has('Content-Security-Policy'))->toBeTrue();
    expect($response->headers->get('X-Frame-Options'))->toBe('DENY');
    expect($response->headers->get('X-Content-Type-Options'))->toBe('nosniff');
    expect($response->headers->get('X-XSS-Protection'))->toBe('1; mode=block');
    expect($response->headers->get('Referrer-Policy'))->toBe('strict-origin-when-cross-origin');
    expect($response->headers->has('Permissions-Policy'))->toBeTrue();

    // HSTS should not be present in testing env
    expect($response->headers->has('Strict-Transport-Security'))->toBeFalse();
});
