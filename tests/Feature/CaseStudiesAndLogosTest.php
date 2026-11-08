<?php

namespace Tests\Feature;

use App\Models\ClientLogo;
use App\Models\CompanySetting;
use App\Models\Portfolio;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CaseStudiesAndLogosTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        CompanySetting::create(['company_name' => 'Test', 'show_portfolio_page' => true]);
    }

    public function test_client_logos_strip_appears_only_with_published_logos(): void
    {
        $this->get('/ar')->assertDontSee(__('frontend.trusted_by', [], 'ar'));

        ClientLogo::create(['name' => 'مطعم الريف', 'logo' => 'client-logos/a.webp', 'is_active' => false]);
        $this->get('/ar')->assertDontSee('مطعم الريف');

        ClientLogo::create(['name' => 'صيدلية الشفاء', 'logo' => 'client-logos/b.webp', 'is_active' => true]);
        $this->get('/ar')->assertSee('يثق بنا')->assertSee('صيدلية الشفاء');
        $this->get('/ar/about')->assertSee('صيدلية الشفاء');
    }

    public function test_admin_can_add_a_client_logo(): void
    {
        Storage::fake('public');

        $this->actingAs(User::factory()->create())->post('/admin/content/client-logos', [
            'name' => 'Client A',
            'logo' => UploadedFile::fake()->createWithContent('logo.png', file_get_contents(public_path('images/og-default.png'))),
            'website_url' => 'https://example.com',
            'is_active' => '1',
        ])->assertSessionHasNoErrors();

        $logo = ClientLogo::firstOrFail();
        Storage::disk('public')->assertExists($logo->logo);
    }

    public function test_portfolio_item_with_case_study_gets_its_own_page(): void
    {
        $plain = Portfolio::create(['title' => 'Website', 'title_ar' => 'موقع', 'image' => 'portfolio/x.jpg', 'is_active' => true]);
        $study = Portfolio::create([
            'title' => 'POS rollout', 'title_ar' => 'تطبيق نظام نقاط البيع', 'image' => 'portfolio/y.jpg', 'is_active' => true,
            'challenge_ar' => 'فواتير يدوية بطيئة', 'solution_ar' => 'نظام نقاط بيع مع مخزون', 'results_ar' => 'تقليل وقت البيع',
        ]);

        $this->get('/ar/portfolio')->assertOk()->assertSee('/ar/portfolio/' . $study->id, false)->assertDontSee('/ar/portfolio/' . $plain->id . '"', false);
        $this->get('/ar/portfolio/' . $study->id)->assertOk()
            ->assertSee('فواتير يدوية بطيئة')->assertSee('نظام نقاط بيع مع مخزون')->assertSee('تقليل وقت البيع');
        $this->get('/ar/portfolio/' . $plain->id)->assertNotFound();
    }

    public function test_admin_portfolio_form_saves_case_study_fields(): void
    {
        Storage::fake('public');
        $admin = User::factory()->create();

        $this->actingAs($admin)->get('/admin/portfolio/create')->assertOk()->assertSee('name="challenge_ar"', false);

        $this->actingAs($admin)->post('/admin/portfolio', [
            'title_en' => 'ERP', 'title_ar' => 'نظام ERP',
            'image' => UploadedFile::fake()->createWithContent('p.png', file_get_contents(public_path('images/og-default.png'))),
            'results_ar' => 'إغلاق الحسابات في يوم بدل أسبوع',
            'is_active' => '1',
        ])->assertSessionHasNoErrors();

        $this->assertTrue(Portfolio::firstOrFail()->isCaseStudy());
    }
}
