<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;

class Permission {

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next) {
        $findIt = false;
        $action = $request->route()->getAction();

        if (isset($action['as']) && strpos($action['as'], "admin") === 0) {
            $adminUser = Auth::guard('admin')->user();
            $role = $adminUser->role;

            foreach ($role->permissions as $permission) {
                if ($permission->route_name == $action['as']) {
                    $findIt = true;
                }
            }
        }

        if ($findIt === true) {
            return $next($request);
        }

        //@todo it should throw exception
        return $next($request);
    }

}
