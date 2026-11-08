<?php

namespace Tests\Feature;

use App\Models\CompanySetting;
use App\Models\Service;
use App\Models\User;
use App\Support\ImageUploader;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ImageUploadTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        if (!ImageUploader::supported()) {
            $this->markTestSkipped('GD with WebP support is required (run: php -d extension=gd artisan test).');
        }

        Storage::fake('public');
        CompanySetting::create(['company_name' => 'Test', 'company_name_en' => 'Test', 'company_name_ar' => 'تجربة']);
    }

    private function jpeg(int $width, int $height): UploadedFile
    {
        $img = imagecreatetruecolor($width, $height);
        imagefill($img, 0, 0, imagecolorallocate($img, 26, 46, 109));
        ob_start();
        imagejpeg($img, null, 95);

        return UploadedFile::fake()->createWithContent('photo.jpg', ob_get_clean());
    }

    public function test_uploaded_logo_is_stored_as_webp_and_resized(): void
    {
        $this->actingAs(User::factory()->create())->put('/admin/settings', [
            'company_name_en' => 'Test',
            'company_name_ar' => 'تجربة',
            'logo' => $this->jpeg(1200, 600),
        ])->assertSessionHasNoErrors();

        $path = CompanySetting::first()->logo;
        $this->assertStringEndsWith('.webp', $path);
        [$width, $height, $type] = getimagesizefromstring(Storage::disk('public')->get($path));
        $this->assertSame(IMAGETYPE_WEBP, $type);
        $this->assertSame([600, 300], [$width, $height]);
    }

    public function test_command_converts_existing_jpg_images(): void
    {
        Storage::disk('public')->put('services/old.jpg', $this->jpeg(2000, 1000)->getContent());
        $service = Service::create(['title' => 'POS', 'description' => 'x', 'is_active' => true, 'order' => 1, 'image' => 'services/old.jpg']);

        $this->artisan('images:optimize')->assertSuccessful();

        $service->refresh();
        $this->assertStringEndsWith('.webp', $service->image);
        Storage::disk('public')->assertExists($service->image);
        Storage::disk('public')->assertExists('services/old.jpg'); // originals kept unless --delete-originals
        [$width] = getimagesizefromstring(Storage::disk('public')->get($service->image));
        $this->assertSame(1200, $width);
    }
}
