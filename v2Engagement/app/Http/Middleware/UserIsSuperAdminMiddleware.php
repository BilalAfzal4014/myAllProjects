<?php

namespace App\Http\Middleware;

use Closure;

class UserIsSuperAdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (auth()->check() && auth()->user()->hasRole(
            config('engagement.roles.super')
        )) {
            return $next($request);
        }

        return response()->json('You must be super admin to access this page', 401);
    }
}
