<?php

namespace App\Http\Middleware;

use App\Libraries\tv_jwt;
use Carbon\Carbon;
use Closure;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use App\User;
if (!class_exists('JWT')) {
    require app_path() . '/Libraries/JWT/JWT.php';
}
//use JWT;

class JwtAuth
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
        $jwtToken = $request->jwtToken;
        $userType = $request->userType;
        try {
            $objToken = \JWT::decode($jwtToken, config('common.JWT.apiKey.'.$userType), array('HS256'));
            if (empty($objToken)) {
                //throw new UnauthorizedHttpException('Token is invalid.');
                return response()->json(['error' => 'Token is invalid.'], 401);
            }
            $expiryDate= config('common.JWT.tokenExpiryTime');
            if (!empty($objToken->exp) && strtotime(date("Y-m-d H:i:s", strtotime("{$expiryDate} Minute"))) > strtotime(date("Y-m-d H:i:s"))) {
//                dd(User::where([['id','=',$objToken->userId]])->count());
//                dd($objToken);

                if( User::where([['id','=',$objToken->userId], ['token','=',$objToken->userToken]])->count() ){

                    return $response = $next($request);
                }else{
                    return response()->json(['error' => 'This user not exist.'], 401);
                }
                //$response->header('Content-Type', 'text/plain')->header('X-Header-One', 'Header Value');
                //return $response;
            } else {
                return response()->json(['error' => 'Token has expired.'], 401);
            }
        } catch (Exception $e) {
            //throw new UnauthorizedHttpException($e->getMessage());
            return response()->json(['error' => 'Unauthenticated.'], 401);
        }

    }
}
