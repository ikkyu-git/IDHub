<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     * These endpoints are intended for server-to-server OAuth operations.
     *
     * @var array
     */
    protected $except = [
        'oauth/token',
        'oauth/introspect',
        'oauth/revoke',
    ];
}
