<?php

return [
    'shared_secret' => env('SSO_SHARED_SECRET', 'change-me'),
    'token_ttl_minutes' => env('SSO_TOKEN_TTL', 15),
    'allowed_callbacks' => [
        // 'https://client.example.com/sso/callback',
    ],
];
