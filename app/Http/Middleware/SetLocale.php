<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // The locale always comes from the URL (/ar/..., /en/...). It is not stored in the
        // session, so read-only pages can be served without creating a session at all.
        $locale = $request->segment(1);

        App::setLocale(in_array($locale, ['en', 'ar'], true) ? $locale : 'ar');

        return $next($request);
    }
}