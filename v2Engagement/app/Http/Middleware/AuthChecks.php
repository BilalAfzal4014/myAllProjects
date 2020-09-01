<?php

namespace App\Http\Middleware;

use Closure;
use App\Api\Response\Response;
use App\Api\Helpers\Helper;
use Illuminate\Routing\Router;
use Log;

class AuthChecks
{
    protected $auth_key;
    protected $response;
    protected $helper;
    protected $route;

    public function __construct(Response $response, Helper $helper, Router $route)
    {
        $this->helper = $helper;
        $this->response = $response;
        $this->route = $route;
        $this->auth_key = env('AUTH_KEY', 'Basic 9ddb1a0d6898db7fa65174e775adefea');
    }
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // User Agent Verification
        $useragent = $request->server('HTTP_USER_AGENT');
        $useragent = $this->helper->check_if_mobile_agent($useragent);

        // Device Token Verification
        $device_token = $request->headers->get('device-token');
        
        $api_key = $request->headers->get('x-api-key');
        
        if($api_key===$this->auth_key && $useragent===false){
            return $next($request);
        }else{
            return $this->response->unauthorize(
                "You're not authorize to access. Make sure that you're passing your api key"
                );
        }
    }
    public function terminate($request, $response)
    {
        //$r = $this->route->getRoutes();
        //dd($r);
        //$useragent = $request->server('HTTP_USER_AGENT');
        //Log::info("[API Request Info]", ['request_url'=>$request->url(), 'request_body'=>$request->all(), 'x-api-key'=>$request->headers->get('x-api-key'), 'device-token'=>$request->headers->get('device-token'),'request_method'=> $this->route->getMethods(),'action_called'=> $request->route()->getActionName()]);
    }
}
