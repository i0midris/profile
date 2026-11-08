<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Vite;
use Symfony\Component\HttpFoundation\Response;

/**
 * Adds browser security headers to every response.
 *
 * The CSP allows only this site plus the few services it really uses
 * (Google Fonts, Ahrefs analytics). 'unsafe-inline' / 'unsafe-eval' are still
 * needed because the pages use inline <script>/style attributes and Alpine.js
 * evaluates its x-data expressions; moving to nonces is a later improvement.
 */
class SecurityHeaders
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->headers->set('Permissions-Policy', 'camera=(), microphone=(), geolocation=(), payment=()');
        $response->headers->set('Content-Security-Policy', $this->contentSecurityPolicy());

        return $response;
    }

    private function contentSecurityPolicy(): string
    {
        $devServer = $this->viteDevServerOrigins();

        $directives = [
            'default-src' => ["'self'"],
            'script-src' => ["'self'", "'unsafe-inline'", "'unsafe-eval'", 'https://analytics.ahrefs.com', 'https://www.googletagmanager.com', ...$devServer],
            'style-src' => ["'self'", "'unsafe-inline'", 'https://fonts.googleapis.com', ...$devServer],
            'font-src' => ["'self'", 'data:', 'https://fonts.gstatic.com'],
            'img-src' => ["'self'", 'data:', 'blob:', 'https:'],
            'connect-src' => ["'self'", 'https://analytics.ahrefs.com', 'https://www.googletagmanager.com', 'https://*.google-analytics.com', 'https://*.analytics.google.com', ...$devServer, ...($devServer ? ['ws:'] : [])],
            'frame-src' => ["'self'", 'https://www.google.com', 'https://maps.google.com'],
            'object-src' => ["'none'"],
            'base-uri' => ["'self'"],
            'form-action' => ["'self'"],
            'frame-ancestors' => ["'self'"],
        ];

        return collect($directives)
            ->map(fn (array $sources, string $directive) => $directive . ' ' . implode(' ', array_unique($sources)))
            ->implode('; ');
    }

    /**
     * When `npm run dev` is running on a developer machine, allow the Vite dev server.
     * CSP cannot express IPv6 hosts such as http://[::1]:5173 (which Vite often uses),
     * so plain http: is allowed in that case. Never applies in production.
     */
    private function viteDevServerOrigins(): array
    {
        return app()->isLocal() && Vite::isRunningHot() ? ['http:'] : [];
    }
}
