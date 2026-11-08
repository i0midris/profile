<?php

namespace App\Console\Commands;

use App\Models\ClientLogo;
use App\Models\CompanySetting;
use App\Models\Faq;
use App\Models\Portfolio;
use App\Models\Post;
use App\Models\Product;
use App\Models\TeamMember;
use App\Models\Testimonial;
use App\Support\PageCache;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

/**
 * Fills a DEVELOPMENT copy of the site with sample content so every section can be
 * previewed (products, case studies, client sectors, team, testimonials, FAQ, blog,
 * map & hours). Refuses to run in production. `--remove` deletes exactly what it added
 * and restores the previous settings.
 */
class PreviewContent extends Command
{
    protected $signature = 'preview:content {--remove : Remove the preview content and restore settings}';

    protected $description = 'Add (or remove) sample content to preview the finished site locally';

    private const STATE_FILE = 'preview-content.json';

    public function handle(): int
    {
        if (app()->environment('production')) {
            $this->error('Preview content is for development copies only — refusing to run in production.');

            return self::FAILURE;
        }

        return $this->option('remove') ? $this->remove() : $this->add();
    }

    private function add(): int
    {
        if (Storage::disk('local')->exists(self::STATE_FILE)) {
            $this->warn('Preview content is already installed. Run with --remove first.');

            return self::FAILURE;
        }

        $data = require database_path('preview/content.php');
        $state = ['created' => [], 'files' => [], 'settings' => []];
        $copy = function (string $file, string $dir) use (&$state): string {
            $path = "preview/{$dir}/{$file}";
            Storage::disk('public')->put($path, file_get_contents(database_path("preview/images/{$file}")));
            $state['files'][] = $path;

            return $path;
        };
        $remember = function ($model) use (&$state) {
            $state['created'][] = [get_class($model), $model->getKey()];
        };

        // Settings (previous values are restored by --remove)
        $settings = CompanySetting::first() ?? CompanySetting::create(['company_name' => config('app.name')]);
        $state['settings'] = array_intersect_key($settings->getAttributes(), $data['settings']);
        $settings->update($data['settings']);

        foreach ($data['products'] as $i => $p) {
            $image = $p['image'];
            unset($p['image']);
            $remember(Product::create($p + [
                'cover_image' => $copy("product-{$image}-cover.webp", 'products'),
                'screenshots' => [$copy("product-{$image}-1.webp", 'products'), $copy("product-{$image}-2.webp", 'products')],
                'sort_order' => $i + 1,
                'is_active' => true,
            ]));
        }

        foreach ($data['client_logos'] as $i => $c) {
            $remember(ClientLogo::create(['name' => $c['name'], 'logo' => $copy($c['image'], 'clients'), 'sort_order' => $i + 1, 'is_active' => true]));
        }

        foreach ($data['case_studies'] as $i => $c) {
            $image = $c['image'];
            unset($c['image']);
            $c += ['description' => $c['description_ar'], 'client_name' => $c['client_name_ar'], 'category' => $c['category_ar']];
            $remember(Portfolio::create($c + ['image' => $copy($image, 'portfolio'), 'sort_order' => $i + 1, 'is_featured' => true, 'is_active' => true]));
        }

        foreach ($data['testimonials'] as $i => $t) {
            $remember(Testimonial::create($t + [
                'client_name' => $t['client_name_ar'], 'client_position' => $t['client_position_ar'], 'content' => $t['content_ar'],
                'rating' => 5, 'sort_order' => $i + 1, 'is_featured' => true, 'is_active' => true,
            ]));
        }

        foreach ($data['team'] as $i => $m) {
            $remember(TeamMember::create($m + ['name' => $m['name_ar'], 'position' => $m['position_ar'], 'bio' => $m['bio_ar'], 'sort_order' => $i + 1, 'is_active' => true]));
        }

        foreach ($data['faqs'] as $i => $f) {
            $f['image'] = !empty($f['image']) ? $copy($f['image'], 'faq') : null;
            $remember(Faq::create($f + ['sort_order' => $i + 1, 'show_on_home' => $i < 3, 'is_active' => true]));
        }

        foreach ($data['posts'] as $p) {
            $image = $p['image'];
            $days = $p['days_ago'];
            unset($p['image'], $p['days_ago']);
            $remember(Post::create($p + ['cover_image' => $copy($image, 'blog'), 'published_at' => now()->subDays($days), 'is_active' => true]));
        }

        Storage::disk('local')->put(self::STATE_FILE, json_encode($state, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        PageCache::flush();

        $this->info('Preview content added: ' . count($state['created']) . ' items, ' . count($state['files']) . ' images.');
        $this->line('Remove it with: php artisan preview:content --remove');

        return self::SUCCESS;
    }

    private function remove(): int
    {
        if (!Storage::disk('local')->exists(self::STATE_FILE)) {
            $this->warn('No preview content is installed.');

            return self::SUCCESS;
        }

        $state = json_decode(Storage::disk('local')->get(self::STATE_FILE), true);

        foreach (array_reverse($state['created']) as [$class, $id]) {
            $class::query()->whereKey($id)->first()?->delete();
        }
        Storage::disk('public')->delete($state['files']);
        CompanySetting::first()?->update($state['settings']);

        Storage::disk('local')->delete(self::STATE_FILE);
        PageCache::flush();

        $this->info('Preview content removed (' . count($state['created']) . ' items) and settings restored.');

        return self::SUCCESS;
    }
}
