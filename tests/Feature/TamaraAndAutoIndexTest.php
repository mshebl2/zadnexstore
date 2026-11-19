<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\ShippingZone;
use App\Models\StoreSetting;
use App\Models\User;
use App\Services\AutoIndexService;
use App\Services\TamaraService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class TamaraAndAutoIndexTest extends TestCase
{
    use RefreshDatabase;

    public function test_auto_index_service_generates_key_and_pings()
    {
        $key = AutoIndexService::getIndexNowKey();
        $this->assertNotEmpty($key);

        $ping = AutoIndexService::pingFullSitemap();
        $this->assertTrue($ping['success']);
        $this->assertGreaterThan(0, $ping['urls_count']);
    }

    public function test_tamara_service_initiates_mock_payment_safely()
    {
        $order = Order::create([
            'order_number' => 'INC-TEST-TAMARA-01',
            'customer_name' => 'محمد أحمد',
            'customer_phone' => '0501234567',
            'customer_email' => 'test@example.com',
            'city' => 'الرياض',
            'district' => 'العليا',
            'street_address' => 'شارع التحلية',
            'subtotal' => 200,
            'total' => 200,
            'status' => 'pending',
            'payment_status' => 'unpaid',
        ]);

        $tamara = new TamaraService();
        $result = $tamara->initiatePayment($order);

        $this->assertTrue($result['success']);
        $this->assertStringContainsString('tamara/mock', $result['redirect_url']);
        $this->assertDatabaseHas('payments', [
            'order_id' => $order->id,
            'provider' => 'tamara',
            'payment_method' => 'tamara_instalments',
            'status' => 'pending',
        ]);
    }

    public function test_tamara_mock_completion_approves_order()
    {
        $order = Order::create([
            'order_number' => 'INC-TEST-TAMARA-02',
            'customer_name' => 'سارة عبدالله',
            'customer_phone' => '0509876543',
            'city' => 'جدة',
            'district' => 'الروضة',
            'street_address' => 'شارع صاري',
            'subtotal' => 400,
            'total' => 400,
            'status' => 'pending',
            'payment_status' => 'unpaid',
        ]);

        $response = $this->post(route('payment.tamara.mock.process', $order->order_number), [
            'orderId' => 'TAMARA_MOCK_TEST_123',
        ]);

        $response->assertRedirect(route('checkout.confirmation', $order->order_number));
        $this->assertDatabaseHas('orders', [
            'order_number' => 'INC-TEST-TAMARA-02',
            'payment_status' => 'paid',
            'status' => 'processing',
        ]);
    }

    public function test_admin_can_save_tamara_and_trigger_seo_ping()
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->post(route('admin.settings.update'), [
            'store_name_ar' => 'إنسينس',
            'tamara_enabled' => '1',
            'tamara_mode' => 'sandbox',
            'tamara_api_token' => 'tamara_test_token_123',
        ]);

        $response->assertSessionHas('success');
        $this->assertEquals('1', StoreSetting::get('tamara_enabled'));
        $this->assertEquals('sandbox', StoreSetting::get('tamara_mode'));
        $this->assertEquals('tamara_test_token_123', StoreSetting::get('tamara_api_token'));

        $pingRes = $this->actingAs($admin)->post(route('admin.settings.seo.ping'));
        $pingRes->assertSessionHas('success');
    }
}
