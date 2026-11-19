<x-app-layout>
    <x-slot:title>سلة المشتريات | متجر زاد نيكس</x-slot:title>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10"
         x-data="{
            couponInput: '{{ $cart->coupon_code }}',
            couponLoading: false,
            async applyCoupon() {
                if(!this.couponInput) return;
                this.couponLoading = true;
                try {
                    const token = document.querySelector('meta[name="csrf-token"]').content;
                    const res = await fetch('{{ route('cart.coupon.apply') }}', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': token, 'Accept': 'application/json' },
                        body: JSON.stringify({ code: this.couponInput })
                    });
                    const data = await res.json();
                    if(res.ok) {
                        $store.toast.notify(data.message);
                        window.location.reload();
                    } else {
                        $store.toast.notify(data.message, 'error');
                    }
                } catch(e) {
                    $store.toast.notify('حدث خطأ في تطبيق الكود', 'error');
                } finally {
                    this.couponLoading = false;
                }
            },
            async removeCoupon() {
                const token = document.querySelector('meta[name="csrf-token"]').content;
                const res = await fetch('{{ route('cart.coupon.remove') }}', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': token, 'Accept': 'application/json' }
                });
                window.location.reload();
            }
         }">

        <div class="mb-8 text-right">
            <h1 class="text-2xl sm:text-3xl font-extrabold text-[#242424]">سلة المشتريات</h1>
            <p class="text-xs text-[#777777] mt-1">راجع منتجاتك المختارة واستكمل طلبك بكل أمان</p>
        </div>

        @if($cart && $cart->items->count() > 0)
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- Items Column (2/3) -->
            <div class="lg:col-span-2 space-y-4">
                <div class="bg-white rounded-2xl border border-[#EDE6DA] overflow-hidden divide-y divide-[#EDE6DA]/70">
                    @foreach($cart->items as $item)
                    <div class="p-5 flex flex-col sm:flex-row items-center gap-4 text-right">
                        <!-- Thumbnail -->
                        <img src="{{ $item->product->primary_image }}" alt="{{ $item->product->name_ar }}" class="w-20 h-20 object-cover rounded-xl border border-[#EDE6DA] bg-[#FAF8F4] flex-shrink-0">
                        
                        <!-- Meta -->
                        <div class="flex-1 min-w-0 w-full sm:w-auto">
                            <a href="{{ route('product.show', $item->product->slug) }}" class="text-sm font-bold text-[#242424] hover:text-[#C5A059] transition block">
                                {{ $item->product->name_ar }}
                            </a>
                            @if($item->variant)
                                <span class="text-xs text-[#A59D92] block mt-0.5">{{ $item->variant->title_ar }}</span>
                            @endif
                            <div class="text-xs font-bold text-[#C5A059] mt-1">
                                {{ number_format($item->price, 2) }} ر.س
                            </div>
                        </div>

                        <!-- Quantity Actions -->
                        <div class="flex items-center gap-4 w-full sm:w-auto justify-between sm:justify-end">
                            <div class="flex items-center border border-[#EDE6DA] rounded-lg bg-[#FAF8F4]">
                                <button type="button" @click="$store.cart.updateQuantity({{ $item->id }}, {{ $item->quantity - 1 }}); setTimeout(() => location.reload(), 300)" class="px-2.5 py-1 text-xs text-[#777777] hover:text-[#242424]">
                                    <x-icon name="minus" class="w-3.5 h-3.5" />
                                </button>
                                <span class="px-3 text-xs font-bold text-[#242424]">{{ $item->quantity }}</span>
                                <button type="button" @click="$store.cart.updateQuantity({{ $item->id }}, {{ $item->quantity + 1 }}); setTimeout(() => location.reload(), 300)" class="px-2.5 py-1 text-xs text-[#777777] hover:text-[#242424]">
                                    <x-icon name="plus" class="w-3.5 h-3.5" />
                                </button>
                            </div>

                            <span class="text-sm font-bold text-[#242424] min-w-[70px] text-left">
                                {{ number_format($item->total, 2) }} ر.س
                            </span>

                            <button type="button" @click="$store.cart.removeItem({{ $item->id }}); setTimeout(() => location.reload(), 300)" class="p-1.5 text-red-500 hover:text-red-700 hover:bg-red-50 rounded-lg transition" title="حذف">
                                <x-icon name="trash" class="w-4 h-4" />
                            </button>
                        </div>
                    </div>
                    @endforeach
                </div>

                <div class="flex items-center justify-between text-xs text-[#777777] pt-2">
                    <a href="{{ route('products.index') }}" class="flex items-center gap-1 hover:text-[#C5A059] font-medium">
                        <x-icon name="arrow-right" class="w-4 h-4" />
                        <span>متابعة التسوق وإضافة المزيد</span>
                    </a>
                </div>
            </div>

            <!-- Summary Column (1/3) -->
            <div class="space-y-6">
                <!-- Coupon Box -->
                <div class="bg-white p-5 rounded-2xl border border-[#EDE6DA] text-right space-y-3">
                    <label class="block text-xs font-bold text-[#242424]">قسيمة الخصم (كوبون التخفيض):</label>
                    <div class="flex gap-2">
                        <input type="text" 
                               x-model="couponInput" 
                               placeholder="أدخل كود الخصم (مثل ZADNEX10)" 
                               class="flex-1 bg-[#FAF8F4] border border-[#EDE6DA] rounded-lg px-3 py-2 text-xs text-[#242424] uppercase focus:outline-none focus:border-[#C5A059]">
                        <button type="button" 
                                @click="applyCoupon()" 
                                :disabled="couponLoading" 
                                class="bg-[#242424] hover:bg-[#C5A059] text-white text-xs font-semibold px-4 py-2 rounded-lg transition">
                            <span x-show="!couponLoading">تطبيق</span>
                            <span x-show="couponLoading" x-cloak>جاري...</span>
                        </button>
                    </div>

                    @if($coupon)
                    <div class="p-2.5 bg-emerald-50 border border-emerald-200 rounded-lg flex items-center justify-between text-xs text-emerald-800">
                        <span>تم تطبيق الكود: <strong>{{ $coupon->code }}</strong></span>
                        <button type="button" @click="removeCoupon()" class="text-red-600 hover:underline text-[11px]">إزالة</button>
                    </div>
                    @endif
                </div>

                <!-- Order Totals Box -->
                <div class="bg-white p-6 rounded-2xl border border-[#EDE6DA] text-right space-y-4">
                    <h3 class="text-sm font-bold text-[#242424] border-b border-[#EDE6DA] pb-3">ملخص الطلب</h3>

                    <div class="space-y-2.5 text-xs text-[#777777]">
                        <div class="flex items-center justify-between">
                            <span>المجموع الفرعي:</span>
                            <span class="font-bold text-[#242424]">{{ number_format($cart->subtotal, 2) }} ر.س</span>
                        </div>

                        @if($discountAmount > 0)
                        <div class="flex items-center justify-between text-emerald-600">
                            <span>خصم الكوبون:</span>
                            <span class="font-bold">- {{ number_format($discountAmount, 2) }} ر.س</span>
                        </div>
                        @endif

                        <div class="flex items-center justify-between">
                            <span>الشحن والتوصيل:</span>
                            <span class="text-[#242424] font-medium">يحدد في خطوة الدفع</span>
                        </div>
                    </div>

                    <div class="border-t border-[#EDE6DA] pt-4 flex items-center justify-between text-base font-extrabold text-[#242424]">
                        <span>المجموع الكلي:</span>
                        <span class="text-xl text-[#C5A059]">
                            {{ number_format(max(0, $cart->subtotal - $discountAmount), 2) }} 
                            <span class="text-xs font-normal text-[#777777]">ر.س</span>
                        </span>
                    </div>

                    <a href="{{ route('checkout.index') }}" class="w-full bg-[#C5A059] hover:bg-[#AF8B44] text-white font-bold text-xs py-3.5 px-4 rounded-xl shadow-lg flex items-center justify-center gap-2 transition duration-200">
                        <x-icon name="shield-check" class="w-4 h-4" />
                        <span>متابعة الشراء والدفع الآمن</span>
                    </a>
                </div>
            </div>

        </div>
        @else
        <div class="bg-white rounded-2xl border border-[#EDE6DA] p-16 text-center max-w-md mx-auto space-y-4">
            <div class="w-20 h-20 mx-auto rounded-full bg-[#FAF8F4] flex items-center justify-center text-[#A59D92]">
                <x-icon name="shopping-bag" class="w-10 h-10" />
            </div>
            <h2 class="text-lg font-bold text-[#242424]">سلة مشترياتك فارغة</h2>
            <p class="text-xs text-[#777777] max-w-xs mx-auto">
                لم تقم بإضافة أي منتجات بعد. تصفح تشكيلتنا الفاخرة واختر ما يعجبك.
            </p>
            <a href="{{ route('products.index') }}" class="inline-block bg-[#C5A059] hover:bg-[#AF8B44] text-white text-xs font-bold px-8 py-3 rounded-xl shadow-md transition">
                تصفح المنتجات الآن
            </a>
        </div>
        @endif

    </div>
</x-app-layout>

