<?php

namespace App\Http\Controllers;

use App\Services\CartService;
use App\Models\Coupon;
use Illuminate\Http\Request;

class CartController extends Controller
{
    protected CartService $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    public function index()
    {
        $cart = $this->cartService->getOrCreateCart();
        $cart->load(['items.product.images', 'items.variant']);

        $subtotal = $cart->subtotal;
        $discountAmount = 0.00;
        $coupon = null;

        if ($cart->coupon_code) {
            $coupon = Coupon::where('code', $cart->coupon_code)->where('is_active', true)->first();
            if ($coupon && $coupon->isValidFor($subtotal)) {
                $discountAmount = $coupon->calculateDiscount($subtotal);
            }
        }

        return view('pages.cart', compact('cart', 'coupon', 'discountAmount'));
    }

    public function apiGet()
    {
        return response()->json($this->cartService->getCartPayload());
    }

    public function apiAdd(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'variant_id' => 'nullable|exists:product_variants,id',
            'quantity' => 'nullable|integer|min:1',
        ]);

        $res = $this->cartService->addItem(
            (int) $request->product_id,
            $request->variant_id ? (int) $request->variant_id : null,
            (int) ($request->quantity ?? 1)
        );

        $payload = $this->cartService->getCartPayload();
        $payload['message'] = $res['message'];

        return response()->json($payload, $res['success'] ? 200 : 422);
    }

    public function apiUpdate(Request $request, $itemId)
    {
        $request->validate(['quantity' => 'required|integer|min:0']);

        $res = $this->cartService->updateItemQuantity((int) $itemId, (int) $request->quantity);
        $payload = $this->cartService->getCartPayload();
        $payload['message'] = $res['message'];

        return response()->json($payload, $res['success'] ? 200 : 422);
    }

    public function apiRemove($itemId)
    {
        $res = $this->cartService->removeItem((int) $itemId);
        $payload = $this->cartService->getCartPayload();
        $payload['message'] = $res['message'];

        return response()->json($payload);
    }

    public function applyCoupon(Request $request)
    {
        $request->validate(['code' => 'required|string']);
        $res = $this->cartService->applyCoupon($request->code);

        return response()->json($res, $res['success'] ? 200 : 422);
    }

    public function removeCoupon()
    {
        $res = $this->cartService->removeCoupon();
        return response()->json($res);
    }
}

