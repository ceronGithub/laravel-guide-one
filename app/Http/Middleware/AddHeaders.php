<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Foundation\abort;
use Illuminate\Support\Facades\Redirect;

class AddHeaders
{
    public function handle($request, Closure $next)
    {
        //Validate Referer
        // if(!$this->validateReferer($request)){
        //     abort(403, 'Unauthorized action.');
        // }

        $response = $next($request);

        // security related
        if(env('APP_DEBUG') == false){
            $response->headers->set("X-Frame-Options","deny"); // Anti clickjacking
            $response->headers->set("X-XSS-Protection", "1; mode=block"); // Anti cross site scripting (XSS)
            $response->headers->set("X-Content-Type-Options", "nosniff"); // Reduce exposure to drive-by dl attacks
            $response->headers->set("Content-Security-Policy", "script-src 'self' https://www.google-analytics.com https://www.googletagmanager.com; connect-src 'self'; child-src 'self'; frame-src 'self'; media-src 'self';"); // Reduce risk of XSS, clickjacking, and other stuff
            $response->headers->set("Feature-Policy", "accelerometer 'none'; camera 'none'; geolocation 'none'; gyroscope 'none'; magnetometer 'none'; microphone 'none'; payment 'self' ; usb 'none'; sync-xhr 'self';");
            $response->headers->set("Referrer-Policy", "strict-origin");
            $response->headers->set("Referrer-Policy", "same-origin"); // fix for Livewire's query string to work
        }
        // Don"t cache stuff (we"ll be updating the page frequently)
        $response->headers->set("Cache-Control", "nocache, no-store, max-age=0, must-revalidate");
        $response->headers->set("Pragma", "no-cache");

        // $response->headers->remove("X-Powered-By");
        // $response->headers->set("Expires", "Fri, 01 Jan 1990 00:00:00 GMT");

        //For SSL use only
        $response->headers->set("Strict-Transport-Security", "max-age=31536000; includeSubDomains; preload");

        return $response;
    }

    protected function validateReferer($request){
        if(!$request->is('login')){
            $refererHost = parse_url($request->server('HTTP_REFERER'), PHP_URL_HOST);
            $host = $request->server('HTTP_HOST');

            if($host != $refererHost){
                return false;
            }
        }
        return true;
    }
}
