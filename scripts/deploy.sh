#!/usr/bin/env bash
# Production deploy for albaseetsoft-ye.com — run from the project root on the server.
# Usage: bash scripts/deploy.sh
set -euo pipefail

echo "▸ Maintenance mode"
php artisan down --retry=15 || true

echo "▸ PHP dependencies (no dev packages, optimized autoloader)"
composer install --no-dev --optimize-autoloader --no-interaction

echo "▸ Front-end assets"
npm ci --no-audit --no-fund
npm run build            # new manifest => cached pages are refreshed automatically

echo "▸ Database"
php artisan migrate --force

echo "▸ Convert uploaded JPG/PNG images to WebP (needs PHP GD; safe to re-run)"
php artisan images:optimize || echo "  (skipped: enable the PHP GD extension to convert images)"

echo "▸ Laravel caches (config, routes, views, events)"
php artisan optimize
php artisan storage:link 2>/dev/null || true

echo "▸ Reload PHP-FPM so OPcache picks up the new code"
if command -v systemctl >/dev/null 2>&1; then
    sudo systemctl reload "php$(php -r 'echo PHP_MAJOR_VERSION.".".PHP_MINOR_VERSION;')-fpm" || true
fi

php artisan up
echo "✓ Deployed"
