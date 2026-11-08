<?php

namespace Tests\Feature;

use App\Models\CompanySetting;
use App\Models\ContactMessage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class ContactFormTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Mail::fake();
        CompanySetting::create(['company_name' => 'Test', 'email' => 'info@example.com']);
    }

    private function submit(array $fields)
    {
        return $this->from('/ar/contact')->post('/ar/contact', array_merge([
            'name' => 'أحمد',
            'message' => 'أرغب في الاستفسار عن نظام نقاط البيع',
            'form_started_at' => now()->subSeconds(10)->timestamp,
        ], $fields));
    }

    public function test_phone_only_is_accepted(): void
    {
        $this->submit(['phone' => '777 123 456'])->assertSessionHasNoErrors();

        $this->assertDatabaseHas('contact_messages', ['phone' => '777 123 456', 'email' => null]);
    }

    public function test_email_only_is_accepted(): void
    {
        $this->submit(['email' => 'client@example.com'])->assertSessionHasNoErrors();

        $this->assertDatabaseHas('contact_messages', ['email' => 'client@example.com', 'phone' => null]);
    }

    public function test_phone_or_email_is_required(): void
    {
        $this->submit([])->assertSessionHasErrors(['phone', 'email']);

        $this->assertSame(0, ContactMessage::count());
    }

    public function test_invalid_phone_is_rejected(): void
    {
        $this->submit(['phone' => 'abc'])->assertSessionHasErrors(['phone']);
    }

    public function test_contact_page_shows_yemeni_phone_example(): void
    {
        $this->get('/ar/contact')->assertOk()->assertSee('+967 777 123 456');
    }
}
