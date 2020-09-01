<?php

namespace App\Http\Middleware;

use Closure;

class privilege
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
        $rolesArr = $request->user()->roles()->pluck('name')->toArray();
        $contoller = $request->route()->getAction()['controller'];//dd($contoller);
        if (strpos($contoller, 'HomeController') !== false) {
            return $next($request);
        }

        //SUPER-ADMIN is login and UserController is not hit.
        if (in_array('SUPER-ADMIN', $rolesArr) &&
            strpos($contoller, 'UserController') === false &&
            strpos($contoller, 'LookupController') === false &&
            strpos($contoller, 'LocationController') === false &&
            strpos($contoller, 'SettingController') === false &&
            strpos($contoller, 'QuickNotificationController') === false &&
            strpos($contoller, 'NewsFeedTemplate') === false &&
            strpos($contoller, 'CampaignTemplateController') === false
        ) {
            abort(403, 'Unauthorized');
        } //not SUPER-ADMIN is login and UserController is hit and not update and edit link.
        else if (!in_array('SUPER-ADMIN', $rolesArr) &&
            (strpos($contoller, 'UserController') !== false &&
                strpos($contoller, 'UserController@updateUser') === false &&
                ($request->users != \Auth::user()->id ||
                    strpos($contoller, 'UserController@edit') === false) || strpos($contoller, 'SettingController') || strpos($contoller, 'LookupController')
            ) && strpos($contoller, 'LookupController') === false) {

            abort(403, 'Unauthorized');
        }
        // dd($request);
        return $next($request);
    }
}
