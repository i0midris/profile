<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        RateLimiter::for('contact-form', function (Request $request) {
            return Limit::perMinute(3)
                ->by($request->ip())
                ->response(function (Request $request, array $headers) {
                    return back()
                        ->withErrors(['contact' => __('frontend.contact_rate_limited')])
                        ->withInput()
                        ->withHeaders($headers);
                });
        });

        RateLimiter::for('admin-login', function (Request $request) {
            $email = Str::lower((string) $request->input('email', ''));
            $key = ($email !== '' ? $email : 'unknown') . '|' . $request->ip();

            return Limit::perMinute(5)
                ->by($key)
                ->response(function (Request $request, array $headers) {
                    return back()
                        ->withErrors(['email' => 'Too many login attempts. Please try again in a minute.'])
                        ->onlyInput('email')
                        ->withHeaders($headers);
                });
        });
    }
}
