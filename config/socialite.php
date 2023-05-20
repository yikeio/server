<?php

return [
    'github' => [
        'client_id' => env('GITHUB_CLIENT_ID'),
        'client_secret' => env('GITHUB_CLIENT_SECRET'),
        'redirect' => env('GITHUB_REDIRECT_URI'),
    ],

    'dashboard' => [
        'provider' => 'github',
        'client_id' => env('DASHBOARD_GITHUB_CLIENT_ID'),
        'client_secret' => env('DASHBOARD_GITHUB_CLIENT_SECRET'),
        'redirect' => env('DASHBOARD_GITHUB_REDIRECT_URI'),
    ],

    'google' => [
        'client_id' => env('GOOGLE_CLIENT_ID'),
        'client_secret' => env('GOOGLE_CLIENT_SECRET'),
        'redirect' => env('GOOGLE_REDIRECT_URI'),
    ],
];
