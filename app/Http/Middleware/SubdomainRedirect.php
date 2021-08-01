<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Artisan;

class SubdomainRedirect
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */

    function handle($request, Closure $next)
    {
        $httpHost = $request->getHttpHost();
        $requestURI = $request->getRequestUri();

        Artisan::call('view:clear');
        if ($httpHost === APP_ADMIN_DOMAIN) {

            if ($requestURI === "/") {
                return redirect()->route('admin-dashboard');
            }

            if ($requestURI === "/login") {
                return redirect()->route('admin-login-form');
            }
        }
        return $next($request);
    }
}
