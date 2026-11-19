<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Coupon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CartTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_can_add_item_to_cart_via_api(): void
    {
        $product = Product::first();
        $this->assertNotNull($product);

        $response = $this->postJson('/api/cart/add', [
            'product_id' => $product->id,
            'quantity' => 2,
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'count' => 2,
        ]);

        $this->assertEquals(2 * $product->current_price, $response->json('subtotal'));
    }

    public function test_cannot_add_more_than_available_stock(): void
    {
        $product = Product::first();
        $product->update(['stock_quantity' => 3]);

        $response = $this->postJson('/api/cart/add', [
            'product_id' => $product->id,
            'quantity' => 10,
        ]);

        $response->assertStatus(422);
    }

    public function test_can_update_quantity_and_remove_item(): void
    {
        $product = Product::first();
        $addRes = $this->postJson('/api/cart/add', [
            'product_id' => $product->id,
            'quantity' => 1,
        ]);

        $itemId = $addRes->json('items.0.id');

        // Update quantity to 3
        $updateRes = $this->postJson("/api/cart/update/{$itemId}", [
            'quantity' => 3,
        ]);
        $updateRes->assertStatus(200);
        $this->assertEquals(3, $updateRes->json('count'));

        // Remove item
        $removeRes = $this->postJson("/api/cart/remove/{$itemId}");
        $removeRes->assertStatus(200);
        $this->assertEquals(0, $removeRes->json('count'));
    }

    public function test_can_apply_and_validate_coupon(): void
    {
        $product = Product::where('regular_price', '>=', 300)->first();
        $this->assertNotNull($product);

        $this->postJson('/api/cart/add', [
            'product_id' => $product->id,
            'quantity' => 1,
        ]);

        // Apply INCENSE10 (10% discount)
        $couponRes = $this->postJson('/cart/coupon/apply', [
            'code' => 'INCENSE10',
        ]);

        $couponRes->assertStatus(200);
        $couponRes->assertJson(['success' => true]);

        $cartRes = $this->getJson('/api/cart');
        $expectedDiscount = round($product->current_price * 0.10, 2);
        $this->assertEquals($expectedDiscount, $cartRes->json('discount'));
    }

    public function test_rejects_invalid_coupon(): void
    {
        $response = $this->postJson('/cart/coupon/apply', [
            'code' => 'INVALID_CODE_999',
        ]);

        $response->assertStatus(422);
        $response->assertJson(['success' => false]);
    }
}

