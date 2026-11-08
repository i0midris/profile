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
