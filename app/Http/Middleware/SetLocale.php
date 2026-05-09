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
        $locale = $request->segment(1);

        if (in_array($locale, ['en', 'ar'])) {
            App::setLocale($locale);
            session(['locale' => $locale]);
        } elseif (session('locale')) {
            App::setLocale(session('locale'));
        } else {
            // Default to Arabic if no locale set
            App::setLocale('ar');
            session(['locale' => 'ar']);
        }

        return $next($request);
    }
}