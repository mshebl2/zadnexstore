<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\StoreSetting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class SeoAndAdminToolsTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create([
            'role' => 'admin',
            'email' => 'admin@incense-sa.com',
        ]);
    }

    public function test_sitemap_xml_and_robots_txt_are_accessible_publicly(): void
    {
        $category = Category::create([
            'name_ar' => 'عطور ملكية',
            'name_en' => 'Royal Perfumes',
            'slug' => 'royal-perfumes',
            'is_active' => true,
        ]);

        $product = Product::create([
            'category_id' => $category->id,
            'name_ar' => 'عطر اللبان الملكي',
            'name_en' => 'Royal Frankincense',
            'slug' => 'royal-frankincense',
            'sku' => 'INC-TST-001',
            'regular_price' => 300,
            'stock_quantity' => 10,
            'description_ar' => 'وصف تجريبي',
            'is_active' => true,
        ]);

        $sitemapRes = $this->get('/sitemap.xml');
        $sitemapRes->assertStatus(200);
        $sitemapRes->assertHeader('Content-Type', 'text/xml; charset=utf-8');
        $sitemapRes->assertSee('<urlset', false);
        $sitemapRes->assertSee($product->slug, false);

        $robotsRes = $this->get('/robots.txt');
        $robotsRes->assertStatus(200);
        $robotsRes->assertHeader('Content-Type', 'text/plain; charset=utf-8');
        $robotsRes->assertSee('Sitemap:', false);
        $robotsRes->assertSee('/sitemap.xml', false);
    }

    public function test_admin_can_export_orders_as_csv(): void
    {
        $order = Order::create([
            'order_number' => 'INC-TEST-999',
            'customer_name' => 'محمد عبدالله',
            'customer_phone' => '0501234567',
            'customer_email' => 'customer@test.com',
            'city' => 'الرياض',
            'street_address' => 'طريق الملك فهد',
            'subtotal' => 200,
            'shipping_cost' => 0,
            'discount_amount' => 0,
            'total' => 200,
            'payment_status' => 'paid',
            'status' => 'pending',
            'payment_method' => 'card',
        ]);

        $res = $this->actingAs($this->admin)->get(route('admin.orders.export'));
        $res->assertStatus(200);
        $res->assertHeader('Content-Type', 'text/csv; charset=UTF-8');
    }

    public function test_admin_can_delete_an_order(): void
    {
        $order = Order::create([
            'order_number' => 'INC-DELETE-123',
            'customer_name' => 'تجربة حذف',
            'customer_phone' => '0555555555',
            'city' => 'جدة',
            'street_address' => 'حي الروضة',
            'subtotal' => 100,
            'total' => 100,
            'payment_status' => 'unpaid',
            'status' => 'cancelled',
            'payment_method' => 'card',
        ]);

        $res = $this->actingAs($this->admin)->delete(route('admin.orders.destroy', $order->id));
        $res->assertRedirect(route('admin.orders.index'));
        $this->assertDatabaseMissing('orders', ['id' => $order->id]);
    }

    public function test_admin_can_download_product_template_csv(): void
    {
        $res = $this->actingAs($this->admin)->get(route('admin.products.template'));
        $res->assertStatus(200);
        $res->assertHeader('Content-Type', 'text/csv; charset=UTF-8');
    }

    public function test_admin_can_import_products_from_csv(): void
    {
        $csvContent = "name_ar,name_en,sku,category,regular_price,sale_price,stock_quantity,size,description_ar\n"
            . "\"عطر الورد الملكي\",\"Royal Rose\",\"INC-TEST-IMP-1\",\"عطور نيش\",250.00,199.00,20,\"100ml\",\"وصف تجريبي\"\n";

        $file = UploadedFile::fake()->createWithContent('products.csv', $csvContent);

        $res = $this->actingAs($this->admin)
            ->from(route('admin.products.index'))
            ->post(route('admin.products.import'), [
                'file' => $file,
            ]);

        $res->assertRedirect(route('admin.products.index'));
        $this->assertDatabaseHas('products', [
            'sku' => 'INC-TEST-IMP-1',
            'name_ar' => 'عطر الورد الملكي',
        ]);
    }

    public function test_admin_can_save_seo_and_pixels_settings(): void
    {
        $res = $this->actingAs($this->admin)->post(route('admin.settings.update'), [
            'meta_title' => 'متجر إنسينس الرسمي',
            'meta_keywords' => 'بخور, عود, عطور ملكية',
            'google_site_verification' => 'google-test-token-123',
            'meta_pixel_id' => '1122334455',
            'tiktok_pixel_id' => 'TIKTOK-99',
            'snapchat_pixel_id' => 'SNAP-88',
            'google_ads_id' => 'AW-777',
        ]);

        $res->assertSessionHas('success');
        $this->assertEquals('متجر إنسينس الرسمي', StoreSetting::get('meta_title'));
        $this->assertEquals('google-test-token-123', StoreSetting::get('google_site_verification'));
        $this->assertEquals('1122334455', StoreSetting::get('meta_pixel_id'));

        // Verify that layout renders them
        $homeRes = $this->get('/');
        $homeRes->assertStatus(200);
        $homeRes->assertSee('google-test-token-123');
        $homeRes->assertSee('1122334455');
        $homeRes->assertSee('TIKTOK-99');
        $homeRes->assertSee('SNAP-88');
        $homeRes->assertSee('AW-777');
    }

    public function test_custom_error_pages_render_luxury_brand_views(): void
    {
        $res404 = $this->get('/non-existent-page-url-12345');
        $res404->assertStatus(404);
        $res404->assertSee('الصفحة غير موجودة');
        $res404->assertSee('العودة للرئيسية');
    }
}
