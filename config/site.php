<?php

return [
    /*
    | Full-page cache for public read-only pages (see App\Http\Middleware\CachePublicPage).
    | On by default in production only, so template edits show up immediately while developing.
    */
    'page_cache' => (bool) env('PAGE_CACHE', env('APP_ENV') === 'production'),
];
