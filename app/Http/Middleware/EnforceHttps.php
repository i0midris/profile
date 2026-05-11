<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnforceHttps
{
    /**
     * Redirect insecure traffic to HTTPS in production.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $shouldForceHttps = app()->environment('production') || config('app.force_https');

        if ($shouldForceHttps && ! $request->isSecure()) {
            $target = 'https://'.$request->getHttpHost().$request->getRequestUri();

            return redirect($target, 301);
        }

        return $next($request);
    }
}
