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

    public function test_home_has_marquee_and_polish(): void
    {
        $this->get('/ar')->assertOk()
            ->assertSee('systems-marquee', false)
            ->assertSee('id="back-to-top"', false)
            ->assertSee('id="scroll-progress-bar"', false)
            ->assertSee('class="skip-link"', false)
            ->assertSee('<meta name="theme-color"', false)
            ->assertSee('site.webmanifest', false);
    }

    public function test_system_finder_was_removed(): void
    {
        Product::create(['slug' => 'pos-system', 'name_ar' => 'نظام نقاط البيع', 'name_en' => 'POS', 'description_ar' => 'x', 'description_en' => 'x', 'is_active' => true]);

        foreach (['/ar', '/en', '/ar/products'] as $path) {
            $this->get($path)->assertOk()
                ->assertDontSee('id="system-finder"', false)
                ->assertDontSee('finder-option', false);
        }
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
