<?php

namespace App\Http\Middleware;

use Closure;

class CheckApiVersionMiddleware
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
        if (!in_array($request->segment(2), config('api.versions'))) {
            return response()->json("Invalid API version number", 404);
        }

        return $next($request);
    }
}
