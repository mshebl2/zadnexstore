<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\HomepageBanner;
use App\Models\StoreSetting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StorefrontTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_homepage_loads_successfully_with_luxury_content(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('INCENSE');
        $response->assertSee('إنسينس السعودية');
        $response->assertSee('سلة المشتريات');
    }

    public function test_products_catalog_page_displays_seeded_fragrances(): void
    {
        $response = $this->get('/products');

        $response->assertStatus(200);
        $response->assertSee('تشكيلة منتجات إنسينس الفاخرة');
        $response->assertSee('عطر سمرقند الملكي');
    }

    public function test_category_filter_shows_category_specific_products(): void
    {
        $category = Category::where('slug', 'dehn-al-oud')->first();
        $this->assertNotNull($category);

        $response = $this->get('/category/' . $category->slug);

        $response->assertStatus(200);
        $response->assertSee($category->name_ar);
        $response->assertSee('دهن عود تراد الحطب');
    }

    public function test_product_detail_page_loads_with_fragrance_notes_and_variants(): void
    {
        $product = Product::where('slug', 'samarkand-royal-perfume')->first();
        $this->assertNotNull($product);

        $response = $this->get('/product/' . $product->slug);

        $response->assertStatus(200);
        $response->assertSee($product->name_ar);
        $response->assertSee('الهرم والتركيبة العطرية');
        $response->assertSee('الهيل');
        $response->assertSee('العود الكمبودي');
    }

    public function test_quick_view_api_returns_json_details(): void
    {
        $product = Product::first();
        $this->assertNotNull($product);

        $response = $this->getJson("/api/products/{$product->id}/quick-view");

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'id',
            'name',
            'slug',
            'category',
            'image',
            'current_price',
            'regular_price',
            'has_discount',
            'description',
            'stock',
        ]);
    }

    public function test_policy_pages_render_without_errors(): void
    {
        $this->get('/policy/shipping')->assertStatus(200)->assertSee('الشحن والتوصيل');
        $this->get('/policy/returns')->assertStatus(200)->assertSee('سياسة الاستبدال والاسترجاع');
        $this->get('/policy/privacy')->assertStatus(200)->assertSee('سياسة الخصوصية');
        $this->get('/policy/terms')->assertStatus(200)->assertSee('الشروط والأحكام');
    }

    public function test_live_search_api_returns_matching_products(): void
    {
        $response = $this->getJson('/api/search?q=سمرقند');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'products' => [
                '*' => ['id', 'name', 'category', 'slug', 'url', 'price', 'image']
            ]
        ]);
        $this->assertNotEmpty($response->json('products'));
    }

    public function test_products_search_page_filters_by_query(): void
    {
        $response = $this->get('/products?q=مروكي');
        $response->assertStatus(200);
        $response->assertSee('نتائج البحث عن:');
        $response->assertSee('مروكي');
    }

    public function test_flash_offer_popup_appears_on_storefront(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('INCENSE10');
        $response->assertSee('نسخ الكود');
    }

    public function test_homepage_renders_promotional_banner_grid_structure(): void
    {
        // Seed hero, secondary, and offers banners
        HomepageBanner::create([
            'placement' => 'hero',
            'title_ar' => 'عود المشايخ الملكي',
            'subtitle_ar' => 'أصالة العود المعتق',
            'image_url' => 'https://images.unsplash.com/photo-1547887537-6158d64c35b3',
            'is_active' => true,
            'sort_order' => 1,
        ]);

        HomepageBanner::create([
            'placement' => 'secondary',
            'title_ar' => 'عود بيلسان الفاخر',
            'subtitle_ar' => 'بكسرات العود الطبيعي',
            'image_url' => 'https://images.unsplash.com/photo-1592945403244-b3fbafd7f539',
            'is_active' => true,
            'sort_order' => 1,
        ]);

        HomepageBanner::create([
            'placement' => 'offers',
            'title_ar' => 'عطر روج الفرنسي',
            'subtitle_ar' => 'خصم 60%',
            'badge_ar' => 'خصم 60%',
            'image_url' => 'https://images.unsplash.com/photo-1588405748880-12d1d2a59f75',
            'is_active' => true,
            'sort_order' => 1,
        ]);

        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('عود المشايخ الملكي');
        $response->assertSee('عود بيلسان الفاخر');
        $response->assertSee('عروض العطور');
        $response->assertSee('عطر روج الفرنسي');
    }

    public function test_admin_can_manage_homepage_banners_and_sections(): void
    {
        $admin = \App\Models\User::create([
            'name' => 'Admin User',
            'email' => 'admin_test_' . uniqid() . '@incense-sa.com',
            'password' => \Illuminate\Support\Facades\Hash::make('password123'),
            'role' => 'admin',
        ]);

        // 1. Visit banner management page
        $res = $this->actingAs($admin)->get('/admin/banners');
        $res->assertStatus(200);
        $res->assertSee('إدارة الصفحة الرئيسية والبنرات');

        // 2. Create new promotional banner
        $createRes = $this->actingAs($admin)->post('/admin/banners', [
            'placement' => 'secondary',
            'title_ar' => 'بخور ملكي فاخر',
            'badge_ar' => 'جديد',
            'button_text_ar' => 'اطلب الآن',
            'button_url' => '/category/bakhoor-incense',
            'image_url' => 'https://images.unsplash.com/photo-test',
        ]);
        $createRes->assertSessionHas('success');

        $this->assertDatabaseHas('homepage_banners', [
            'title_ar' => 'بخور ملكي فاخر',
            'placement' => 'secondary',
        ]);

        // 3. Update homepage sections settings
        $secRes = $this->actingAs($admin)->post('/admin/homepage/sections', [
            'homepage_offers_title' => 'عروض إنسينس الكبرى',
            'homepage_section_offers_visible' => '1',
            'homepage_section_hero_visible' => '1',
        ]);
        $secRes->assertSessionHas('success');
        $this->assertEquals('عروض إنسينس الكبرى', StoreSetting::get('homepage_offers_title'));
    }
}

