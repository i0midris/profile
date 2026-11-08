<?php

namespace App\Support;

use Illuminate\Support\Facades\Cache;

/**
 * Small helper around a "content version" number.
 *
 * Every cached public page and the cached company settings include the current
 * version in their key. Saving or deleting any content from the dashboard bumps
 * the version, so visitors see the change immediately without clearing the
 * whole cache by hand.
 */
class PageCache
{
    private const VERSION_KEY = 'site:content-version';

    public static function version(): int
    {
        return (int) Cache::rememberForever(self::VERSION_KEY, fn () => 1);
    }

    public static function flush(): void
    {
        Cache::forever(self::VERSION_KEY, self::version() + 1);
        app()->forgetInstance('site.settings');
    }

    public static function key(string $name): string
    {
        return 'site:v' . self::version() . ':' . self::buildId() . ':' . $name;
    }

    /**
     * Changes on every deploy (`npm run build` rewrites the manifest), so pages cached
     * with old templates/assets are never served after an update.
     */
    private static function buildId(): string
    {
        $manifest = public_path('build/manifest.json');

        return is_file($manifest) ? (string) filemtime($manifest) : 'dev';
    }
}
