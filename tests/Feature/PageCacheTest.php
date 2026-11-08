<?php

namespace Tests\Feature;

use App\Models\CompanySetting;
use App\Models\Service;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PageCacheTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        config(['site.page_cache' => true]);
        CompanySetting::create(['company_name' => 'Test']);
    }

    public function test_read_only_pages_are_cached_and_set_no_cookies(): void
    {
        $first = $this->get('/ar/services')->assertOk()->assertHeader('X-Page-Cache', 'MISS');
        $second = $this->get('/ar/services')->assertOk()->assertHeader('X-Page-Cache', 'HIT');

        $this->assertSame($first->getContent(), $second->getContent());
        $this->assertEmpty($first->headers->getCookies(), 'Read-only pages must not create sessions/cookies.');
        $this->assertStringContainsString('public', $second->headers->get('Cache-Control'));
        $this->assertNotNull($second->headers->get('Content-Security-Policy'));
    }

    public function test_editing_content_refreshes_the_cache(): void
    {
        $this->get('/ar/services')->assertDontSee('نظام المطاعم');

        Service::create(['title' => 'نظام المطاعم', 'title_ar' => 'نظام المطاعم', 'description' => 'x', 'is_active' => true, 'order' => 1]);

        $this->get('/ar/services')->assertHeader('X-Page-Cache', 'MISS')->assertSee('نظام المطاعم');
    }

    public function test_contact_page_keeps_session_for_its_form(): void
    {
        $response = $this->get('/ar/contact')->assertOk();

        $this->assertFalse($response->headers->has('X-Page-Cache'));
        $this->assertNotEmpty($response->headers->getCookies());
        $response->assertSee('name="_token"', false);
    }
}
