<?php

namespace App\Http\Middleware;

use App\Components\ParseResponse;
use Closure;
use Illuminate\Http\Response;

class ApiGuestMiddleware
{
    use ParseResponse;

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        dd($request->all());
        if (auth()->check()) {
            return $this->addResponse(
                Response::HTTP_UNPROCESSABLE_ENTITY,
                'error',
                ['User has already logged in'],
                'error'
            );
        }

        return $next($request);
    }
}
