<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Coupon;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;

class CartService
{
    public function getOrCreateCart(): Cart
    {
        $userId = Auth::id();
        $cartId = Session::get('cart_id');
        $cart = null;

        if ($cartId) {
            $cart = Cart::find($cartId);
        }

        if (!$cart && $userId) {
            $cart = Cart::where('user_id', $userId)->first();
        }

        if (!$cart) {
            $sessionId = Session::getId();
            $cart = Cart::where('session_id', $sessionId)->first();
        }

        if (!$cart) {
            $sessionId = Session::getId();
            $cart = Cart::create([
                'session_id' => $sessionId,
                'user_id' => $userId,
            ]);
        }

        if ($userId && !$cart->user_id) {
            $cart->user_id = $userId;
            $cart->save();
        }

        Session::put('cart_id', $cart->id);

        return $cart;
    }

    public function addItem(int $productId, ?int $variantId = null, int $quantity = 1): array
    {
        $product = Product::active()->findOrFail($productId);

        if (!$product->is_in_stock) {
            return ['success' => false, 'message' => 'عذراً، هذا المنتج غير متوفر في المخزون حالياً'];
        }

        $price = $product->current_price;
        $variant = null;

        if ($variantId) {
            $variant = ProductVariant::where('product_id', $productId)->where('is_active', true)->findOrFail($variantId);
            $price = $variant->current_price;
            if ($variant->stock_quantity < $quantity) {
                return ['success' => false, 'message' => 'الكمية المطلوبة غير متوفرة لهذا الحجم'];
            }
        } else {
            if ($product->stock_quantity < $quantity) {
                return ['success' => false, 'message' => 'الكمية المطلوبة تتجاوز المخزون المتوفر'];
            }
        }

        $cart = $this->getOrCreateCart();

        $existingItem = CartItem::where('cart_id', $cart->id)
            ->where('product_id', $productId)
            ->where('variant_id', $variantId)
            ->first();

        if ($existingItem) {
            $newQty = $existingItem->quantity + $quantity;
            $maxStock = $variant ? $variant->stock_quantity : $product->stock_quantity;
            if ($newQty > $maxStock) {
                return ['success' => false, 'message' => 'لا يمكن إضافة المزيد، لقد وصلت للحد الأقصى للمخزون المتوفر'];
            }
            $existingItem->quantity = $newQty;
            $existingItem->price = $price; // Fresh price
            $existingItem->save();
        } else {
            CartItem::create([
                'cart_id' => $cart->id,
                'product_id' => $productId,
                'variant_id' => $variantId,
                'quantity' => $quantity,
                'price' => $price,
            ]);
        }

        return ['success' => true, 'message' => 'تمت إضافة المنتج إلى سلة التسوق بنجاح'];
    }

    public function updateItemQuantity(int $itemId, int $quantity): array
    {
        $cart = $this->getOrCreateCart();
        $item = CartItem::where('cart_id', $cart->id)->findOrFail($itemId);

        if ($quantity <= 0) {
            $item->delete();
            return ['success' => true, 'message' => 'تم حذف المنتج من السلة'];
        }

        $maxStock = $item->variant ? $item->variant->stock_quantity : $item->product->stock_quantity;
        if ($quantity > $maxStock) {
            return ['success' => false, 'message' => 'الكمية المحددة تتجاوز المتوفر في المخزون (' . $maxStock . ')'];
        }

        $item->quantity = $quantity;
        $item->save();

        return ['success' => true, 'message' => 'تم تحديث الكمية'];
    }

    public function removeItem(int $itemId): array
    {
        $cart = $this->getOrCreateCart();
        CartItem::where('cart_id', $cart->id)->where('id', $itemId)->delete();
        return ['success' => true, 'message' => 'تم حذف المنتج من السلة'];
    }

    public function getCartPayload(): array
    {
        $cart = $this->getOrCreateCart();
        $cart->load(['items.product.images', 'items.variant']);

        $items = $cart->items->map(function ($item) {
            return [
                'id' => $item->id,
                'product_id' => $item->product_id,
                'name' => $item->product->name_ar,
                'variant' => $item->variant?->title_ar,
                'price' => (float) $item->price,
                'quantity' => $item->quantity,
                'total' => (float) $item->total,
                'image' => $item->product->primary_image,
            ];
        });

        $subtotal = (float) $cart->subtotal;
        $discount = 0.00;

        if ($cart->coupon_code) {
            $coupon = Coupon::where('code', $cart->coupon_code)->where('is_active', true)->first();
            if ($coupon && $coupon->isValidFor($subtotal)) {
                $discount = $coupon->calculateDiscount($subtotal);
            } else {
                // Invalidate coupon if subtotal fell below threshold
                $cart->coupon_code = null;
                $cart->save();
            }
        }

        return [
            'count' => (int) $cart->items_count,
            'subtotal' => $subtotal,
            'discount' => $discount,
            'total' => max(0, $subtotal - $discount),
            'coupon' => $cart->coupon_code,
            'items' => $items,
        ];
    }

    public function applyCoupon(string $code): array
    {
        $cart = $this->getOrCreateCart();
        $coupon = Coupon::where('code', strtoupper(trim($code)))->first();

        if (!$coupon) {
            return ['success' => false, 'message' => 'كود الخصم غير صحيح أو غير موجود'];
        }

        if (!$coupon->isValidFor($cart->subtotal)) {
            if ($coupon->min_order_amount > $cart->subtotal) {
                return ['success' => false, 'message' => 'الحد الأدنى للطلب لتفعيل هذا الكوبون هو ' . $coupon->min_order_amount . ' ر.س'];
            }
            return ['success' => false, 'message' => 'هذا الكوبون منتهي الصلاحية أو غير فعال'];
        }

        $cart->coupon_code = $coupon->code;
        $cart->save();

        return ['success' => true, 'message' => 'تم تطبيق كود الخصم بنجاح'];
    }

    public function removeCoupon(): array
    {
        $cart = $this->getOrCreateCart();
        $cart->coupon_code = null;
        $cart->save();
        return ['success' => true, 'message' => 'تم إلغاء كود الخصم'];
    }
}
