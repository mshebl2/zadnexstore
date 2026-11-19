<x-admin-layout>
    <x-slot:title>تفاصيل الطلب: {{ $order->order_number }}</x-slot:title>

    <div class="max-w-4xl mx-auto space-y-6 text-right">
        
        <!-- Header Actions Card -->
        <div class="bg-white rounded-2xl border border-gray-200 p-6 shadow-xs flex flex-col sm:flex-row items-center justify-between gap-4 print:hidden">
            <div>
                <span class="text-xs text-gray-500">رقم الطلب:</span>
                <h2 class="text-xl font-bold font-mono text-[#242424]">{{ $order->order_number }}</h2>
                <span class="text-xs text-gray-400 mt-0.5 block">حرر بتاريخ: {{ $order->created_at->format('Y-m-d H:i:s') }}</span>
            </div>

            <div class="flex items-center gap-2">
                <a href="{{ route('admin.orders.invoice', $order->id) }}" target="_blank" class="bg-[#242424] hover:bg-black text-[#0B2E75] text-xs font-bold px-4 py-2.5 rounded-xl transition flex items-center gap-2 shadow-xs">
                    <x-icon name="printer" class="w-4 h-4" />
                    <span>طباعة الفاتورة الرسمية</span>
                </a>
                <form action="{{ route('admin.orders.destroy', $order->id) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من حذف هذا الطلب نهائياً وكافة سجلاته؟ لا يمكن التراجع عن هذا الإجراء.');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-50 hover:bg-red-600 hover:text-white text-red-600 border border-red-200 text-xs font-bold px-3 py-2.5 rounded-xl transition flex items-center gap-1.5 shadow-xs">
                        <x-icon name="trash" class="w-4 h-4" />
                        <span>حذف الطلب</span>
                    </button>
                </form>
            </div>
        </div>

        <!-- Status Updates Form -->
        <div class="bg-white rounded-2xl border border-gray-200 p-6 shadow-xs print:hidden">
            <h3 class="text-xs font-bold text-gray-700 uppercase mb-4">تحديث حالات الطلب</h3>
            <form action="{{ route('admin.orders.status', $order->id) }}" method="POST" class="grid grid-cols-1 sm:grid-cols-3 gap-4 items-end">
                @csrf
                @method('PATCH')

                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">حالة التوصيل والشحن:</label>
                    <select name="status" class="w-full bg-[#F8FAFC] border border-gray-300 rounded-lg px-3 py-2 text-xs focus:outline-none focus:border-[#0B2E75]">
                        <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>قيد المراجعة</option>
                        <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>قيد التجهيز</option>
                        <option value="shipped" {{ $order->status === 'shipped' ? 'selected' : '' }}>تم الشحن للمندوب</option>
                        <option value="delivered" {{ $order->status === 'delivered' ? 'selected' : '' }}>تم التوصيل للعميل</option>
                        <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>ملغي</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">حالة السداد والدفع:</label>
                    <select name="payment_status" class="w-full bg-[#F8FAFC] border border-gray-300 rounded-lg px-3 py-2 text-xs focus:outline-none focus:border-[#0B2E75]">
                        <option value="unpaid" {{ $order->payment_status === 'unpaid' ? 'selected' : '' }}>غير مدفوع</option>
                        <option value="paid" {{ $order->payment_status === 'paid' ? 'selected' : '' }}>مدفوع ومؤكد</option>
                        <option value="failed" {{ $order->payment_status === 'failed' ? 'selected' : '' }}>فشل الدفع</option>
                        <option value="refunded" {{ $order->payment_status === 'refunded' ? 'selected' : '' }}>مسترد</option>
                    </select>
                </div>

                <button type="submit" class="bg-[#0B2E75] hover:bg-[#071E50] text-white text-xs font-bold py-2.5 px-4 rounded-lg transition">
                    حفظ التعديلات
                </button>
            </form>
        </div>

        <!-- Customer & Delivery Info -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <div class="bg-white rounded-2xl border border-gray-200 p-6 shadow-xs space-y-2 text-xs">
                <h3 class="text-sm font-bold text-[#242424] border-b border-gray-100 pb-2 flex items-center gap-2">
                    <x-icon name="user" class="w-4 h-4 text-[#0B2E75]" />
                    <span>بيانات العميل</span>
                </h3>
                <p><strong>الاسم:</strong> {{ $order->customer_name }}</p>
                <p><strong>رقم الجوال:</strong> <a href="tel:{{ $order->customer_phone }}" class="text-[#0B2E75] font-mono">{{ $order->customer_phone }}</a></p>
                <p><strong>البريد:</strong> {{ $order->customer_email ?? 'غير محدد' }}</p>
                <p><strong>طريقة الدفع:</strong> {{ $order->payment_method === 'cod' ? 'الدفع عند الاستلام' : 'بطاقة إلكترونية (Paymob)' }}</p>
            </div>

            <div class="bg-white rounded-2xl border border-gray-200 p-6 shadow-xs space-y-2 text-xs">
                <h3 class="text-sm font-bold text-[#242424] border-b border-gray-100 pb-2 flex items-center gap-2">
                    <x-icon name="map-pin" class="w-4 h-4 text-[#0B2E75]" />
                    <span>عنوان التوصيل</span>
                </h3>
                <p><strong>المدينة:</strong> {{ $order->city }}</p>
                <p><strong>الحي والشارع:</strong> {{ $order->district }} - {{ $order->street_address }}</p>
                <p><strong>المبنى:</strong> {{ $order->building_info ?? '—' }}</p>
                @if($order->notes)
                    <p class="text-amber-800 bg-amber-50 p-2 rounded"><strong>ملاحظة العميل:</strong> {{ $order->notes }}</p>
                @endif
            </div>
        </div>

        <!-- MapIt Shipping & Logistics Card -->
        <div class="bg-white rounded-2xl border border-[#E2E8F0] p-6 shadow-xs text-xs space-y-4 print:hidden">
            <div class="flex items-center justify-between border-b border-gray-100 pb-3">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-lg bg-[#F8FAFC] border border-[#0B2E75]/40 flex items-center justify-center font-bold text-[#0B2E75]">
                        <x-icon name="truck" class="w-4 h-4" />
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-[#242424]">الشحن والتوصيل عبر منصة مابت (MapIt)</h3>
                        <p class="text-[11px] text-gray-500">الربط الآلي مع شركات الشحن وإصدار البوالص السريعة داخل المملكة</p>
                    </div>
                </div>
                <span class="text-[10px] font-bold px-2.5 py-1 rounded-full {{ $order->shipping_tracking_number ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-gray-100 text-gray-600' }}">
                    {{ $order->shipping_tracking_number ? '● تم إصدار البوليصة' : '● بانتظار الإصدار' }}
                </span>
            </div>

            @if($order->shipping_tracking_number)
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 bg-[#F8FAFC] p-4 rounded-xl border border-[#E2E8F0]">
                    <div>
                        <span class="text-gray-500 block text-[11px]">شركة الخدمات اللوجستية:</span>
                        <strong class="text-sm font-bold text-[#242424]">مابت (MapIt Logistics)</strong>
                    </div>
                    <div>
                        <span class="text-gray-500 block text-[11px]">رقم الشحنة / البوليصة:</span>
                        <strong class="text-sm font-bold text-[#0B2E75] font-mono">{{ $order->shipping_tracking_number }}</strong>
                    </div>
                    <div>
                        <span class="text-gray-500 block text-[11px]">حالة الشحنة في مابت:</span>
                        <span class="inline-block mt-0.5 px-2 py-0.5 rounded text-[11px] font-bold bg-amber-100 text-amber-900">
                            {{ $order->shipping_status ?? 'قيد المعالجة' }}
                        </span>
                    </div>
                </div>

                <div class="flex items-center gap-3 pt-1">
                    @if($order->shipping_label_url)
                        <a href="{{ $order->shipping_label_url }}" target="_blank" class="bg-[#242424] hover:bg-black text-[#0B2E75] text-xs font-bold px-4 py-2.5 rounded-xl transition flex items-center gap-1.5 shadow-xs">
                            <x-icon name="search" class="w-4 h-4" />
                            <span>تتبع الشحنة المباشر على مابت &larr;</span>
                        </a>
                    @endif
                    <form action="{{ route('admin.orders.ship.mapit', $order->id) }}" method="POST" onsubmit="return confirm('هل تريد إعادة إرسال/تحديث الشحنة لدى مابت؟')">
                        @csrf
                        <button type="submit" class="bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-semibold px-4 py-2.5 rounded-xl transition">
                            إعادة تحديث بيانات الشحنة
                        </button>
                    </form>
                </div>
            @else
                <div class="flex flex-col sm:flex-row items-center justify-between gap-4 bg-[#F8FAFC] p-4 rounded-xl border border-[#E2E8F0]">
                    <div class="space-y-1">
                        <p class="font-bold text-[#242424]">هل الطلب جاهز للشحن؟</p>
                        <p class="text-[11px] text-gray-500">اضغط على الزر ليتم إرسال بيانات العميل والعنوان فوراً إلى منصة مابت وإصدار رقم التتبع والبوليصة تلقائياً.</p>
                    </div>
                    <form action="{{ route('admin.orders.ship.mapit', $order->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="bg-[#0B2E75] hover:bg-[#071E50] text-white text-xs font-bold px-6 py-3 rounded-xl transition duration-200 flex items-center gap-2 shadow-sm whitespace-nowrap">
                            <x-icon name="truck" class="w-4 h-4" />
                            <span>إصدار بوليصة شحن مابت الآن 🚀</span>
                        </button>
                    </form>
                </div>
            @endif
        </div>

        <!-- Items Table -->
        <div class="bg-white rounded-2xl border border-gray-200 p-6 shadow-xs space-y-4">
            <h3 class="text-sm font-bold text-[#242424] border-b border-gray-100 pb-2">المنتجات المطلوبة</h3>

            <div class="divide-y divide-gray-100">
                @foreach($order->items as $item)
                <div class="py-3 flex items-center justify-between text-xs">
                    <div class="flex items-center gap-3">
                        <img src="{{ $item->image_url ?? 'https://images.unsplash.com/photo-1594035910387-fea47794261f?auto=format&fit=crop&w=400&q=80' }}" alt="" class="w-12 h-12 object-cover rounded-lg border border-gray-200">
                        <div>
                            <h4 class="font-bold text-[#242424]">{{ $item->product_name }}</h4>
                            @if($item->variant_name)
                                <span class="text-[10px] text-gray-500">{{ $item->variant_name }}</span>
                            @endif
                            <div class="text-[11px] text-gray-400">SKU: {{ $item->sku }}</div>
                        </div>
                    </div>

                    <div class="text-left font-bold text-[#242424]">
                        {{ $item->quantity }} × {{ number_format($item->unit_price, 2) }} ر.س
                        <div class="text-[#0B2E75] font-black">{{ number_format($item->total_price, 2) }} ر.س</div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Totals -->
            <div class="border-t border-gray-200 pt-4 space-y-2 text-xs text-gray-600">
                <div class="flex items-center justify-between">
                    <span>المجموع الفرعي:</span>
                    <span class="font-bold text-[#242424]">{{ number_format($order->subtotal, 2) }} ر.س</span>
                </div>
                @if($order->discount_amount > 0)
                <div class="flex items-center justify-between text-emerald-600">
                    <span>خصم الكوبون ({{ $order->coupon_code }}):</span>
                    <span class="font-bold">- {{ number_format($order->discount_amount, 2) }} ر.س</span>
                </div>
                @endif
                <div class="flex items-center justify-between">
                    <span>رسوم الشحن:</span>
                    <span class="font-bold text-[#242424]">{{ number_format($order->shipping_cost, 2) }} ر.س</span>
                </div>
                <div class="border-t border-gray-200 pt-2 flex items-center justify-between text-base font-extrabold text-[#242424]">
                    <span>المجموع الإجمالي:</span>
                    <span class="text-xl text-[#0B2E75]">{{ number_format($order->total, 2) }} ر.س</span>
                </div>
            </div>
        </div>

    </div>
</x-admin-layout>

