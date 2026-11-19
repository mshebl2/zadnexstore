<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\Payment;
use App\Models\Product;
use App\Models\ShippingZone;
use App\Models\User;
use App\Services\PaymentService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PaymentAndSecurityTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_mock_payment_approval_marks_order_paid(): void
    {
        $product = Product::first();
        $shippingZone = ShippingZone::first();

        // Add to cart and checkout via paymob
        $this->postJson('/api/cart/add', ['product_id' => $product->id, 'quantity' => 1]);

        $this->post('/checkout', [
            'customer_name' => 'سعود القحطاني',
            'customer_phone' => '0551234567',
            'shipping_zone_id' => $shippingZone->id,
            'district' => 'الملقا',
            'street_address' => 'شارع الخير',
            'payment_method' => 'paymob',
        ]);

        $order = Order::latest()->first();
        $this->assertNotNull($order);

        // Process mock approval
        $response = $this->post(route('payment.mock.process', $order->order_number), [
            'action' => 'success',
            'transaction_id' => 'MOCK_TEST_123',
        ]);

        $response->assertRedirect(route('checkout.confirmation', $order->order_number));

        $order->refresh();
        $this->assertEquals('paid', $order->payment_status);
        $this->assertEquals('processing', $order->status);
    }

    public function test_mock_payment_failure_restores_inventory_and_cancels_order(): void
    {
        $product = Product::first();
        $initialStock = $product->stock_quantity;
        $shippingZone = ShippingZone::first();

        $this->postJson('/api/cart/add', ['product_id' => $product->id, 'quantity' => 2]);

        $this->post('/checkout', [
            'customer_name' => 'سعود القحطاني',
            'customer_phone' => '0551234567',
            'shipping_zone_id' => $shippingZone->id,
            'district' => 'الملقا',
            'street_address' => 'شارع الخير',
            'payment_method' => 'paymob',
        ]);

        $order = Order::latest()->first();
        $this->assertEquals($initialStock - 2, $product->fresh()->stock_quantity);

        // Process mock decline
        $this->post(route('payment.mock.process', $order->order_number), [
            'action' => 'fail',
            'transaction_id' => 'MOCK_FAIL_456',
        ]);

        $order->refresh();
        $this->assertEquals('failed', $order->payment_status);
        $this->assertEquals('cancelled', $order->status);

        // Stock must be restored!
        $this->assertEquals($initialStock, $product->fresh()->stock_quantity);
    }

    public function test_unauthenticated_user_cannot_access_admin(): void
    {
        $response = $this->get('/admin');
        $response->assertRedirect('/login');
    }

    public function test_customer_user_cannot_access_admin(): void
    {
        $customer = User::where('role', 'customer')->first();
        $this->assertNotNull($customer);

        $response = $this->actingAs($customer)->get('/admin');
        $response->assertRedirect('/login');
    }

    public function test_admin_user_can_access_dashboard_and_manage_products(): void
    {
        $admin = User::where('role', 'admin')->first();
        $this->assertNotNull($admin);

        $response = $this->actingAs($admin)->get('/admin');
        $response->assertStatus(200);
        $response->assertSee('لوحة المؤشرات والتقارير');
        $response->assertSee('إجمالي المبيعات');

        $productsRes = $this->actingAs($admin)->get('/admin/products');
        $productsRes->assertStatus(200);
        $productsRes->assertSee('إدارة المنتجات والمخزون');
    }

    public function test_admin_can_manage_shipping_and_popup_ad_settings(): void
    {
        $admin = User::where('role', 'admin')->first();
        $this->assertNotNull($admin);

        // Check Shipping page
        $shippingRes = $this->actingAs($admin)->get('/admin/shipping');
        $shippingRes->assertStatus(200);
        $shippingRes->assertSee('مناطق التوصيل ورسوم الشحن');

        // Check Popup Ad page
        $popupRes = $this->actingAs($admin)->get('/admin/offers-popup');
        $popupRes->assertStatus(200);
        $popupRes->assertSee('إعلانات العروض المفاجئة');

        // Update Popup Ad settings
        $updateRes = $this->actingAs($admin)->post('/admin/offers-popup', [
            'popup_ad_active' => '1',
            'popup_ad_title' => 'عرض عيد الفطر المبارك',
            'popup_ad_subtitle' => 'خصم 20% خاص على دهن العود الكمبودي',
            'popup_ad_coupon' => 'EID20',
            'popup_ad_badge' => 'عرض استثنائي',
            'popup_ad_delay' => '2',
            'popup_ad_duration' => '5',
        ]);

        $updateRes->assertSessionHas('success');
        $this->assertEquals('EID20', \App\Models\StoreSetting::get('popup_ad_coupon'));
        $this->assertEquals('عرض عيد الفطر المبارك', \App\Models\StoreSetting::get('popup_ad_title'));
    }

    public function test_security_headers_are_present_on_responses(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertHeader('X-Frame-Options', 'SAMEORIGIN');
        $response->assertHeader('X-Content-Type-Options', 'nosniff');
        $response->assertHeader('X-XSS-Protection', '1; mode=block');
        $response->assertHeader('Referrer-Policy', 'strict-origin-when-cross-origin');
    }

    public function test_order_confirmation_page_blocks_unauthorized_guests(): void
    {
        $order = Order::first();
        if ($order) {
            // Stranger without session or auth gets 403
            $response = $this->get(route('checkout.confirmation', $order->order_number));
            $response->assertStatus(403);
        }
    }
}

