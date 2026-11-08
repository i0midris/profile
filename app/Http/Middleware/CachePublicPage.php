<?php

namespace App\Http\Middleware;

use App\Support\PageCache;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

/**
 * Full-page cache for public pages that are the same for every visitor
 * (home, about, services, ...). The first visit renders the page; later visits
 * get the stored HTML without touching the database. Any edit in the dashboard
 * bumps the content version (see PageCache), so changes show up immediately.
 */
class CachePublicPage
{
    private const TTL_SECONDS = 3600;

    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->isMethod('GET') || !config('site.page_cache', true)) {
            return $next($request);
        }

        $key = PageCache::key('page:' . $request->getRequestUri());

        if (is_string($html = Cache::get($key))) {
            return $this->withCacheHeaders(response($html), 'HIT');
        }

        $response = $next($request);

        $isHtml = str_contains((string) $response->headers->get('Content-Type'), 'text/html');
        if ($response->getStatusCode() === 200 && $isHtml) {
            Cache::put($key, $response->getContent(), self::TTL_SECONDS);
        }

        return $this->withCacheHeaders($response, 'MISS');
    }

    private function withCacheHeaders(Response $response, string $status): Response
    {
        $response->headers->set('Content-Type', 'text/html; charset=UTF-8');
        // Short browser/CDN lifetime: pages are cheap to revalidate and edits show up within minutes.
        $response->headers->set('Cache-Control', 'public, max-age=120');
        $response->headers->set('X-Page-Cache', $status);

        return $response;
    }
}
