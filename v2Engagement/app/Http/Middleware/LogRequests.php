<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Log;

class LogRequests
{
    public function handle($request, Closure $next)
    {
        
        $request->start = microtime(true);        
        return $next($request);
    }

    public function terminate($request, $response)
    {
        $request->end = microtime(true);
        $this->log($request,$response);
    }

    protected function log($request,$response)
    {
        if(!config('engagement.request_logger')) return;
        
        
        $duration = $request->end - $request->start;
        $url = $request->fullUrl();
        $method = $request->getMethod();
        $ip = $request->getClientIp();

        $log = "\n ===========Request Start================= \n".
        "IP : {{$ip}} \n".
        "Method : {{$method}} \n".
        "Time elapsed : {{$duration}ms} \n".
        //$log = "\n ---------- Headers -------------  \n".        
        //"Headers : {[$request->headers->all()]} \n".
        $log = "\n ---------- Request Params-----------  \n".
        "Request : {[$request]} \n".
        $log = "\n ---------- Response-----------  \n".        
        "Response : {$response->getContent()} \n";
        $log .= "\n===========Request End================= \n";
                
        Log::info($log);
    }
}