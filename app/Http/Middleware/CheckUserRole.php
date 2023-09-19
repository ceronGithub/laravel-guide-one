<?php

namespace App\Http\Middleware;

use Closure;

class CheckUserRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $permitted_roles)
    {
        $roles = explode('|', $permitted_roles);
        foreach($roles as $role){
            if(strtolower($request->user()->role->name) === strtolower($role)){
                return $next($request);
            }

        }
        abort(403);
    }
}
