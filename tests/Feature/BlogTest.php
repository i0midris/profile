<?php

namespace Tests\Feature;

use App\Models\CompanySetting;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BlogTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        CompanySetting::create(['company_name' => 'Test']);
    }

    public function test_blog_is_hidden_until_an_article_is_published(): void
    {
        $this->get('/ar/blog')->assertNotFound();

        Post::create(['slug' => 'future', 'title_ar' => 'مقال مجدول', 'body_ar' => 'نص', 'published_at' => now()->addDay()]);
        Post::create(['slug' => 'draft', 'title_ar' => 'مسودة', 'body_ar' => 'نص', 'is_active' => false]);

        $this->get('/ar/blog')->assertNotFound();
        $this->get('/ar/blog/future')->assertNotFound();
        $this->get('/ar/blog/draft')->assertNotFound();
        $this->get('/ar')->assertDontSee('/ar/blog"', false);
    }

    public function test_published_article_renders_markdown_safely_with_schema(): void
    {
        Post::create([
            'slug' => 'choose-accounting-system',
            'title_ar' => 'كيف تختار نظام محاسبة لشركتك',
            'body_ar' => "## الخطوة الأولى\n\n- حدّد احتياجك\n\n<script>alert(1)</script>\n\n[رابط](javascript:alert(1))",
        ]);

        $this->get('/ar')->assertSee('/ar/blog"', false);
        $this->get('/ar/blog')->assertOk()->assertSee('كيف تختار نظام محاسبة لشركتك');

        $response = $this->get('/ar/blog/choose-accounting-system')->assertOk()
            ->assertSee('<h2>الخطوة الأولى</h2>', false)
            ->assertSee('<li>حدّد احتياجك</li>', false)
            ->assertSee('"@type":"BlogPosting"', false);
        $this->assertStringNotContainsString('<script>alert(1)</script>', $response->getContent());
        $this->assertStringNotContainsString('href="javascript:', $response->getContent());

        $this->get('/sitemap.xml')->assertSee(url('/ar/blog/choose-accounting-system'), false);
    }

    public function test_admin_can_write_and_schedule_an_article(): void
    {
        $admin = User::factory()->create();

        $this->actingAs($admin)->post('/admin/content/posts', [
            'title_ar' => 'نظام نقاط بيع للمطاعم', 'title_en' => 'POS for restaurants',
            'body_ar' => 'نص المقال', 'is_active' => '1',
        ])->assertSessionHasNoErrors();
        $post = Post::firstOrFail();
        $this->assertSame('pos-for-restaurants', $post->slug);
        $this->assertTrue($post->isPublished());

        $this->actingAs($admin)->put("/admin/content/posts/{$post->id}", [
            'title_ar' => 'نظام نقاط بيع للمطاعم', 'body_ar' => 'نص المقال', 'is_active' => '1',
            'slug' => 'pos-for-restaurants', 'published_at' => now()->addWeek()->format('Y-m-d\TH:i'),
        ])->assertSessionHasNoErrors();
        $this->assertFalse($post->fresh()->isPublished());
    }
}
