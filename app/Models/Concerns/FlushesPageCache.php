<?php

namespace App\Models\Concerns;

use App\Support\PageCache;

/**
 * Content edited from the dashboard invalidates the cached public pages.
 */
trait FlushesPageCache
{
    public static function bootFlushesPageCache(): void
    {
        static::saved(fn () => PageCache::flush());
        static::deleted(fn () => PageCache::flush());
    }
}
