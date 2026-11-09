<?php

namespace Tests\Feature;

use App\Models\CompanySetting;
use App\Models\DemoRequest;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class DemoRequestTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Mail::fake();
        CompanySetting::create(['company_name' => 'Test', 'email' => 'info@example.com', 'whatsapp' => '00967-777335118']);
    }

    private function submit(array $fields)
    {
        return $this->from('/ar/demo')->post('/ar/demo', array_merge([
            'name' => 'سالم',
            'phone' => '777 123 456',
            'preferred_contact' => 'whatsapp',
            'form_started_at' => now()->subSeconds(10)->timestamp,
        ], $fields));
    }

    public function test_demo_page_preselects_product_from_link(): void
    {
        Product::create(['slug' => 'pos', 'name_ar' => 'نظام نقاط البيع', 'description_ar' => 'x', 'is_active' => true]);

        $this->get('/ar/demo?product=pos')->assertOk()
            ->assertSee('<option value="نظام نقاط البيع" selected', false)
            ->assertSee('name="_token"', false);
    }

    public function test_sector_link_fills_the_form_before_products_are_published(): void
    {
        $html = $this->get('/ar/demo?sector=restaurant&product=pos-system')->assertOk()
            ->assertSee('<option value="restaurant" selected', false)
            ->assertSee('value="نقاط البيع"', false)
            ->assertSee('جهّزنا طلبك')
            ->getContent();

        $this->assertStringContainsString('نعمل في مجال المطاعم والكافيهات، ونرغب بتجربة نقاط البيع.</textarea>', $html);
    }

    public function test_product_and_sector_prefill_uses_published_product_name(): void
    {
        Product::create(['slug' => 'pos-system', 'name_ar' => 'نظام نقاط البيع POS', 'description_ar' => 'x', 'is_active' => true]);

        $this->get('/ar/demo?product=pos-system&sector=pharmacy')->assertOk()
            ->assertSee('<option value="نظام نقاط البيع POS" selected', false)
            ->assertSee('<option value="pharmacy" selected', false)
            ->assertSee('نعمل في مجال الصيدليات، ونرغب بتجربة نظام نقاط البيع POS.</textarea>', false);

        $this->get('/en/demo?sector=school')->assertOk()
            ->assertSee('Our business: Schools &amp; organisations. We would like to know which system suits us.</textarea>', false);
    }

    public function test_unknown_or_missing_values_leave_the_form_empty(): void
    {
        $this->get('/ar/demo?sector=<script>&product=nope')->assertOk()
            ->assertDontSee('جهّزنا طلبك')
            ->assertDontSee('<option value="restaurant" selected', false)
            ->assertSee('></textarea>', false);

        $this->get('/ar/demo')->assertOk()->assertDontSee('جهّزنا طلبك');
    }

    public function test_sector_is_saved_and_shown_to_admin(): void
    {
        $this->submit(['sector' => 'warehouse', 'message' => 'نعمل في مجال المستودعات'])->assertSessionHasNoErrors();
        $request = DemoRequest::firstOrFail();
        $this->assertSame('warehouse', $request->sector);

        $this->submit(['sector' => 'casino'])->assertSessionHasErrors('sector');

        $this->actingAs(User::factory()->create())->get("/admin/content/demo-requests/{$request->id}/edit")
            ->assertOk()->assertSee('المستودعات والمخازن');
    }

    public function test_sector_cards_link_to_a_prefilled_demo_form(): void
    {
        $html = str_replace('&amp;', '&', $this->get('/ar')->assertOk()->getContent());

        $this->assertStringContainsString('/ar/demo?sector=restaurant&product=pos-system', $html);
        $this->assertStringContainsString('/ar/demo?sector=distribution&product=sales-rep-app', $html);
        $this->assertStringContainsString('اطلب عرضاً لقطاعك', $html);
    }

    public function test_product_page_passes_the_sector_on_to_the_demo_form(): void
    {
        Product::create(['slug' => 'pos-system', 'name_ar' => 'نقاط البيع', 'description_ar' => 'x', 'is_active' => true]);

        $html = str_replace('&amp;', '&', $this->get('/ar/products/pos-system?sector=pharmacy')->assertOk()->getContent());
        $this->assertStringContainsString('/ar/demo?product=pos-system&sector=pharmacy', $html);
        $this->assertMatchesRegularExpression('#<link rel="canonical" href="[^"?]+/ar/products/pos-system">#', $html);

        $html = str_replace('&amp;', '&', $this->get('/ar/products/pos-system?sector=bad')->getContent());
        $this->assertStringContainsString('/ar/demo?product=pos-system"', $html);
    }

    public function test_forms_remember_only_contact_fields_on_this_device(): void
    {
        foreach (['/ar/demo', '/ar/contact'] as $path) {
            $this->get($path)->assertOk()
                ->assertSee('data-remember-form', false)
                ->assertSee('data-remembered-notice', false)
                ->assertSee('data-remember="name"', false)
                ->assertSee('data-remember="phone"', false);
        }

        // The contact form's hidden "company" field is a spam trap: it must never be filled in.
        $contact = $this->get('/ar/contact')->getContent();
        $this->assertDoesNotMatchRegularExpression('/name="company"[^>]*data-remember/', $contact);
        $this->assertStringContainsString('data-remember="company"', $this->get('/ar/demo')->getContent());
    }

    public function test_visitor_can_request_a_demo(): void
    {
        $this->submit(['company' => 'مطعم الريف', 'product' => 'نظام نقاط البيع'])
            ->assertSessionHasNoErrors()
            ->assertSessionHas('lead', 'demo_request');

        $this->assertDatabaseHas('demo_requests', ['name' => 'سالم', 'company' => 'مطعم الريف', 'is_handled' => false, 'locale' => 'ar']);
    }

    public function test_phone_is_required_and_bots_are_ignored(): void
    {
        $this->submit(['phone' => ''])->assertSessionHasErrors('phone');
        $this->submit(['website' => 'http://spam.example'])->assertSessionHas('success');
        $this->submit(['form_started_at' => now()->timestamp])->assertSessionHasErrors('demo');

        $this->assertSame(0, DemoRequest::count());
    }

    public function test_admin_sees_requests_and_marks_them_handled(): void
    {
        $request = DemoRequest::create(['name' => 'سالم', 'phone' => '777123456', 'preferred_contact' => 'call']);
        $admin = User::factory()->create();

        $this->actingAs($admin)->get('/admin/content/demo-requests')->assertOk()
            ->assertSee('سالم')->assertSee('+967 777 123 456')->assertSee('wa.me/967777123456', false);
        $this->actingAs($admin)->get('/admin/content/demo-requests/create')->assertNotFound();
        $this->actingAs($admin)->get("/admin/content/demo-requests/{$request->id}/edit")->assertOk()->assertSee('tel:+967777123456', false);

        $this->actingAs($admin)->put("/admin/content/demo-requests/{$request->id}", ['is_handled' => '1'])->assertSessionHasNoErrors();
        $this->assertTrue($request->fresh()->is_handled);
    }

    public function test_floating_whatsapp_button_hours_and_map(): void
    {
        CompanySetting::first()->update([
            'working_hours_ar' => 'السبت – الخميس: 8 – 4',
            'map_embed_url' => 'https://www.google.com/maps/embed?pb=abc',
        ]);

        $this->get('/ar')->assertSee('id="floating-whatsapp"', false)->assertSee('wa.me/967777335118?text=', false);
        $this->get('/ar/contact')->assertSee('السبت – الخميس: 8 – 4')->assertSee('src="https://www.google.com/maps/embed?pb=abc"', false);
    }

    public function test_settings_reject_non_google_map_links(): void
    {
        $this->actingAs(User::factory()->create())->put('/admin/settings', [
            'company_name_en' => 'Test', 'company_name_ar' => 'تجربة',
            'map_embed_url' => 'https://evil.example/maps/embed',
        ])->assertSessionHasErrors('map_embed_url');
    }
}
