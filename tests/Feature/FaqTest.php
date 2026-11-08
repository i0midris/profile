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

    public function test_faq_section_is_hidden_without_questions(): void
    {
        $this->get('/ar')->assertDontSee('"@type":"FAQPage"', false);
        $this->get('/ar/contact')->assertDontSee('id="faq"', false);
    }

    public function test_published_questions_show_with_schema(): void
    {
        Faq::create(['question_ar' => 'هل يعمل النظام بدون إنترنت؟', 'answer_ar' => 'نعم، ويُزامن عند عودة الاتصال.', 'question_en' => 'Does it work offline?', 'answer_en' => 'Yes.']);
        Faq::create(['question_ar' => 'سؤال للصفحة فقط', 'answer_ar' => 'جواب', 'show_on_home' => false]);
        Faq::create(['question_ar' => 'سؤال غير منشور', 'answer_ar' => 'جواب', 'is_active' => false]);

        $this->get('/ar')->assertSee('هل يعمل النظام بدون إنترنت؟')->assertSee('"@type":"FAQPage"', false)
            ->assertDontSee('سؤال للصفحة فقط')->assertDontSee('سؤال غير منشور');
        $this->get('/ar/contact')->assertSee('سؤال للصفحة فقط')->assertDontSee('سؤال غير منشور');
        $this->get('/en')->assertSee('Does it work offline?');
    }

    public function test_admin_can_add_a_question(): void
    {
        $this->actingAs(User::factory()->create())->post('/admin/content/faqs', [
            'question_ar' => 'كم مدة التركيب؟', 'answer_ar' => 'يومان في الغالب.', 'is_active' => '1', 'show_on_home' => '1',
        ])->assertSessionHasNoErrors();

        $this->assertSame('كم مدة التركيب؟', Faq::firstOrFail()->question_ar);
    }
}
