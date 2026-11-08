<?php

namespace Tests\Feature;

use App\Models\CompanySetting;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SiteExtrasTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        CompanySetting::create(['company_name' => 'Test', 'whatsapp' => '777335118']);
    }

    public function test_home_has_marquee_system_finder_and_polish(): void
    {
        $this->get('/ar')->assertOk()
            ->assertSee('systems-marquee', false)
            ->assertSee('id="system-finder"', false)
            ->assertSee('اختر النظام المناسب لعملك')
            ->assertSee('id="back-to-top"', false)
            ->assertSee('id="scroll-progress-bar"', false)
            ->assertSee('class="skip-link"', false)
            ->assertSee('<meta name="theme-color"', false)
            ->assertSee('site.webmanifest', false);
    }

    public function test_finder_links_to_published_products_and_demo(): void
    {
        Product::create(['slug' => 'pos-system', 'name_ar' => 'نظام نقاط البيع', 'description_ar' => 'x', 'is_active' => true]);

        $html = $this->get('/ar')->getContent();
        $this->assertStringContainsString('demo?product=erp-accounting', str_replace('\\/', '/', $html));
        $this->assertStringContainsString('products/pos-system', str_replace('\\/', '/', $html));
        $this->assertStringContainsString('wa.me/967777335118', str_replace('\\/', '/', $html));
    }

    public function test_web_manifest_is_valid_json_with_icons(): void
    {
        $manifest = json_decode(file_get_contents(public_path('site.webmanifest')), true);

        $this->assertSame('rtl', $manifest['dir']);
        $this->assertCount(2, $manifest['icons']);
        foreach ($manifest['icons'] as $icon) {
            $this->assertFileExists(public_path(ltrim($icon['src'], '/')));
        }
    }
}
