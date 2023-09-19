<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Exceptions\UnauthorizedException;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        $authGuard = Auth::guard($guard);


        if ($authGuard->check()) {
            return redirect(RouteServiceProvider::HOME);
        }

         return $next($request);

        // return redirect()->route('store.index');

        // return $next($request);
    }
}
