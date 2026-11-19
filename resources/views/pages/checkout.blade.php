<x-app-layout>
    <x-slot:title>إتمام الطلب والدفع الآمن | متجر زاد نيكس</x-slot:title>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10"
         x-data="{
            shippingCost: {{ $initialShippingCost }},
            selectedCityId: '{{ $shippingZones->first()?->id }}',
            subtotal: {{ $subtotal }},
            discount: {{ $discount }},
            shippingZones: {{ $shippingZones->toJson() }},
            paymentMethod: '{{ $defaultPaymentMethod }}',

            updateCity(cityId) {
                this.selectedCityId = cityId;
                const zone = this.shippingZones.find(z => z.id == cityId);
                if (zone) {
                    if (zone.free_shipping_threshold && (this.subtotal - this.discount) >= zone.free_shipping_threshold) {
                        this.shippingCost = 0;
                    } else {
                        this.shippingCost = parseFloat(zone.shipping_cost);
                    }
                }
            },

            get finalTotal() {
                return Math.max(0, (this.subtotal - this.discount + this.shippingCost)).toFixed(2);
            }
         }">

        <div class="mb-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="text-right">
                <h1 class="text-2xl sm:text-3xl font-extrabold text-[#242424]">إتمام الطلب والدفع</h1>
                <p class="text-xs text-[#777777] mt-1">أدخل بيانات التوصيل واختر طريقة الدفع المفضلة لديك</p>
            </div>
            <a href="{{ route('cart.index') }}" class="inline-flex items-center gap-2 self-start sm:self-auto text-xs font-bold text-[#C5A059] hover:text-[#AF8B44] bg-[#FAF8F4] border border-[#EDE6DA] px-4 py-2.5 rounded-xl transition shadow-2xs">
                <span>العودة إلى السلة</span>
                <span>&larr;</span>
            </a>
        </div>

        @if(session('error'))
        <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-700 text-xs rounded-xl flex items-center gap-2">
            <x-icon name="x" class="w-4 h-4 text-red-600" />
            <span>{{ session('error') }}</span>
        </div>
        @endif

        <form action="{{ route('checkout.process') }}" method="POST" class="grid grid-cols-1 lg:grid-cols-3 gap-8 text-right">
            @csrf

            <!-- Form Details (2 Cols) -->
            <div class="lg:col-span-2 space-y-6">
                
                <!-- Customer Details -->
                <div class="bg-white p-6 rounded-2xl border border-[#EDE6DA] shadow-xs space-y-4">
                    <h2 class="text-sm font-bold text-[#242424] border-b border-[#EDE6DA] pb-3 flex items-center gap-2">
                        <x-icon name="user" class="w-4 h-4 text-[#C5A059]" />
                        <span>معلومات العميل</span>
                    </h2>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-[#242424] mb-1.5">الاسم الثلاثي <span class="text-red-500">*</span></label>
                            <input type="text" 
                                   name="customer_name" 
                                   value="{{ old('customer_name', auth()->user()?->name) }}" 
                                   required 
                                   placeholder="مثال: عبدالعزيز السعد" 
                                   class="w-full bg-[#FAF8F4] border border-[#EDE6DA] rounded-lg px-3.5 py-2.5 text-xs text-[#242424] focus:outline-none focus:border-[#C5A059]">
                            @error('customer_name') <span class="text-[11px] text-red-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-[#242424] mb-1.5">رقم الجوال السعودي <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <input type="tel" 
                                       name="customer_phone" 
                                       value="{{ old('customer_phone', auth()->user()?->phone) }}" 
                                       required 
                                       placeholder="05XXXXXXXX" 
                                       class="w-full bg-[#FAF8F4] border border-[#EDE6DA] rounded-lg px-3.5 py-2.5 text-xs text-[#242424] focus:outline-none focus:border-[#C5A059] text-left" 
                                       dir="ltr">
                            </div>
                            @error('customer_phone') <span class="text-[11px] text-red-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div class="sm:col-span-2">
                            <label class="block text-xs font-semibold text-[#242424] mb-1.5">البريد الإلكتروني (اختياري لاستلام الفاتورة)</label>
                            <input type="email" 
                                   name="customer_email" 
                                   value="{{ old('customer_email', auth()->user()?->email) }}" 
                                   placeholder="name@example.com" 
                                   class="w-full bg-[#FAF8F4] border border-[#EDE6DA] rounded-lg px-3.5 py-2.5 text-xs text-[#242424] focus:outline-none focus:border-[#C5A059]">
                        </div>
                    </div>
                </div>

                <!-- Shipping Address -->
                <div class="bg-white p-6 rounded-2xl border border-[#EDE6DA] shadow-xs space-y-4">
                    <h2 class="text-sm font-bold text-[#242424] border-b border-[#EDE6DA] pb-3 flex items-center gap-2">
                        <x-icon name="map-pin" class="w-4 h-4 text-[#C5A059]" />
                        <span>عنوان الشحن والتوصيل</span>
                    </h2>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-[#242424] mb-1.5 flex items-center justify-between">
                                <span>المدينة <span class="text-red-500">*</span></span>
                                <span class="text-[10px] text-emerald-700 bg-emerald-50 border border-emerald-200 px-2 py-0.5 rounded-full font-bold">
                                    توصيل فوري بمكة المكرمة ⚡
                                </span>
                            </label>
                            <select name="shipping_zone_id" 
                                    @change="updateCity($event.target.value)" 
                                    class="w-full bg-[#FAF8F4] border border-[#EDE6DA] rounded-lg px-3.5 py-2.5 text-xs text-[#242424] focus:outline-none focus:border-[#C5A059] font-bold">
                                @foreach($shippingZones as $zone)
                                    <option value="{{ $zone->id }}" {{ old('shipping_zone_id') == $zone->id ? 'selected' : '' }}>
                                        {{ $zone->city_name_ar }} ({{ $zone->shipping_cost }} ر.س)
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-[#242424] mb-1.5">الحي بمكة المكرمة <span class="text-red-500">*</span></label>
                            <input type="text" 
                                   name="district" 
                                   value="{{ old('district') }}" 
                                   required 
                                   placeholder="مثال: حي أم الجود، العزيزية، الشوقية، العوالي..." 
                                   class="w-full bg-[#FAF8F4] border border-[#EDE6DA] rounded-lg px-3.5 py-2.5 text-xs text-[#242424] focus:outline-none focus:border-[#C5A059]">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-[#242424] mb-1.5">اسم الشارع <span class="text-red-500">*</span></label>
                            <input type="text" 
                                   name="street_address" 
                                   value="{{ old('street_address') }}" 
                                   required 
                                   placeholder="مثال: شارع عبد الله بن أبي بكر الصديق" 
                                   class="w-full bg-[#FAF8F4] border border-[#EDE6DA] rounded-lg px-3.5 py-2.5 text-xs text-[#242424] focus:outline-none focus:border-[#C5A059]">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-[#242424] mb-1.5">رقم المبنى / الشقة / معلم قريب</label>
                            <input type="text" 
                                   name="building_info" 
                                   value="{{ old('building_info') }}" 
                                   placeholder="مثال: بجوار جامع الهدى، عمارة 5، شقة 2" 
                                   class="w-full bg-[#FAF8F4] border border-[#EDE6DA] rounded-lg px-3.5 py-2.5 text-xs text-[#242424] focus:outline-none focus:border-[#C5A059]">
                        </div>

                        <div class="sm:col-span-2">
                            <label class="block text-xs font-semibold text-[#242424] mb-1.5">ملاحظات إضافية للتوصيل (اختياري)</label>
                            <textarea name="notes" 
                                      rows="2" 
                                      placeholder="مثال: يرجى الاتصال قبل الوصول بنصف ساعة" 
                                      class="w-full bg-[#FAF8F4] border border-[#EDE6DA] rounded-lg p-3 text-xs text-[#242424] focus:outline-none focus:border-[#C5A059]"></textarea>
                        </div>
                    </div>
                </div>

                <!-- Payment Method Selection -->
                <div class="bg-white p-6 rounded-2xl border border-[#EDE6DA] shadow-xs space-y-4">
                    <h2 class="text-sm font-bold text-[#242424] border-b border-[#EDE6DA] pb-3 flex items-center justify-between">
                        <span class="flex items-center gap-2">
                            <x-icon name="credit-card" class="w-4 h-4 text-[#C5A059]" />
                            <span>طريقة تأكيد الطلب والدفع</span>
                        </span>
                        <span class="text-[10px] bg-amber-50 text-amber-800 border border-amber-200 px-2 py-0.5 rounded-full font-bold">
                            وضع الإطلاق التجريبي 🚀
                        </span>
                    </h2>

                    <div class="space-y-3">
                        @if($whatsappEnabled)
                        <!-- WhatsApp Direct Order (Primary Trial Method) -->
                        <label class="flex items-center justify-between p-4 rounded-xl border cursor-pointer transition"
                               :class="paymentMethod === 'whatsapp' ? 'border-emerald-500 bg-emerald-50/40 ring-1 ring-emerald-500' : 'border-[#EDE6DA] hover:border-gray-300'">
                            <div class="flex items-center gap-3">
                                <input type="radio" name="payment_method" value="whatsapp" x-model="paymentMethod" class="text-emerald-600 focus:ring-emerald-500">
                                <div>
                                    <div class="flex items-center gap-2">
                                        <span class="text-xs font-bold text-[#242424]">تأكيد الطلب عبر واتساب (الدفع عند الاستلام)</span>
                                        <span class="bg-emerald-100 text-emerald-800 font-bold text-[9px] px-1.5 py-0.5 rounded-md">موصى به</span>
                                    </div>
                                    <span class="text-[11px] text-[#777777]">يتم إرسال تفاصيل الفاتورة مباشرة لواتساب الإدارة لتجهيز طلبك وتوصيله بمكة المكرمة فوراً</span>
                                </div>
                            </div>
                            <div class="w-8 h-8 rounded-full bg-[#25D366] text-white flex items-center justify-center shrink-0 shadow-xs">
                                <x-icon name="whatsapp" class="w-4 h-4" />
                            </div>
                        </label>
                        @endif

                        @if($codEnabled && !$whatsappEnabled)
                        <!-- Cash on Delivery Standalone -->
                        <label class="flex items-center justify-between p-4 rounded-xl border cursor-pointer transition"
                               :class="paymentMethod === 'cod' ? 'border-[#C5A059] bg-[#FAF8F4] ring-1 ring-[#C5A059]' : 'border-[#EDE6DA] hover:border-gray-300'">
                            <div class="flex items-center gap-3">
                                <input type="radio" name="payment_method" value="cod" x-model="paymentMethod" class="text-[#C5A059] focus:ring-[#C5A059]">
                                <div>
                                    <span class="text-xs font-bold text-[#242424] block">الدفع عند الاستلام (COD)</span>
                                    <span class="text-[11px] text-[#777777]">ادفع نقداً أو بجهاز مدى لمندوب التوصيل في مكة</span>
                                </div>
                            </div>
                            <x-icon name="truck" class="w-5 h-5 text-[#A59D92]" />
                        </label>
                        @endif

                        @if($paymobEnabled)
                        <!-- Paymob Online (Mada, Visa, Apple Pay) -->
                        <label class="flex items-center justify-between p-4 rounded-xl border cursor-pointer transition"
                               :class="paymentMethod === 'paymob' ? 'border-[#C5A059] bg-[#FAF8F4] ring-1 ring-[#C5A059]' : 'border-[#EDE6DA] hover:border-gray-300'">
                            <div class="flex items-center gap-3">
                                <input type="radio" name="payment_method" value="paymob" x-model="paymentMethod" class="text-[#C5A059] focus:ring-[#C5A059]">
                                <div>
                                    <span class="text-xs font-bold text-[#242424] block">بطاقة مدى / فيزا / ماستركارد / Apple Pay</span>
                                    <span class="text-[11px] text-[#777777]">دفع إلكتروني فوري وآمن عبر بوابة Paymob</span>
                                </div>
                            </div>
                            <div class="flex items-center gap-1.5">
                                <div class="h-6 px-1 bg-white rounded border border-[#EDE6DA] flex items-center justify-center">
                                    <img src="{{ asset('images/payments/mada.png') }}" alt="mada" class="h-4.5 w-auto object-contain">
                                </div>
                                <div class="h-6 px-1 bg-white rounded border border-[#EDE6DA] flex items-center justify-center">
                                    <img src="{{ asset('images/payments/apple-pay.png') }}" alt="Apple Pay" class="h-4 w-auto object-contain">
                                </div>
                                <div class="h-6 px-1 bg-white rounded border border-[#EDE6DA] flex items-center justify-center">
                                    <img src="{{ asset('images/payments/visa-mastercard.png') }}" alt="Visa Mastercard" class="h-4 w-auto object-contain">
                                </div>
                            </div>
                        </label>
                        @endif

                        @if($tamaraEnabled)
                        <!-- Tamara (قسّمها على 4 دفعات) -->
                        <label class="flex items-center justify-between p-4 rounded-xl border cursor-pointer transition"
                               :class="paymentMethod === 'tamara' ? 'border-[#C5A059] bg-[#FAF8F4] ring-1 ring-[#C5A059]' : 'border-[#EDE6DA] hover:border-gray-300'">
                            <div class="flex items-center gap-3">
                                <input type="radio" name="payment_method" value="tamara" x-model="paymentMethod" class="text-[#C5A059] focus:ring-[#C5A059]">
                                <div>
                                    <div class="flex items-center gap-2">
                                        <span class="text-xs font-bold text-[#242424]">تمارا | قسّم فاتورتك على 4 دفعات</span>
                                        <span class="bg-gradient-to-r from-[#FFB4A2] to-[#9CD5FF] text-black font-extrabold text-[9px] px-1.5 py-0.5 rounded">بدون فوائد</span>
                                    </div>
                                    <span class="text-[11px] text-[#777777]">ادفع 25% اليوم وقسّم الباقي على 3 أشهر بدون فوائد</span>
                                </div>
                            </div>
                            <div class="h-6 px-1.5 bg-white rounded border border-[#EDE6DA] flex items-center justify-center">
                                <img src="{{ asset('images/payments/tamara.png') }}" alt="تمارا Tamara" class="h-4.5 w-auto object-contain">
                            </div>
                        </label>
                        @endif

                        @if($tabbyEnabled)
                        <!-- Tabby (قسّمها على 4 دفعات) -->
                        <label class="flex items-center justify-between p-4 rounded-xl border cursor-pointer transition"
                               :class="paymentMethod === 'tabby' ? 'border-[#C5A059] bg-[#FAF8F4] ring-1 ring-[#C5A059]' : 'border-[#EDE6DA] hover:border-gray-300'">
                            <div class="flex items-center gap-3">
                                <input type="radio" name="payment_method" value="tabby" x-model="paymentMethod" class="text-[#C5A059] focus:ring-[#C5A059]">
                                <div>
                                    <div class="flex items-center gap-2">
                                        <span class="text-xs font-bold text-[#242424]">تابي | قسّم مشترياتك على 4 دفعات</span>
                                        <span class="bg-[#3BFF9E] text-black font-black text-[9px] px-1.5 py-0.5 rounded">بدون فوائد</span>
                                    </div>
                                    <span class="text-[11px] text-[#777777]">قسّم دفعاتك على 4 أشهر بدون أي رسوم إضافية</span>
                                </div>
                            </div>
                            <div class="h-6 px-1.5 bg-white rounded border border-[#EDE6DA] flex items-center justify-center">
                                <img src="{{ asset('images/payments/tabby.png') }}" alt="تابي Tabby" class="h-4.5 w-auto object-contain">
                            </div>
                        </label>
                        @endif
                    </div>

                    <!-- Security Alert -->
                    <div class="p-3 bg-emerald-50/60 rounded-lg border border-emerald-200/70 flex items-center gap-2 text-[11px] text-emerald-800">
                        <x-icon name="shield-check" class="w-4 h-4 text-emerald-600 flex-shrink-0" />
                        <span>متجر زاد نيكس موثق بسجل تجاري رسمي رقم 7055435114 وشهادة سلامة الغذاء 101565890 بمكة المكرمة.</span>
                    </div>
                </div>

            </div>

            <!-- Order Summary Sidebar (1 Col) -->
            <div class="space-y-6">
                <div class="bg-white p-6 rounded-2xl border border-[#EDE6DA] shadow-xs space-y-4">
                    <h3 class="text-sm font-bold text-[#242424] border-b border-[#EDE6DA] pb-3 flex items-center justify-between">
                        <span>محتويات الطلب</span>
                        <span class="text-xs text-[#777777]">({{ $cart->items->sum('quantity') }} قطع)</span>
                    </h3>

                    <!-- Items mini list -->
                    <div class="divide-y divide-[#EDE6DA]/60 max-h-64 overflow-y-auto">
                        @foreach($cart->items as $item)
                        <div class="py-2.5 flex items-center justify-between text-xs">
                            <div class="flex items-center gap-2">
                                <img src="{{ $item->product->primary_image }}" alt="{{ $item->product->name_ar }}" class="w-10 h-10 object-cover rounded-md border border-[#EDE6DA]">
                                <div>
                                    <h4 class="font-bold text-[#242424] truncate max-w-[130px]">{{ $item->product->name_ar }}</h4>
                                    <span class="text-[10px] text-[#777777]">{{ $item->quantity }} × {{ number_format($item->price, 2) }} ر.س</span>
                                </div>
                            </div>
                            <span class="font-bold text-[#242424]">{{ number_format($item->total, 2) }} ر.س</span>
                        </div>
                        @endforeach
                    </div>

                    <!-- Totals -->
                    <div class="border-t border-[#EDE6DA] pt-4 space-y-2.5 text-xs text-[#777777]">
                        <div class="flex items-center justify-between">
                            <span>المجموع الفرعي:</span>
                            <span class="font-bold text-[#242424]">{{ number_format($subtotal, 2) }} ر.س</span>
                        </div>

                        @if($discount > 0)
                        <div class="flex items-center justify-between text-emerald-600">
                            <span>الخصم المطبق:</span>
                            <span class="font-bold">- {{ number_format($discount, 2) }} ر.س</span>
                        </div>
                        @endif

                        <div class="flex items-center justify-between">
                            <span>تكلفة الشحن:</span>
                            <span class="font-bold text-[#242424]" x-text="shippingCost > 0 ? shippingCost.toFixed(2) + ' ر.س' : 'شحن مجاني'"></span>
                        </div>
                    </div>

                    <div class="border-t border-[#EDE6DA] pt-4 flex items-center justify-between text-base font-black text-[#242424]">
                        <span>المبلغ الإجمالي:</span>
                        <span class="text-2xl text-[#C5A059]" x-text="finalTotal + ' ر.س'"></span>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" 
                            :class="paymentMethod === 'whatsapp' ? 'bg-[#25D366] hover:bg-[#1EBE5D] text-white shadow-emerald-500/20' : 'bg-[#0B2E75] hover:bg-[#071E50] text-[#FBB404] shadow-blue-900/20'"
                            class="w-full font-black text-sm py-4 px-4 rounded-xl shadow-lg flex items-center justify-center gap-2 transition duration-200 cursor-pointer">
                        <template x-if="paymentMethod === 'whatsapp'">
                            <span class="flex items-center gap-2">
                                <x-icon name="whatsapp" class="w-5 h-5 text-white" />
                                <span>تأكيد الطلب والإرسال عبر واتساب ⚡</span>
                            </span>
                        </template>
                        <template x-if="paymentMethod !== 'whatsapp'">
                            <span class="flex items-center gap-2">
                                <x-icon name="shield-check" class="w-5 h-5 text-[#FBB404]" />
                                <span>تأكيد الطلب الآن</span>
                            </span>
                        </template>
                    </button>

                    <a href="{{ route('cart.index') }}" class="w-full text-center block text-xs font-semibold text-[#777777] hover:text-[#C5A059] pt-1 transition">
                        &larr; العودة إلى سلة المشتريات وتعديل المنتجات
                    </a>
                </div>
            </div>

        </form>
    </div>
</x-app-layout>

