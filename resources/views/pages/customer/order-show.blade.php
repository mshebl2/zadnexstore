<x-app-layout>
    <x-slot:title>تفاصيل الطلب {{ $order->order_number }} | متجر زاد نيكس</x-slot:title>
    <x-slot:description>فاتورة وتفاصيل طلب المنتجات الغذائية والتموينية من متجر زاد نيكس.</x-slot:description>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10 sm:py-14 text-right">
        
        <!-- Breadcrumb & Back -->
        <div class="flex items-center justify-between pb-6 border-b border-[#EDE6DA] mb-6">
            <div>
                <nav class="text-xs text-[#777777] mb-1 flex items-center gap-2">
                    <a href="{{ route('home') }}" class="hover:text-[#C5A059]">الرئيسية</a>
                    <span>/</span>
                    <a href="{{ route('customer.dashboard', ['tab' => 'orders']) }}" class="hover:text-[#C5A059]">طلباتي</a>
                    <span>/</span>
                    <span class="text-[#242424] font-bold">{{ $order->order_number }}</span>
                </nav>
                <h1 class="text-xl sm:text-2xl font-extrabold text-[#242424]">تفاصيل الفاتورة وتتبع الطلب</h1>
            </div>
            <a href="{{ route('customer.dashboard', ['tab' => 'orders']) }}" class="bg-[#FAF8F4] hover:bg-[#EDE6DA] text-[#242424] text-xs font-bold px-4 py-2 rounded-xl border border-[#EDE6DA] transition flex items-center gap-1.5">
                <x-icon name="arrow-right" class="w-3.5 h-3.5" />
                <span>العودة لطلباتي</span>
            </a>
        </div>

        <!-- Order Summary Card -->
        <div class="bg-white rounded-3xl border border-[#EDE6DA] p-6 sm:p-8 shadow-xs space-y-6">
            
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 pb-6 border-b border-[#FAF8F4]">
                <div>
                    <span class="text-xs text-[#A59D92]">رقم الطلب</span>
                    <h2 class="text-lg font-mono font-black text-[#242424] tracking-wider">{{ $order->order_number }}</h2>
                    <span class="text-xs text-[#777777] mt-0.5 block">تاريخ الطلب: {{ $order->created_at->format('Y-m-d — h:i A') }}</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="px-3 py-1.5 rounded-xl text-xs font-bold {{ $order->payment_status === 'paid' ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">
                        {{ $order->payment_status_label_ar }}
                    </span>
                    <span class="px-3 py-1.5 rounded-xl text-xs font-bold bg-blue-50 text-blue-800">
                        {{ $order->status_label_ar }}
                    </span>
                </div>
            </div>

            <!-- Shipment Tracking Progress Timeline -->
            <div class="py-2">
                <h3 class="text-xs font-bold text-[#A59D92] uppercase mb-4">مسار معالجة الشحنة</h3>
                <div class="grid grid-cols-4 gap-2 text-center text-xs">
                    
                    <!-- Step 1: Received -->
                    <div class="space-y-1.5">
                        <div class="w-8 h-8 rounded-full bg-[#C5A059] text-white flex items-center justify-center mx-auto shadow-xs">
                            <x-icon name="check" class="w-4 h-4" />
                        </div>
                        <span class="font-bold text-[#242424] block text-[11px]">تم استلام الطلب</span>
                    </div>

                    <!-- Step 2: Processing -->
                    <div class="space-y-1.5">
                        <div class="w-8 h-8 rounded-full {{ in_array($order->status, ['processing', 'shipped', 'delivered']) ? 'bg-[#C5A059] text-white' : 'bg-gray-100 text-gray-400' }} flex items-center justify-center mx-auto">
                            <x-icon name="package" class="w-4 h-4" />
                        </div>
                        <span class="font-bold text-[#242424] block text-[11px]">جاري التجهيز والتغليف</span>
                    </div>

                    <!-- Step 3: Shipped -->
                    <div class="space-y-1.5">
                        <div class="w-8 h-8 rounded-full {{ in_array($order->status, ['shipped', 'delivered']) ? 'bg-[#C5A059] text-white' : 'bg-gray-100 text-gray-400' }} flex items-center justify-center mx-auto">
                            <x-icon name="truck" class="w-4 h-4" />
                        </div>
                        <span class="font-bold text-[#242424] block text-[11px]">تم تسليم الشحنة</span>
                    </div>

                    <!-- Step 4: Delivered -->
                    <div class="space-y-1.5">
                        <div class="w-8 h-8 rounded-full {{ $order->status === 'delivered' ? 'bg-[#C5A059] text-white' : 'bg-gray-100 text-gray-400' }} flex items-center justify-center mx-auto">
                            <x-icon name="home" class="w-4 h-4" />
                        </div>
                        <span class="font-bold text-[#242424] block text-[11px]">تم التوصيل بنجاح</span>
                    </div>

                </div>
            </div>

            @if($order->shipping_tracking_number)
            <!-- Live Mapit Tracking Badge -->
            <div class="p-4 bg-[#FAF8F4] border border-[#C5A059]/40 rounded-2xl flex flex-col sm:flex-row items-center justify-between gap-3 text-xs">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-xl bg-[#C5A059] text-white flex items-center justify-center shrink-0">
                        <x-icon name="truck" class="w-4 h-4" />
                    </div>
                    <div>
                        <span class="text-[#777777] block text-[11px]">شركة الشحن المعتمدة: <strong>مابت (MapIt Logistics)</strong></span>
                        <span class="font-bold text-[#242424]">رقم تتبع الشحنة: <strong class="font-mono text-[#C5A059]">{{ $order->shipping_tracking_number }}</strong></span>
                    </div>
                </div>
                <a href="{{ $order->shipping_label_url ?: 'https://www.mapit.sa/customer/track/' . $order->shipping_tracking_number }}" target="_blank" class="bg-[#242424] hover:bg-black text-[#C5A059] px-4 py-2 rounded-xl font-bold transition flex items-center gap-1.5 shadow-xs whitespace-nowrap">
                    <span>تتبع شحنتك لحظة بلحظة &larr;</span>
                </a>
            </div>
            @endif

            <!-- Items Table -->
            <div class="pt-4 border-t border-[#FAF8F4]">
                <h3 class="text-xs font-bold text-[#242424] mb-3">المنتجات المشمولة في الطلب</h3>
                <div class="divide-y divide-[#FAF8F4] border border-[#EDE6DA] rounded-2xl overflow-hidden">
                    @foreach($order->items as $item)
                    <div class="p-4 flex items-center justify-between text-xs hover:bg-[#FAF8F4]/50 transition">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 rounded-xl bg-[#FAF8F4] border border-[#EDE6DA] overflow-hidden shrink-0 flex items-center justify-center">
                                @if($item->image_url)
                                    <img src="{{ $item->image_url }}" alt="{{ $item->product_name }}" class="w-full h-full object-cover">
                                @else
                                    <x-icon name="package" class="w-5 h-5 text-[#C5A059]" />
                                @endif
                            </div>
                            <div>
                                <h4 class="font-bold text-[#242424] text-xs sm:text-sm">{{ $item->product_name }}</h4>
                                <span class="text-[11px] text-[#A59D92]">الكمية: {{ $item->quantity }} × {{ number_format($item->unit_price, 2) }} ر.س</span>
                            </div>
                        </div>
                        <div class="text-left font-mono font-bold text-[#242424] text-sm" dir="ltr">
                            {{ number_format($item->total_price, 2) }} SAR
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Totals & Shipping Address Summary -->
            <div class="pt-4 grid grid-cols-1 sm:grid-cols-2 gap-6 items-start">
                
                <!-- Shipping Info -->
                <div class="bg-[#FAF8F4] p-5 rounded-2xl border border-[#EDE6DA] space-y-2 text-xs">
                    <span class="font-bold text-[#C5A059] block">عنوان ومعلومات التوصيل</span>
                    <p class="text-[#242424]"><strong>الاسم:</strong> {{ $order->customer_name }}</p>
                    <p class="text-[#242424]"><strong>الجوال:</strong> {{ $order->customer_phone }}</p>
                    <p class="text-[#242424]"><strong>المدينة:</strong> {{ $order->city }}</p>
                    <p class="text-[#242424]"><strong>تفاصيل العنوان:</strong> {{ $order->street_address }}</p>
                    <p class="text-[#242424]"><strong>طريقة الدفع:</strong> {{ $order->payment_method_label_ar }}</p>
                </div>

                <!-- Financial Calculation -->
                <div class="bg-white p-5 rounded-2xl border border-[#EDE6DA] space-y-2 text-xs">
                    <span class="font-bold text-[#242424] block pb-2 border-b border-[#FAF8F4]">ملخص الفاتورة</span>
                    
                    <div class="flex items-center justify-between text-[#777777]">
                        <span>المجموع الفرعي:</span>
                        <span class="font-mono">{{ number_format($order->subtotal, 2) }} ر.س</span>
                    </div>

                    <div class="flex items-center justify-between text-[#777777]">
                        <span>تكلفة الشحن والتوصيل:</span>
                        <span class="font-mono">{{ $order->shipping_cost > 0 ? number_format($order->shipping_cost, 2) . ' ر.س' : 'مجاني' }}</span>
                    </div>

                    @if($order->discount_amount > 0)
                    <div class="flex items-center justify-between text-emerald-600 font-medium">
                        <span>الخصم المطبق ({{ $order->coupon_code }}):</span>
                        <span class="font-mono">- {{ number_format($order->discount_amount, 2) }} ر.س</span>
                    </div>
                    @endif

                    <div class="pt-3 border-t border-[#EDE6DA] flex items-center justify-between font-bold text-sm text-[#242424]">
                        <span>المجموع الإجمالي (شامل الضريبة 15%):</span>
                        <span class="font-mono text-base text-[#C5A059]">{{ number_format($order->total, 2) }} ر.س</span>
                    </div>
                </div>

            </div>

        </div>

    </div>
</x-app-layout>

