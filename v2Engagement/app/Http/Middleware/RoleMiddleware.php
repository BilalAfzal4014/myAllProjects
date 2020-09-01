<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class RoleMiddleware {

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
     public function handle( $request, Closure $next, $permission ) {

          //print_r($request->user()->role('Staff')->get());
          //echo $role.'-'.$permission;
          //echo $request->user()->hasRole($role);
          // echo $request->user()->can('home');
          //exit;
          // if (Auth::guest()) {
          //     return redirect($urlOfYourLoginPage);
          // }
          // abort(403, 'Unauthorized action.');
          // dd( $permission );
          $rolesArr = $request->user()->roles()->pluck('name')->toArray();
          dd($request->user());
          // echo $request->user()->hasRole( 'admin' );
          if ( empty( $rolesArr ) ) {
             abort(403, 'Unauthorized');
          }
          $allPermissionArr = $request->user()->getPermissionsViaRoles()->pluck('name')->toArray();
          // dd($allPermissionArr);exit;
          // echo $request->user()->can( $permission );exit;
          // dd($allPermissionArr->toArray());exit;
          if ( !in_array( $permission, $allPermissionArr ) ) {
             abort(403, 'Unauthorized');
          }
          return $next($request);
     }
}
