<?php

namespace Tests\Feature;

use App\Models\CompanySetting;
use App\Models\DemoRequest;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class AnalyticsTest extends TestCase
{
    use RefreshDatabase;

    public function test_no_google_code_until_configured(): void
    {
        CompanySetting::create(['company_name' => 'Test']);

        $this->get('/ar')->assertDontSee('googletagmanager.com/gtag', false)->assertDontSee('google-site-verification', false);
    }

    public function test_admin_pastes_google_snippets_and_site_outputs_them(): void
    {
        CompanySetting::create(['company_name' => 'Test', 'company_name_en' => 'Test', 'company_name_ar' => 'تجربة']);

        $this->actingAs(User::factory()->create())->put('/admin/settings', [
            'company_name_en' => 'Test', 'company_name_ar' => 'تجربة',
            'google_site_verification' => '<meta name="google-site-verification" content="AbC_123-xyz" />',
            'ga_measurement_id' => "gtag('config', 'g-ab12cd34ef');",
        ])->assertSessionHasNoErrors();

        $settings = CompanySetting::first();
        $this->assertSame('AbC_123-xyz', $settings->google_site_verification);
        $this->assertSame('G-AB12CD34EF', $settings->ga_measurement_id);

        $response = $this->get('/ar')
            ->assertSee('<meta name="google-site-verification" content="AbC_123-xyz">', false)
            ->assertSee('googletagmanager.com/gtag/js?id=G-AB12CD34EF', false);
        $this->assertStringContainsString('https://www.googletagmanager.com', $response->headers->get('Content-Security-Policy'));
    }

    public function test_successful_form_marks_a_lead_for_analytics(): void
    {
        Mail::fake();
        CompanySetting::create(['company_name' => 'Test']);

        $this->from('/ar/demo')->post('/ar/demo', [
            'name' => 'سالم', 'phone' => '777123456', 'preferred_contact' => 'call',
            'form_started_at' => now()->subSeconds(10)->timestamp,
        ]);

        $this->get('/ar/demo')->assertSee('<meta name="lead-event" content="demo_request">', false);
        $this->get('/ar/demo')->assertDontSee('lead-event', false); // flash: reported once
    }

    public function test_dashboard_shows_leads_this_month(): void
    {
        CompanySetting::create(['company_name' => 'Test']);
        DemoRequest::create(['name' => 'a', 'phone' => '777123456', 'preferred_contact' => 'call']);
        DemoRequest::create(['name' => 'b', 'phone' => '777123457', 'preferred_contact' => 'call', 'is_handled' => true]);

        $this->actingAs(User::factory()->create())->get('/admin/dashboard')->assertOk()
            ->assertSee(__('admin.leads_this_month'))
            ->assertViewHas('stats', fn ($stats) => $stats['demo_requests_month'] === 2 && $stats['demo_requests_pending'] === 1);
    }
}
