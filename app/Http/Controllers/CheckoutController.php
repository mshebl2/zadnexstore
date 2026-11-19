<?php

namespace App\Http\Controllers;

use App\Services\CartService;
use App\Services\PaymentService;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ShippingZone;
use App\Models\Coupon;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\StoreSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class CheckoutController extends Controller
{
    protected CartService $cartService;
    protected PaymentService $paymentService;

    public function __construct(CartService $cartService, PaymentService $paymentService)
    {
        $this->cartService = $cartService;
        $this->paymentService = $paymentService;
    }

    public function index()
    {
        $cart = $this->cartService->getOrCreateCart();
        $cart->load(['items.product', 'items.variant']);

        if ($cart->items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'سلة الشراء فارغة، يرجى إضافة منتجات أولاً');
        }

        $subtotal = $cart->subtotal;
        $discount = 0.00;

        if ($cart->coupon_code) {
            $coupon = Coupon::where('code', $cart->coupon_code)->where('is_active', true)->first();
            if ($coupon && $coupon->isValidFor($subtotal)) {
                $discount = $coupon->calculateDiscount($subtotal);
            }
        }

        $shippingZones = ShippingZone::where('is_active', true)->get();
        $firstZone = $shippingZones->first();
        $initialShippingCost = $firstZone ? $firstZone->calculateShipping($subtotal - $discount) : 12.00;

        $paymobEnabled = StoreSetting::get('paymob_enabled', '0') === '1';
        $tamaraEnabled = StoreSetting::get('tamara_enabled', '0') === '1';
        $tabbyEnabled = StoreSetting::get('tabby_enabled', '0') === '1';
        $whatsappEnabled = StoreSetting::get('whatsapp_order_enabled', '1') === '1';
        $codEnabled = StoreSetting::get('cod_enabled', '1') === '1';

        $defaultPaymentMethod = 'whatsapp';
        if ($whatsappEnabled) {
            $defaultPaymentMethod = 'whatsapp';
        } elseif ($codEnabled) {
            $defaultPaymentMethod = 'cod';
        } elseif ($paymobEnabled) {
            $defaultPaymentMethod = 'paymob';
        } elseif ($tamaraEnabled) {
            $defaultPaymentMethod = 'tamara';
        }

        return view('pages.checkout', compact(
            'cart',
            'shippingZones',
            'subtotal',
            'discount',
            'initialShippingCost',
            'paymobEnabled',
            'tamaraEnabled',
            'tabbyEnabled',
            'whatsappEnabled',
            'codEnabled',
            'defaultPaymentMethod'
        ));
    }

    public function process(Request $request)
    {
        $paymobEnabled = StoreSetting::get('paymob_enabled', '0') === '1';
        $tamaraEnabled = StoreSetting::get('tamara_enabled', '0') === '1';
        $tabbyEnabled = StoreSetting::get('tabby_enabled', '0') === '1';
        $whatsappEnabled = StoreSetting::get('whatsapp_order_enabled', '1') === '1';
        $codEnabled = StoreSetting::get('cod_enabled', '1') === '1';

        $allowedMethods = [];
        if ($whatsappEnabled) $allowedMethods[] = 'whatsapp';
        if ($codEnabled) $allowedMethods[] = 'cod';
        if ($paymobEnabled) $allowedMethods[] = 'paymob';
        if ($tamaraEnabled) $allowedMethods[] = 'tamara';
        if ($tabbyEnabled) $allowedMethods[] = 'tabby';
        if (empty($allowedMethods)) $allowedMethods = ['whatsapp', 'cod'];

        $request->validate([
            'customer_name' => 'required|string|max:150',
            'customer_phone' => ['required', 'string', 'regex:/^(05|5|\+9665)[0-9]{8}$/'],
            'customer_email' => 'nullable|email|max:150',
            'shipping_zone_id' => 'required|exists:shipping_zones,id',
            'district' => 'required|string|max:100',
            'street_address' => 'required|string|max:255',
            'building_info' => 'nullable|string|max:100',
            'notes' => 'nullable|string|max:500',
            'payment_method' => ['required', Rule::in($allowedMethods)],
        ], [
            'customer_name.required' => 'يرجى إدخال اسم العميل الكامل',
            'customer_phone.required' => 'يرجى إدخال رقم الجوال السعودي',
            'customer_phone.regex' => 'صيغة رقم الجوال غير صحيحة، يجب أن يبدأ بـ 05 ويتكون من 10 أرقام',
            'district.required' => 'يرجى إدخال الحي في مكة المكرمة',
            'street_address.required' => 'يرجى إدخال اسم الشارع',
            'payment_method.required' => 'يرجى اختيار طريقة الدفع أو تأكيد الطلب',
            'payment_method.in' => 'طريقة الدفع المختارة غير متاحة حالياً',
        ]);

        $cart = $this->cartService->getOrCreateCart();
        $cart->load(['items.product', 'items.variant']);

        if ($cart->items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'سلة الشراء فارغة');
        }

        $shippingZone = ShippingZone::findOrFail($request->shipping_zone_id);

        try {
            $order = DB::transaction(function () use ($request, $cart, $shippingZone) {
                $subtotal = 0.00;

                // 1. Validate stock and calculate authoritative prices inside transaction
                foreach ($cart->items as $item) {
                    $product = Product::where('id', $item->product_id)->lockForUpdate()->firstOrFail();
                    
                    if ($item->variant_id) {
                        $variant = ProductVariant::where('id', $item->variant_id)->lockForUpdate()->firstOrFail();
                        if ($variant->stock_quantity < $item->quantity) {
                            throw new \Exception("الكمية المطلوبة من المنتج '{$product->name_ar}' بالحجم المحدد غير متوفرة");
                        }
                        $itemPrice = $variant->current_price;
                        // Deduct variant stock
                        $variant->decrement('stock_quantity', $item->quantity);
                    } else {
                        if ($product->stock_quantity < $item->quantity) {
                            throw new \Exception("عذراً، نفدت الكمية من المنتج '{$product->name_ar}'");
                        }
                        $itemPrice = $product->current_price;
                    }

                    // Deduct base product stock
                    $product->decrement('stock_quantity', $item->quantity);
                    $subtotal += ($itemPrice * $item->quantity);
                }

                // 2. Validate Coupon inside transaction
                $discount = 0.00;
                $couponCode = null;
                if ($cart->coupon_code) {
                    $coupon = Coupon::where('code', $cart->coupon_code)->where('is_active', true)->lockForUpdate()->first();
                    if ($coupon && $coupon->isValidFor($subtotal)) {
                        $discount = $coupon->calculateDiscount($subtotal);
                        $couponCode = $coupon->code;
                        $coupon->increment('used_count');
                    }
                }

                // 3. Shipping cost
                $shippingCost = $shippingZone->calculateShipping($subtotal - $discount);
                $finalTotal = max(0, ($subtotal - $discount + $shippingCost));

                // 4. Create Order with ZAD prefix
                $orderNumber = 'ZAD-' . date('ym') . '-' . strtoupper(Str::random(5));

                $order = Order::create([
                    'order_number' => $orderNumber,
                    'user_id' => auth()->id(),
                    'customer_name' => $request->customer_name,
                    'customer_phone' => $request->customer_phone,
                    'customer_email' => $request->customer_email,
                    'city' => $shippingZone->city_name_ar,
                    'district' => $request->district,
                    'street_address' => $request->street_address,
                    'building_info' => $request->building_info,
                    'notes' => $request->notes,
                    'subtotal' => $subtotal,
                    'shipping_cost' => $shippingCost,
                    'discount_amount' => $discount,
                    'total' => $finalTotal,
                    'coupon_code' => $couponCode,
                    'status' => 'pending',
                    'payment_status' => 'unpaid',
                    'payment_method' => $request->payment_method,
                ]);

                // 5. Create Order Items preserving historical snapshot
                foreach ($cart->items as $item) {
                    $unitPrice = $item->variant ? $item->variant->current_price : $item->product->current_price;
                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $item->product_id,
                        'variant_id' => $item->variant_id,
                        'product_name' => $item->product->name_ar,
                        'variant_name' => $item->variant?->title_ar,
                        'sku' => $item->variant ? $item->variant->sku : $item->product->sku,
                        'unit_price' => $unitPrice,
                        'quantity' => $item->quantity,
                        'total_price' => $unitPrice * $item->quantity,
                        'image_url' => $item->product->primary_image,
                    ]);
                }

                return $order;
            });

            session(['last_placed_order' => $order->order_number]);

            // If WhatsApp Order or Cash on delivery (Trial Beta Mode)
            if (in_array($request->payment_method, ['whatsapp', 'cod'])) {
                $cart->items()->delete();
                $cart->coupon_code = null;
                $cart->save();

                // Build rich WhatsApp message
                $whatsappNumber = StoreSetting::get('whatsapp_number', '966580760890');
                $cleanWhatsApp = preg_replace('/[^0-9]/', '', $whatsappNumber);
                if (!str_starts_with($cleanWhatsApp, '966')) {
                    $cleanWhatsApp = '966' . ltrim($cleanWhatsApp, '0');
                }

                $itemsText = [];
                foreach ($order->items as $idx => $item) {
                    $pos = $idx + 1;
                    $variant = $item->variant_name ? " [{$item->variant_name}]" : "";
                    $itemsText[] = "▫️ {$pos}. {$item->product_name}{$variant} (كمية: {$item->quantity}) = " . number_format($item->total_price, 2) . " ر.س";
                }

                $shippingText = $order->shipping_cost > 0 ? number_format($order->shipping_cost, 2) . " ر.س" : "مجاني";
                $discountText = $order->discount_amount > 0 ? "\n🎟️ *الخصم المطبق:* -" . number_format($order->discount_amount, 2) . " ر.س" : "";

                $msg = "🛒 *طلب جديد من متجر زاد نيكس (ZADNEX)* 🛒\n"
                     . "━━━━━━━━━━━━━━━━━━━━\n"
                     . "🔖 *رقم الطلب:* #{$order->order_number}\n"
                     . "👤 *اسم العميل:* {$order->customer_name}\n"
                     . "📱 *رقم الجوال:* {$order->customer_phone}\n"
                     . "📍 *المدينة:* مكة المكرمة\n"
                     . "🏘️ *الحي:* {$order->district}\n"
                     . "🛣️ *الشارع:* {$order->street_address}\n"
                     . ($order->building_info ? "🏢 *المبنى/الشقة:* {$order->building_info}\n" : "")
                     . ($order->notes ? "📝 *ملاحظات التوصيل:* {$order->notes}\n" : "")
                     . "━━━━━━━━━━━━━━━━━━━━\n"
                     . "📦 *المنتجات المطلوبة:*\n"
                     . implode("\n", $itemsText) . "\n"
                     . "━━━━━━━━━━━━━━━━━━━━\n"
                     . "💵 *المجموع الفرعي:* " . number_format($order->subtotal, 2) . " ر.س"
                     . $discountText . "\n"
                     . "🚚 *رسوم التوصيل (مكة المكرمة):* {$shippingText}\n"
                     . "💰 *الإجمالي المستحق الدفع:* " . number_format($order->total, 2) . " ر.س\n"
                     . "💳 *طريقة الدفع:* الدفع عند الاستلام (COD) بمكة المكرمة\n"
                     . "━━━━━━━━━━━━━━━━━━━━\n"
                     . "أرجو تأكيد استلام الطلب وتجهيزه للتوصيل. شكراً لكم!";

                $whatsappUrl = "https://wa.me/{$cleanWhatsApp}?text=" . rawurlencode($msg);

                return redirect()->route('checkout.confirmation', $order->order_number)
                    ->with('whatsapp_url', $whatsappUrl)
                    ->with('whatsapp_message', $msg)
                    ->with('success', 'تم تسجيل طلبك بنجاح! يُرجى تأكيد الطلب عبر واتساب للتجهيز الفوري.');
            }

            // If Tamara
            if ($request->payment_method === 'tamara') {
                $tamaraService = app(\App\Services\TamaraService::class);
                $tamaraResult = $tamaraService->initiatePayment($order);

                if ($tamaraResult['success']) {
                    $cart->items()->delete();
                    $cart->coupon_code = null;
                    $cart->save();
                    return redirect($tamaraResult['redirect_url']);
                }

                return redirect()->route('checkout.index')->with('error', $tamaraResult['message'] ?? 'فشلت عملية تهيئة الدفع عبر تمارا');
            }

            // Paymob payment flow (Mock or Live)
            $paymentResult = $this->paymentService->initiatePayment($order);

            if ($paymentResult['success']) {
                $cart->items()->delete();
                $cart->coupon_code = null;
                $cart->save();
                return redirect($paymentResult['redirect_url']);
            }

            return redirect()->route('checkout.index')->with('error', $paymentResult['message'] ?? 'فشلت عملية تهيئة الدفع');

        } catch (\Exception $e) {
            return redirect()->route('checkout.index')->with('error', $e->getMessage());
        }
    }

    public function confirmation($orderNumber)
    {
        $order = Order::with(['items.product', 'latestPayment'])->where('order_number', $orderNumber)->firstOrFail();

        $isAuthorized = false;
        if (session('last_placed_order') === $orderNumber) {
            $isAuthorized = true;
        } elseif (auth()->check()) {
            if (auth()->user()->isAdmin() || $order->user_id === auth()->id() || (empty($order->user_id) && $order->customer_email === auth()->user()->email)) {
                $isAuthorized = true;
            }
        }

        if (!$isAuthorized) {
            abort(403, 'غير مصرح لك باستعراض تفاصيل هذا الطلب.');
        }

        return view('pages.order-confirmation', compact('order'));
    }
}

