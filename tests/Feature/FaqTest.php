<?php

namespace Tests\Feature;

use App\Models\CompanySetting;
use App\Models\Faq;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FaqTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        CompanySetting::create(['company_name' => 'Test']);
    }

    public function test_faq_page_and_teaser_are_hidden_without_questions(): void
    {
        $this->get('/ar/faq')->assertNotFound();
        $this->get('/ar')->assertDontSee('/ar/faq', false);
        $this->get('/ar/contact')->assertDontSee('/ar/faq', false);
    }

    public function test_faq_page_lists_questions_by_category_with_schema(): void
    {
        $q = Faq::create(['category' => 'products', 'question_ar' => 'هل يعمل النظام بدون إنترنت؟', 'answer_ar' => 'نعم، ويُزامن عند عودة الاتصال.', 'question_en' => 'Does it work offline?', 'answer_en' => 'Yes.', 'image' => 'faq/x.webp']);
        Faq::create(['category' => 'support', 'question_ar' => 'سؤال للصفحة فقط', 'answer_ar' => 'جواب', 'show_on_home' => false]);
        Faq::create(['question_ar' => 'سؤال غير منشور', 'answer_ar' => 'جواب', 'is_active' => false]);

        $this->get('/ar/faq')->assertOk()
            ->assertSee('هل يعمل النظام بدون إنترنت؟')->assertSee('سؤال للصفحة فقط')->assertDontSee('سؤال غير منشور')
            ->assertSee('"@type":"FAQPage"', false)
            ->assertSee('الأنظمة والمنتجات')->assertSee('الدعم والأمان')
            ->assertSee('storage/faq/x.webp', false);

        // Home and contact show only the short teaser that links to the page
        $this->get('/ar')->assertSee('/ar/faq#q-' . $q->id, false)->assertDontSee('سؤال للصفحة فقط')->assertDontSee('"@type":"FAQPage"', false);
        $this->get('/ar/contact')->assertSee('/ar/faq', false);
        $this->get('/ar')->assertSee('/ar/faq"', false); // "More" menu + footer
        $this->get('/en/faq')->assertSee('Does it work offline?');
    }

    public function test_every_answer_gets_a_fitting_illustration(): void
    {
        $chosen = Faq::create(['category' => 'general', 'illustration' => 'security', 'question_ar' => 'هل بياناتي آمنة؟', 'answer_ar' => 'نعم']);
        $auto = Faq::create(['category' => 'setup', 'question_ar' => 'هل تدربون الموظفين؟', 'answer_ar' => 'نعم']);
        $withImage = Faq::create(['category' => 'support', 'illustration' => 'support', 'image' => 'faq/real.webp', 'question_ar' => 'سؤال بصورة', 'answer_ar' => 'نعم']);
        $none = Faq::create(['illustration' => 'none', 'question_ar' => 'سؤال بلا رسم', 'answer_ar' => 'نعم']);

        $this->assertSame('security', $chosen->illustration_key);
        $this->assertSame('training', $auto->fresh()->illustration_key);   // auto: from the "setup" category
        $this->assertNull($withImage->illustration_key);                    // a real image wins
        $this->assertNull($none->illustration_key);

        $html = $this->get('/ar/faq')->assertOk()->getContent();
        $this->assertSame(2, substr_count($html, 'class="faq-illustration"'));
        $this->assertStringContainsString('الصلاحيات', $html);     // label inside the security drawing
        $this->assertStringContainsString('storage/faq/real.webp', $html);
    }

    public function test_admin_can_add_a_question(): void
    {
        $this->actingAs(User::factory()->create())->post('/admin/content/faqs', [
            'category' => 'setup', 'question_ar' => 'كم مدة التركيب؟', 'answer_ar' => 'يومان في الغالب.', 'is_active' => '1', 'show_on_home' => '1',
        ])->assertSessionHasNoErrors();

        $this->assertSame('كم مدة التركيب؟', Faq::firstOrFail()->question_ar);
        $this->assertSame('setup', Faq::firstOrFail()->category);
    }
}
