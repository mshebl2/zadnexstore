<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderInvoiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_admin_invoice(): void
    {
        $order = Order::create([
            'order_number' => 'INC-TEST-001',
            'customer_name' => 'محمد عبدالله',
            'customer_phone' => '0501234567',
            'customer_email' => 'customer@test.com',
            'city' => 'الرياض',
            'district' => 'النرجس',
            'street_address' => 'طريق الملك فيصل',
            'subtotal' => 250,
            'shipping_cost' => 0,
            'total' => 250,
            'status' => 'pending',
            'payment_status' => 'unpaid',
            'payment_method' => 'cod',
        ]);

        $response = $this->get(route('admin.orders.invoice', $order->id));
        $response->assertRedirect(route('login'));
    }

    public function test_admin_can_view_clean_printable_invoice(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $order = Order::create([
            'order_number' => 'INC-TEST-002',
            'customer_name' => 'سارة الشريف',
            'customer_phone' => '0559876543',
            'customer_email' => 'sara@test.com',
            'city' => 'جدة',
            'district' => 'الزهراء',
            'street_address' => 'شارع اليمامة',
            'subtotal' => 395,
            'shipping_cost' => 0,
            'total' => 395,
            'status' => 'processing',
            'payment_status' => 'paid',
            'payment_method' => 'paymob',
        ]);

        OrderItem::create([
            'order_id' => $order->id,
            'product_name' => 'عطر سمرقند الملكي',
            'sku' => 'INC-PRF-001',
            'quantity' => 1,
            'unit_price' => 395,
            'total_price' => 395,
        ]);

        $response = $this->actingAs($admin)->get(route('admin.orders.invoice', $order->id));

        $response->assertOk();
        $response->assertSee('INC-TEST-002');
        $response->assertSee('سارة الشريف');
        $response->assertSee('عطر سمرقند الملكي');
        $response->assertSee('فاتورة مبيعات معتمدة');
        $response->assertSee('متجر زاد نيكس');
        $response->assertDontSee('لوحة المؤشرات العامة');
        $response->assertDontSee('تسجيل الخروج');
    }
}
