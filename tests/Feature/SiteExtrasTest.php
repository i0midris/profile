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

    public function test_how_we_work_line_flows_from_step_one_in_both_languages(): void
    {
        // Dashes travel from x1 to x2; step 1 sits on the right in Arabic and on the left in English.
        $this->get('/ar')->assertSee('<line x1="960" y1="10" x2="40"', false);
        $this->get('/en')->assertSee('<line x1="40" y1="10" x2="960"', false);
    }

    public function test_about_ecosystem_illustration_renders_in_both_languages(): void
    {
        $settings = CompanySetting::first();

        app()->setLocale('ar');
        $ar = view('frontend.partials.about_ecosystem', ['settings' => $settings])->render();
        $this->assertStringContainsString('data-eco', $ar);
        $this->assertSame(6, substr_count($ar, 'class="eco-sat"'));
        $this->assertStringContainsString('بياناتك في أمان', $ar);
        $this->assertStringContainsString('eco-hub-mark', $ar); // no logo uploaded: brand mark fallback

        app()->setLocale('en');
        $settings->logo = 'settings/logo.png';
        $en = view('frontend.partials.about_ecosystem', ['settings' => $settings])->render();
        $this->assertStringContainsString('Safe &amp; secure', $en);
        $this->assertStringContainsString('storage/settings/logo.png', $en);
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
