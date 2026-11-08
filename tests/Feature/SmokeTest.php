<?php

namespace Tests\Feature;

use App\Admin\ContentRegistry;
use App\Models\CompanySetting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Tests\TestCase;

/**
 * Crawls the whole site with every section filled (preview content): every public page
 * in both languages must load, and every internal link and image on those pages must
 * resolve. Then every dashboard screen must render.
 */
class SmokeTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
        Storage::fake('local');
        CompanySetting::create([
            'company_name' => 'Test', 'company_name_ar' => 'البسيط سوفت', 'company_name_en' => 'Albaseet Soft',
            'email' => 'info@example.com', 'phone' => '777335118', 'whatsapp' => '777335118',
            'show_jobs_page' => true,
        ]);
        $this->artisan('preview:content')->assertSuccessful();
    }

    public function test_every_public_page_and_internal_link_resolves(): void
    {
        $queue = ['/ar', '/en', '/sitemap.xml', '/ar/contact', '/ar/demo', '/ar/faq', '/ar/jobs', '/en/faq'];
        $seen = [];
        $broken = [];
        $assets = [];

        while ($queue && count($seen) < 300) {
            $path = array_shift($queue);
            if (isset($seen[$path])) {
                continue;
            }
            $seen[$path] = true;

            $response = $this->get($path, ['Accept-Language' => 'ar']);
            $status = $response->getStatusCode();
            if ($status >= 400) {
                $broken[] = "{$status} {$path}";
                continue;
            }
            if ($status >= 300) {
                $queue[] = parse_url($response->headers->get('Location'), PHP_URL_PATH);
                continue;
            }

            $html = (string) $response->getContent();
            preg_match_all('#(?:href|src)="(https?://localhost[^"]*|/[^"/][^"]*)"#', $html, $m);
            foreach (array_unique($m[1]) as $url) {
                $urlPath = parse_url(html_entity_decode($url), PHP_URL_PATH) ?: '/';
                if (Str::startsWith($urlPath, ['/build/', '/admin', '/@vite', '/resources/'])) {
                    continue;
                }
                if (Str::startsWith($urlPath, ['/storage/', '/images/', '/favicon', '/site.webmanifest'])) {
                    $assets[$urlPath] = $path;
                    continue;
                }
                if (!isset($seen[$urlPath])) {
                    $queue[] = $urlPath;
                }
            }
        }

        foreach ($assets as $asset => $foundOn) {
            $exists = Str::startsWith($asset, '/storage/')
                ? Storage::disk('public')->exists(Str::after($asset, '/storage/'))
                : file_exists(public_path(ltrim($asset, '/')));
            if (!$exists) {
                $broken[] = "missing asset {$asset} (on {$foundOn})";
            }
        }

        $this->assertGreaterThan(60, count($seen), 'The crawl should reach the whole site.');
        $this->assertSame([], $broken, "Broken pages/links:\n" . implode("\n", $broken));
    }

    public function test_every_dashboard_screen_renders(): void
    {
        $this->actingAs(User::factory()->create());

        $paths = ['/admin/dashboard', '/admin/settings', '/admin/hero', '/admin/about', '/admin/services', '/admin/services/create',
            '/admin/team', '/admin/team/create', '/admin/jobs', '/admin/jobs/create', '/admin/job-applications', '/admin/portfolio',
            '/admin/portfolio/create', '/admin/testimonials', '/admin/testimonials/create', '/admin/messages'];

        foreach (ContentRegistry::all() as $key => $definition) {
            $paths[] = "/admin/content/{$key}";
            if (empty($definition['readonly'])) {
                $paths[] = "/admin/content/{$key}/create";
            }
            if ($first = $definition['model']::query()->first()) {
                $paths[] = "/admin/content/{$key}/{$first->getKey()}/edit";
            }
        }
        foreach (['portfolio', 'team', 'testimonials'] as $legacy) {
            $model = ['portfolio' => \App\Models\Portfolio::class, 'team' => \App\Models\TeamMember::class, 'testimonials' => \App\Models\Testimonial::class][$legacy];
            if ($first = $model::query()->first()) {
                $paths[] = "/admin/{$legacy}/{$first->getKey()}/edit";
            }
        }

        $failed = [];
        foreach ($paths as $path) {
            $status = $this->get($path)->getStatusCode();
            if ($status !== 200) {
                $failed[] = "{$status} {$path}";
            }
        }

        $this->assertSame([], $failed, "Dashboard screens failing:\n" . implode("\n", $failed));
    }
}
