<?php

namespace Tests\Feature;

use App\Models\CompanySetting;
use App\Models\Faq;
use App\Models\Post;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PreviewContentTest extends TestCase
{
    use RefreshDatabase;

    public function test_preview_content_can_be_added_and_removed_completely(): void
    {
        Storage::fake('public');
        Storage::fake('local');
        CompanySetting::create(['company_name' => 'Test', 'show_team_page' => false]);

        $this->artisan('preview:content')->assertSuccessful();

        $this->assertSame(5, Product::count());
        $this->assertSame(3, Post::count());
        $this->assertSame(6, Faq::count());
        $this->assertTrue(CompanySetting::first()->show_team_page);
        $this->get('/ar/products/pos-system')->assertOk();
        $this->get('/ar/blog/working-offline')->assertOk();
        $this->get('/ar')->assertSee('يثق بنا')->assertSee('الأسئلة الشائعة');

        $this->artisan('preview:content')->assertFailed(); // not twice

        $this->artisan('preview:content --remove')->assertSuccessful();
        $this->assertSame(0, Product::count() + Post::count() + Faq::count());
        $this->assertFalse(CompanySetting::first()->show_team_page);
        $this->assertEmpty(Storage::disk('public')->allFiles('preview'));
    }

    public function test_refuses_to_run_in_production(): void
    {
        $this->app['env'] = 'production';

        $this->artisan('preview:content')->assertFailed();
        $this->assertSame(0, Product::count());
    }
}
