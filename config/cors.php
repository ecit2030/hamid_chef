<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Laravel CORS Configuration
    |--------------------------------------------------------------------------
    |
    | This configuration controls Cross-Origin Resource Sharing settings. We
    | keep it environment-driven so deployments can safely allow only the
    | intended frontend origin (e.g. https://monchef.codebrains.net).
    |
    */

    // Paths that should be processed by the CORS middleware
    'paths' => [
        'api/*',
        'sanctum/csrf-cookie',
        'login',
        'logout',
        'me',
        'admin/*',  // admin/login, admin/logout, etc. (cross-origin from frontend)
        'chef/*',   // chef/login, chef/logout, etc.
    ],

    // Allowed methods
    'allowed_methods' => ['*'],

    // Allowed origins - must be explicit when using credentials
    'allowed_origins' => array_filter(array_map('trim', explode(',', env('CORS_ALLOWED_ORIGINS', 'http://localhost,http://localhost:3000,http://127.0.0.1:8000')))),

    // You can allow wildcard subdomains like https://*.example.com by moving
    // domains to this array and setting allowed_origins to []
    'allowed_origins_patterns' => [],

    // Allowed headers
    'allowed_headers' => ['*'],

    // Headers that are exposed to the browser
    'exposed_headers' => ['*'],

    // Preflight cache duration
    'max_age' => 86400,

    // Allow credentials (cookies, auth headers). Requires explicit origins.
    'supports_credentials' => true,
];
