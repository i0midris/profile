<?php

namespace Tests\Feature;

use App\Models\CompanySetting;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        CompanySetting::create(['company_name' => 'Test', 'phone' => '777335118']);
    }

    public function test_products_pages_are_hidden_until_a_product_is_published(): void
    {
        $this->get('/ar/products')->assertNotFound();
        $this->get('/ar')->assertOk()->assertDontSee('/ar/products"', false);

        Product::create(['slug' => 'pos', 'name_ar' => 'نظام نقاط البيع', 'description_ar' => 'وصف', 'is_active' => false]);
        $this->get('/ar/products')->assertNotFound();
        $this->get('/ar/products/pos')->assertNotFound();
    }

    public function test_published_product_has_list_detail_nav_and_sitemap(): void
    {
        Product::create([
            'slug' => 'pos', 'name_ar' => 'نظام نقاط البيع', 'name_en' => 'POS System',
            'tagline_ar' => 'بيع أسرع', 'description_ar' => 'نظام لإدارة المبيعات',
            'features_ar' => ['فواتير سريعة', 'تقارير يومية'], 'sectors_ar' => ['المطاعم'],
            'is_active' => true,
        ]);

        $this->get('/ar')->assertSee('/ar/products"', false)->assertSee('نظام نقاط البيع');
        $this->get('/ar/products')->assertOk()->assertSee('نظام نقاط البيع')->assertSee('المطاعم');

        $this->get('/ar/products/pos')->assertOk()
            ->assertSee('فواتير سريعة')
            ->assertSee('"@type":"SoftwareApplication"', false)
            ->assertSee('wa.me/967777335118?text=', false);

        $this->get('/en/products/pos')->assertOk()->assertSee('POS System');
        $this->get('/products/pos', ['Accept-Language' => ''])->assertRedirect('/ar/products/pos');
        $this->get('/sitemap.xml')->assertSee(url('/ar/products/pos'), false);
    }

    public function test_admin_can_create_edit_and_delete_a_product(): void
    {
        $admin = User::factory()->create();

        $this->actingAs($admin)->get('/admin/content/products')->assertOk();
        $this->actingAs($admin)->get('/admin/content/products/create')->assertOk()->assertSee('name="name_ar"', false);

        $this->actingAs($admin)->post('/admin/content/products', [
            'name_ar' => 'نظام الموارد البشرية',
            'name_en' => 'HR System',
            'description_ar' => 'رواتب وإجازات',
            'features_ar' => ['الرواتب', '', 'الإجازات'],
            'sort_order' => 2,
            'is_active' => '1',
        ])->assertRedirect('/admin/content/products')->assertSessionHasNoErrors();

        $product = Product::firstOrFail();
        $this->assertSame('hr-system', $product->slug);
        $this->assertSame(['الرواتب', 'الإجازات'], $product->features_ar);

        $this->actingAs($admin)->put("/admin/content/products/{$product->id}", [
            'name_ar' => 'نظام الموارد البشرية والرواتب',
            'description_ar' => 'رواتب وإجازات',
            'slug' => 'hr',
            'is_active' => '0',
        ])->assertSessionHasNoErrors();
        $this->assertFalse($product->fresh()->is_active);
        $this->assertSame('hr', $product->fresh()->slug);

        $this->actingAs($admin)->delete("/admin/content/products/{$product->id}")->assertRedirect();
        $this->assertSame(0, Product::count());
    }

    public function test_product_requires_name_and_description_in_one_language(): void
    {
        $this->actingAs(User::factory()->create())
            ->post('/admin/content/products', ['is_active' => '1'])
            ->assertSessionHasErrors(['name_ar', 'name_en', 'description_ar']);
    }
}
