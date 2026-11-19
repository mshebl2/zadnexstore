<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Order;
use App\Models\UserAddress;
use App\Models\ReferralCommission;
use App\Models\PayoutRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CustomerDashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_can_register_with_referral_code(): void
    {
        $referrer = User::create([
            'name' => 'المسوق الأول',
            'email' => 'referrer@example.com',
            'password' => bcrypt('password'),
            'referral_code' => 'SUPERREF',
            'role' => 'customer',
        ]);

        $response = $this->post('/register', [
            'name' => 'عميل جديد',
            'email' => 'newcustomer@example.com',
            'phone' => '0512345678',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'referred_by' => 'SUPERREF',
        ]);

        $response->assertRedirect('/my-account');
        $this->assertDatabaseHas('users', [
            'email' => 'newcustomer@example.com',
            'referred_by' => 'SUPERREF',
        ]);
        $this->assertAuthenticated();
    }

    public function test_customer_dashboard_renders_successfully(): void
    {
        $user = User::create([
            'name' => 'فيصل الحربي',
            'email' => 'faisal@example.com',
            'password' => bcrypt('password'),
            'role' => 'customer',
            'wallet_balance' => 150.00,
        ]);

        $response = $this->actingAs($user)->get('/my-account');

        $response->assertStatus(200);
        $response->assertSee('فيصل الحربي');
        $response->assertSee('عميل معتمد في متجر زاد نيكس');
        $response->assertSee('رصيد أرباح الإحالات');
        $response->assertSee('150.00');
    }

    public function test_customer_can_store_and_delete_address(): void
    {
        $user = User::create([
            'name' => 'سارة العتيبي',
            'email' => 'sara@example.com',
            'password' => bcrypt('password'),
            'role' => 'customer',
        ]);

        $response = $this->actingAs($user)->post('/my-account/addresses', [
            'title' => 'المنزل',
            'recipient_name' => 'سارة العتيبي',
            'recipient_phone' => '0555555555',
            'city' => 'الرياض',
            'district' => 'الياسمين',
            'street_address' => 'شارع الثمامة',
            'is_default' => 1,
        ]);

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('user_addresses', [
            'user_id' => $user->id,
            'title' => 'المنزل',
            'city' => 'الرياض',
            'is_default' => true,
        ]);

        $address = UserAddress::where('user_id', $user->id)->first();
        $delResponse = $this->actingAs($user)->delete('/my-account/addresses/' . $address->id);
        $delResponse->assertSessionHas('success');
        $this->assertDatabaseMissing('user_addresses', ['id' => $address->id]);
    }

    public function test_customer_can_request_payout_with_valid_iban(): void
    {
        $user = User::create([
            'name' => 'خالد السعيد',
            'email' => 'khaled@example.com',
            'password' => bcrypt('password'),
            'role' => 'customer',
            'wallet_balance' => 200.00,
        ]);

        $response = $this->actingAs($user)->post('/my-account/payout-request', [
            'amount' => 100.00,
            'bank_name' => 'مصرف الراجحي',
            'account_name' => 'خالد السعيد',
            'iban' => 'SA0380000000608010167519',
        ]);

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('payout_requests', [
            'user_id' => $user->id,
            'amount' => 100.00,
            'bank_name' => 'مصرف الراجحي',
            'status' => 'pending',
        ]);

        // Wallet balance should be decremented to 100.00
        $this->assertEquals(100.00, $user->fresh()->wallet_balance);
    }

    public function test_admin_can_view_and_manage_custom_affiliate_commissions(): void
    {
        $admin = User::create([
            'name' => 'مدير النظام',
            'email' => 'admin@test.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        $affiliate = User::create([
            'name' => 'مسوق تجريبي',
            'email' => 'affiliate@test.com',
            'password' => bcrypt('password'),
            'role' => 'customer',
            'referral_code' => 'AFFTEST1',
            'wallet_balance' => 50.00,
        ]);

        // 1. Admin views affiliates dashboard
        $res = $this->actingAs($admin)->get('/admin/affiliates');
        $res->assertStatus(200);
        $res->assertSee('affiliate@test.com');
        $res->assertSee('AFFTEST1');

        // 2. Admin sets custom percent commission (15%)
        $rateRes = $this->actingAs($admin)->put("/admin/affiliates/{$affiliate->id}/rate", [
            'custom_commission_type' => 'percent',
            'custom_commission_rate' => 15.00,
            'is_affiliate_active' => 1,
        ]);
        $rateRes->assertSessionHas('success');
        $this->assertDatabaseHas('users', [
            'id' => $affiliate->id,
            'custom_commission_type' => 'percent',
            'custom_commission_rate' => 15.00,
        ]);

        // 3. Admin adjusts wallet balance (adds 100 SAR bonus)
        $balRes = $this->actingAs($admin)->post("/admin/affiliates/{$affiliate->id}/adjust-balance", [
            'amount' => 100.00,
            'action' => 'add',
            'note' => 'مكافأة تميز',
        ]);
        $balRes->assertSessionHas('success');
        $this->assertEquals(150.00, $affiliate->fresh()->wallet_balance);
    }

    public function test_affiliate_service_applies_custom_commission_correctly(): void
    {
        $referrer = User::create([
            'name' => 'مسوق خاص',
            'email' => 'vipaffiliate@test.com',
            'password' => bcrypt('password'),
            'role' => 'customer',
            'referral_code' => 'VIPREF',
            'custom_commission_type' => 'fixed',
            'custom_commission_rate' => 25.00,
            'wallet_balance' => 0.00,
        ]);

        $buyer = User::create([
            'name' => 'مشتري',
            'email' => 'buyer@test.com',
            'password' => bcrypt('password'),
            'role' => 'customer',
            'referred_by' => 'VIPREF',
        ]);

        $order = Order::create([
            'order_number' => 'INC-TEST-VIP',
            'user_id' => $buyer->id,
            'customer_name' => 'مشتري',
            'customer_email' => 'buyer@test.com',
            'customer_phone' => '0501112233',
            'shipping_zone_id' => null,
            'city' => 'الرياض',
            'street_address' => 'طريق الملك فهد',
            'subtotal' => 300.00,
            'discount_amount' => 0.00,
            'shipping_cost' => 0.00,
            'total' => 300.00,
            'payment_method' => 'card',
            'payment_status' => 'paid',
            'fulfillment_status' => 'processing',
        ]);

        \App\Services\AffiliateService::processCommission($order);

        // Fixed commission of 25 SAR should be credited
        $this->assertDatabaseHas('referral_commissions', [
            'referrer_id' => $referrer->id,
            'order_id' => $order->id,
            'commission_amount' => 25.00,
        ]);

        $this->assertEquals(25.00, $referrer->fresh()->wallet_balance);
    }
}

