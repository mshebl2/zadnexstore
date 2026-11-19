<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\Product;
use App\Models\ShippingZone;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CheckoutTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_cannot_access_checkout_with_empty_cart(): void
    {
        $response = $this->get('/checkout');
        $response->assertRedirect('/cart');
    }

    public function test_can_process_cod_checkout_and_deduct_inventory(): void
    {
        $product = Product::first();
        $initialStock = $product->stock_quantity;
        $shippingZone = ShippingZone::first();

        // 1. Add to cart
        $this->postJson('/api/cart/add', [
            'product_id' => $product->id,
            'quantity' => 2,
        ]);

        // 2. Checkout
        $response = $this->post('/checkout', [
            'customer_name' => 'فيصل الشمري',
            'customer_phone' => '0501234567',
            'customer_email' => 'faisal@example.com',
            'shipping_zone_id' => $shippingZone->id,
            'district' => 'الياسمين',
            'street_address' => 'طريق أنس بن مالك',
            'building_info' => 'فيلا 12',
            'notes' => 'يرجى التوصيل بعد العصر',
            'payment_method' => 'cod',
        ]);

        $order = Order::latest()->first();
        $this->assertNotNull($order);
        $this->assertEquals('فيصل الشمري', $order->customer_name);
        $this->assertEquals('cod', $order->payment_method);
        $this->assertEquals('unpaid', $order->payment_status);

        // Check stock was decremented atomically
        $product->refresh();
        $this->assertEquals($initialStock - 2, $product->stock_quantity);

        // Assert redirected to confirmation receipt
        $response->assertRedirect(route('checkout.confirmation', $order->order_number));

        // 3. View confirmation page
        $confirmResponse = $this->get(route('checkout.confirmation', $order->order_number));
        $confirmResponse->assertStatus(200);
        $confirmResponse->assertSee($order->order_number);
        $confirmResponse->assertSee('فيصل الشمري');
    }

    public function test_validates_saudi_mobile_number(): void
    {
        $product = Product::first();
        $shippingZone = ShippingZone::first();

        $this->postJson('/api/cart/add', [
            'product_id' => $product->id,
            'quantity' => 1,
        ]);

        // Invalid phone (not Saudi format)
        $response = $this->post('/checkout', [
            'customer_name' => 'محمد علي',
            'customer_phone' => '12345',
            'shipping_zone_id' => $shippingZone->id,
            'district' => 'حي الورود',
            'street_address' => 'شارع العروبة',
            'payment_method' => 'cod',
        ]);

        $response->assertSessionHasErrors('customer_phone');
    }
}

