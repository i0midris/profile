<?php

namespace Tests\Feature;

use App\Models\CompanySetting;
use App\Models\Faq;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class SecurityHardeningTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        CompanySetting::create(['company_name' => 'Test', 'company_name_en' => 'Test', 'company_name_ar' => 'تجربة']);
    }

    public function test_structured_data_cannot_break_out_of_its_script_tag(): void
    {
        $payload = 'POS</script><script>alert(1)</script>';
        Product::create(['slug' => 'pos', 'name_ar' => $payload, 'description_ar' => $payload, 'is_active' => true]);
        Faq::create(['question_ar' => $payload, 'answer_ar' => $payload]);

        foreach (['/ar/products/pos', '/ar/faq'] as $path) {
            $html = $this->get($path)->assertOk()->getContent();
            $this->assertStringNotContainsString('<script>alert(1)</script>', $html, $path);
            $this->assertStringContainsString('</script>', $html, $path); // escaped inside JSON-LD
        }
    }

    public function test_svg_with_script_is_rejected_but_clean_svg_is_accepted(): void
    {
        Storage::fake('public');
        $admin = User::factory()->create();
        $evil = UploadedFile::fake()->createWithContent('logo.svg', '<svg xmlns="http://www.w3.org/2000/svg"><script>alert(1)</script></svg>');
        $handler = UploadedFile::fake()->createWithContent('logo.svg', '<svg xmlns="http://www.w3.org/2000/svg"><rect onload="alert(1)"/></svg>');
        $clean = UploadedFile::fake()->createWithContent('logo.svg', '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 10 10"><rect width="10" height="10" fill="#E4191F"/></svg>');

        $this->actingAs($admin)->post('/admin/content/client-logos', ['name' => 'A', 'logo' => $evil])->assertSessionHasErrors('logo');
        $this->actingAs($admin)->post('/admin/content/client-logos', ['name' => 'B', 'logo' => $handler])->assertSessionHasErrors('logo');
        $this->actingAs($admin)->put('/admin/settings', ['company_name_en' => 'T', 'company_name_ar' => 'ت', 'logo' => $evil])->assertSessionHasErrors('logo');

        $this->actingAs($admin)->post('/admin/content/client-logos', ['name' => 'C', 'logo' => $clean, 'is_active' => '1'])->assertSessionHasNoErrors();
    }
}
