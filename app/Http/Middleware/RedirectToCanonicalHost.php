<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Sends www.example.com/... to example.com/... with a permanent (301) redirect,
 * so search engines index a single version of every page.
 */
class RedirectToCanonicalHost
{
    public function handle(Request $request, Closure $next): Response
    {
        $host = $request->getHost();

        if (str_starts_with($host, 'www.')) {
            $port = $request->getPort();
            $portSuffix = in_array($port, [80, 443], true) ? '' : ':' . $port;
            $scheme = app()->environment('production') ? 'https' : $request->getScheme();

            return redirect()->to(
                $scheme . '://' . substr($host, 4) . $portSuffix . $request->getRequestUri(),
                301
            );
        }

        return $next($request);
    }
}
