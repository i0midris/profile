<?php

namespace Tests\Feature;

use App\Models\CompanySetting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminSettingsTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_upload_social_share_image(): void
    {
        Storage::fake('public');
        CompanySetting::create(['company_name' => 'Test', 'company_name_en' => 'Test', 'company_name_ar' => 'تجربة']);
        $admin = User::factory()->create();

        $this->actingAs($admin)->get('/admin/settings')->assertOk()->assertSee('name="og_image"', false);

        $this->actingAs($admin)->put('/admin/settings', [
            'company_name_en' => 'Test',
            'company_name_ar' => 'تجربة',
            'og_image' => UploadedFile::fake()->createWithContent('share.png', file_get_contents(public_path('images/og-default.png'))),
        ])->assertSessionHasNoErrors();

        $settings = CompanySetting::first();
        $this->assertNotNull($settings->og_image);
        Storage::disk('public')->assertExists($settings->og_image);
        $this->assertStringContainsString('storage/' . $settings->og_image, $settings->og_image_url);
    }
}
