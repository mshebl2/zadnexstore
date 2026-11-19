<x-app-layout>
    <x-slot:title>تم استلام طلبك بنجاح #{{ $order->order_number }} | متجر زاد نيكس</x-slot:title>

    @php
        $whatsappLink = session('whatsapp_url');
        if (!$whatsappLink) {
            $cleanPhone = preg_replace('/[^0-9]/', '', \App\Models\StoreSetting::get('whatsapp_number', '966580760890'));
            if (!str_starts_with($cleanPhone, '966')) {
                $cleanPhone = '966' . ltrim($cleanPhone, '0');
            }
            $itemsSummary = $order->items->map(fn($it) => "▫️ " . $it->product_name . " × " . $it->quantity)->implode("\n");
            $defaultMsg = "🛒 *تأكيد طلب من متجر زاد نيكس*\n"
                        . "━━━━━━━━━━━━━━━━━━━━\n"
                        . "🔖 *رقم الطلب:* #{$order->order_number}\n"
                        . "👤 *الاسم:* {$order->customer_name}\n"
                        . "📱 *الجوال:* {$order->customer_phone}\n"
                        . "📍 *العنوان:* مكة المكرمة - {$order->district} - {$order->street_address}\n"
                        . "📦 *المنتجات:*\n{$itemsSummary}\n"
                        . "━━━━━━━━━━━━━━━━━━━━\n"
                        . "💰 *المبلغ المستحق:* " . number_format($order->total, 2) . " ر.س (الدفع عند الاستلام)\n"
                        . "أرجو تأكيد التجهيز والتوصيل، شكراً لكم!";
            $whatsappLink = "https://wa.me/{$cleanPhone}?text=" . rawurlencode($defaultMsg);
        }
    @endphp

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10 text-right">
        
        <!-- Primary WhatsApp Action Card (Trial Launch Highlight) -->
        <div class="bg-gradient-to-r from-emerald-50 via-white to-emerald-50 rounded-3xl border-2 border-emerald-400 p-6 sm:p-8 shadow-md text-center space-y-4 mb-8">
            <div class="w-16 h-16 rounded-full bg-emerald-500 text-white flex items-center justify-center mx-auto shadow-md shadow-emerald-500/20">
                <x-icon name="whatsapp" class="w-9 h-9" />
            </div>

            <span class="inline-block bg-emerald-100 text-emerald-800 text-xs font-bold px-3.5 py-1 rounded-full">
                🚀 مرحلة الإطلاق التجريبي — توصيل حصري بمكة المكرمة
            </span>

            <h1 class="text-2xl sm:text-3xl font-black text-[#242424]">
                شكراً لتسوقك من متجر زاد نيكس، {{ $order->customer_name }}!
            </h1>

            <p class="text-xs sm:text-sm text-[#555555] max-w-xl mx-auto leading-relaxed">
                تم تسجيل طلبك بنجاح برقم: <strong class="text-[#0B2E75] font-mono text-base font-extrabold">#{{ $order->order_number }}</strong>.
                <br>
                يُرجى الضغط على الزر الأخضر بالأسفل لإرسال تفاصيل الفاتورة مباشرة إلى واتساب الإدارة لتأكيد موعد التوصيل الفوري لموقعك بمكة المكرمة والدفع عند الاستلام.
            </p>

            <div class="pt-2 flex flex-col sm:flex-row items-center justify-center gap-3">
                <a href="{{ $whatsappLink }}" 
                   target="_blank" 
                   id="whatsappActionBtn"
                   class="w-full sm:w-auto bg-[#25D366] hover:bg-[#1EBE5D] text-white text-sm font-black px-8 py-4 rounded-xl flex items-center justify-center gap-2.5 shadow-lg shadow-emerald-600/30 transition transform hover:-translate-y-0.5">
                    <x-icon name="whatsapp" class="w-5 h-5" />
                    <span>إرسال تفاصيل الطلب عبر واتساب لتأكيد التوصيل ⚡</span>
                </a>

                <button onclick="window.print()" type="button" class="w-full sm:w-auto bg-white hover:bg-gray-50 border border-gray-300 text-gray-700 text-xs font-bold px-5 py-4 rounded-xl flex items-center justify-center gap-2 transition">
                    <x-icon name="package" class="w-4 h-4" />
                    <span>طباعة الفاتورة</span>
                </button>
            </div>
        </div>

        <!-- Order Summary & Details Card -->
        <div class="bg-white rounded-3xl border border-[#EDE6DA] p-6 sm:p-8 shadow-xs space-y-6">
            
            <div class="flex items-center justify-between border-b border-[#EDE6DA] pb-4 flex-wrap gap-3">
                <div>
                    <span class="text-xs text-[#777777] block">حالة الدفع:</span>
                    <span class="inline-block mt-1 text-xs font-bold px-2.5 py-1 rounded-md bg-amber-100 text-amber-800">
                        {{ $order->payment_status === 'paid' ? 'تم الدفع' : 'الدفع عند الاستلام (مكة المكرمة)' }}
                    </span>
                </div>

                <div>
                    <span class="text-xs text-[#777777] block">حالة الشحنة:</span>
                    <span class="inline-block mt-1 text-xs font-bold px-2.5 py-1 rounded-md bg-blue-50 text-blue-800">
                        قيد التجهيز والتوصيل
                    </span>
                </div>

                <div>
                    <span class="text-xs text-[#777777] block">طريقة الدفع:</span>
                    <span class="text-xs font-bold text-[#242424] mt-1 block">
                        @if($order->payment_method === 'whatsapp')
                            الطلب والتأكيد عبر واتساب (الدفع عند الاستلام)
                        @elseif($order->payment_method === 'cod')
                            الدفع عند الاستلام (COD)
                        @elseif($order->payment_method === 'tamara')
                            تمارا (تقسيط 4 دفعات)
                        @else
                            بطاقة بنكية إلكترونية
                        @endif
                    </span>
                </div>

                <div>
                    <span class="text-xs text-[#777777] block">تاريخ الطلب:</span>
                    <span class="text-xs text-[#242424] font-medium mt-1 block">{{ $order->created_at->format('Y-m-d H:i') }}</span>
                </div>
            </div>

            <!-- Shipping address box -->
            <div class="p-4 bg-[#FAF8F4] rounded-xl border border-[#EDE6DA] text-xs space-y-1">
                <h3 class="font-bold text-[#242424] mb-1 flex items-center gap-1.5">
                    <x-icon name="map-pin" class="w-4 h-4 text-[#FBB404]" />
                    <span>عنوان التوصيل في مكة المكرمة:</span>
                </h3>
                <p class="text-[#242424] font-semibold">{{ $order->customer_name }} — {{ $order->customer_phone }}</p>
                <p class="text-[#555555]">مكة المكرمة، {{ $order->district }}، {{ $order->street_address }} @if($order->building_info) ({{ $order->building_info }}) @endif</p>
                @if($order->notes)
                    <p class="text-[#A59D92] mt-1 italic">ملاحظات التوصيل: {{ $order->notes }}</p>
                @endif
            </div>

            <!-- Items Table -->
            <div class="divide-y divide-[#EDE6DA]/70">
                @foreach($order->items as $item)
                <div class="py-3 flex items-center justify-between text-xs">
                    <div class="flex items-center gap-3">
                        <img src="{{ $item->image_url ?? 'https://images.unsplash.com/photo-1542838132-92c53300491e?auto=format&fit=crop&w=400&q=80' }}" alt="{{ $item->product_name }}" class="w-12 h-12 object-cover rounded-lg border border-[#EDE6DA]">
                        <div>
                            <h4 class="font-bold text-[#242424]">{{ $item->product_name }}</h4>
                            @if($item->variant_name)
                                <span class="text-[10px] text-[#777777]">{{ $item->variant_name }}</span>
                            @endif
                            <div class="text-[11px] text-[#A59D92]">الكمية: {{ $item->quantity }} × {{ number_format($item->unit_price, 2) }} ر.س</div>
                        </div>
                    </div>
                    <span class="font-bold text-[#242424]">{{ number_format($item->total_price, 2) }} ر.س</span>
                </div>
                @endforeach
            </div>

            <!-- Totals -->
            <div class="border-t border-[#EDE6DA] pt-4 space-y-2 text-xs text-[#777777]">
                <div class="flex items-center justify-between">
                    <span>المجموع الفرعي:</span>
                    <span class="font-bold text-[#242424]">{{ number_format($order->subtotal, 2) }} ر.س</span>
                </div>

                @if($order->discount_amount > 0)
                <div class="flex items-center justify-between text-emerald-600">
                    <span>الخصم المطبق:</span>
                    <span class="font-bold">- {{ number_format($order->discount_amount, 2) }} ر.س</span>
                </div>
                @endif

                <div class="flex items-center justify-between">
                    <span>رسوم التوصيل (مكة المكرمة):</span>
                    <span class="font-bold text-[#242424]">{{ $order->shipping_cost > 0 ? number_format($order->shipping_cost, 2) . ' ر.س' : 'مجاني' }}</span>
                </div>

                <div class="border-t border-[#EDE6DA] pt-3 flex items-center justify-between text-base font-black text-[#242424]">
                    <span>المبلغ الإجمالي المستحق:</span>
                    <span class="text-2xl text-[#0B2E75] font-black">{{ number_format($order->total, 2) }} ر.س</span>
                </div>
            </div>

            <div class="pt-4 flex items-center justify-center gap-4 flex-wrap">
                <a href="{{ route('home') }}" class="inline-block bg-[#0B2E75] hover:bg-[#071E50] text-[#FBB404] text-xs font-bold px-8 py-3.5 rounded-xl transition">
                    العودة للتسوق في متجر زاد نيكس
                </a>
            </div>

        </div>

    </div>

    @if(session('whatsapp_url'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Auto open WhatsApp tab in background after 1.5s for seamless customer experience
            setTimeout(function() {
                var url = @json(session('whatsapp_url'));
                if (url) {
                    window.open(url, '_blank');
                }
            }, 1200);
        });
    </script>
    @endif
</x-app-layout>
