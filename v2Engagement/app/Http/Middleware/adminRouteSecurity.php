<?php

namespace App\Http\Middleware;

use Closure;
use session;

class adminRouteSecurity
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (session('adminSessionKey')) {
            return $next($request);
        }
        return redirect()->back();
    }
}
