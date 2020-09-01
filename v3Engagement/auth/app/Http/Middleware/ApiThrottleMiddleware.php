<?php

namespace App\Http\Middleware;

use App\Components\ParseResponse;
use Closure;
use Illuminate\Http\Response;
use Illuminate\Routing\Middleware\ThrottleRequests;

class ApiThrottleMiddleware extends ThrottleRequests
{
    use ParseResponse;

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @param  int $maxAttempts
     * @param  float|int $decayMinutes
     * @return mixed
     */
    public function handle($request, Closure $next, $maxAttempts = 500, $decayMinutes = 1)
    {
        try {
            return parent::handle($request, $next, $maxAttempts, $decayMinutes);
        } catch (\Exception $exception) {
            return $this->addResponse(
                Response::HTTP_I_AM_A_TEAPOT,
                'error',
                ['Invalid authorization headers provided.'],
                'error'
            );
        }
    }

    /**
     * Create a 'too many attempts' response.
     *
     * @param  string $key
     * @param  int $maxAttempts
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function buildResponse($key, $maxAttempts)
    {
        $retryAfter = $this->limiter->availableIn($key);

        $response = $this->addResponse(
            Response::HTTP_TOO_MANY_REQUESTS,
            'error',
            ['Too Many Attempts. Please retry after ' . $retryAfter . ' seconds'],
            'error'
        );

        return $this->addHeaders(
            $response, $maxAttempts,
            $this->calculateRemainingAttempts($key, $maxAttempts, $retryAfter),
            $retryAfter
        );
    }
}
