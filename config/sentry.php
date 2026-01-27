<?php

return [
    'dsn' => env('SENTRY_DSN', null),
    'breadcrumbs' => [
        'sql_bindings' => true,
    ],
    'traces_sample_rate' => env('SENTRY_TRACES_SAMPLE_RATE', 0.0),
    'environment' => env('APP_ENV', 'production'),
    'release' => env('SENTRY_RELEASE'),
];
