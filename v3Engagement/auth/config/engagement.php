<?php

return [
    'api' => [
        'versions' => ['v1'],
        'headers' => [
            'prefix' => 'engagiv-',
            'app' => [
                'app-id',
                'app-name',
                'app-version',
                'app-build',
                'device-type',
                'user-token',
                'device-token',
                'Accept',
                'Content-Type',
                'lang'
            ]
        ],
    ],
    'url' => [
        'auth' => env('AUTH_API_URL', 'http://localhost:8100/api/'),
        'backend' => env('BACKEND_API_URL', 'http://localhost:8200/api/'),
        'callback' => env('FRONTEND_APP_CALLBACK', 'http://localhost:8000/callback'),
        'api_authorize' => env('API_AUTHORIZE_URL', 'http://localhost:8100/oauth/authorize'),
    ],
    'requests' => [
        'delay' => env('MAX_DELAY', 60),
    ]
];