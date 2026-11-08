<?php

namespace Tests\Feature;

use App\Models\CompanySetting;
use App\Models\Service;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SeoTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        CompanySetting::create(['company_name' => 'البسيط سوفت', 'company_name_ar' => 'البسيط سوفت', 'company_name_en' => 'Albaseet Soft']);
    }

    public function test_pages_have_og_image_and_unique_titles(): void
    {
        $titles = [];
        foreach (['/ar', '/ar/about', '/ar/services', '/ar/contact'] as $path) {
            $html = $this->get($path)->assertOk()->getContent();

            $this->assertStringContainsString('<meta property="og:image" content="' . asset('images/og-default.png') . '">', $html);
            $this->assertStringContainsString('<meta name="twitter:image"', $html);
            $this->assertStringContainsString('content="ar_YE"', $html);
            preg_match('#<title>(.*?)</title>#s', $html, $m);
            $titles[] = trim($m[1]);
        }

        $this->assertCount(4, array_unique($titles), 'Each page must have its own title.');
        $this->assertStringContainsString('ERP', $titles[0], 'Home title should contain keywords.');
    }

    public function test_hreflang_x_default_points_to_language_neutral_url(): void
    {
        $html = $this->get('/ar/services')->getContent();

        $this->assertStringContainsString('hreflang="ar" href="' . url('/ar/services') . '"', $html);
        $this->assertStringContainsString('hreflang="en" href="' . url('/en/services') . '"', $html);
        $this->assertStringContainsString('hreflang="x-default" href="' . url('/services') . '"', $html);
    }

    public function test_neutral_urls_redirect_by_browser_language(): void
    {
        $this->get('/services', ['Accept-Language' => ''])->assertRedirect('/ar/services');
        $this->get('/services', ['Accept-Language' => 'fr-FR,fr;q=0.9'])->assertRedirect('/ar/services');
        $this->get('/services', ['Accept-Language' => 'en-US,en;q=0.9'])->assertRedirect('/en/services');
        $this->get('/', ['Accept-Language' => 'ar-YE,ar;q=0.9'])->assertRedirect('/ar');
    }

    public function test_sitemap_uses_real_last_modified_dates(): void
    {
        $this->travelTo('2026-01-15 10:00:00');
        Service::create(['title' => 'POS', 'description' => 'x', 'is_active' => true, 'order' => 1]);
        $this->travelBack();

        $xml = $this->get('/sitemap.xml')->assertOk()->getContent();

        $this->assertMatchesRegularExpression('#<loc>' . preg_quote(url('/ar/services'), '#') . '</loc>\s*<lastmod>2026-01-15T10:00:00#', $xml);
        $this->assertStringContainsString('hreflang="x-default" href="' . url('/services') . '"', $xml);
    }
}
