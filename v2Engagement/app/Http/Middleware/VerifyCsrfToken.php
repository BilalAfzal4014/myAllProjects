<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as BaseVerifier;

class VerifyCsrfToken extends BaseVerifier
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        'bot/*',
        'api/*',
        'my_send_app_data',
        'stats',
        '/dashboard/stats',
        '/updateTrackingStatus'
    ];
}
