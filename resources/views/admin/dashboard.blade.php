<x-admin-layout>
    <x-slot:title>لوحة المؤشرات والتقارير</x-slot:title>

    <div class="space-y-8">

        @php
            $isNoticeActive = \App\Models\StoreSetting::get('store_launch_notice_active', '1') === '1';
            $noticeText = \App\Models\StoreSetting::get('store_launch_notice_text', 'المتجر قيد التجهيز لاستقبال طلباتكم بما يليق بكم');
        @endphp

        <!-- Store Launch & Preparation Notice Quick Control Card -->
        <div class="bg-gradient-to-r {{ $isNoticeActive ? 'from-amber-50/80 via-white to-amber-50/40 border-amber-200' : 'from-gray-50 via-white to-gray-50 border-gray-200' }} border-2 rounded-2xl p-4 sm:p-5 flex flex-col md:flex-row items-start md:items-center justify-between gap-4 shadow-2xs text-right">
            <div class="flex items-center gap-3.5 min-w-0">
                <div class="w-11 h-11 rounded-2xl {{ $isNoticeActive ? 'bg-amber-400 text-slate-950 shadow-xs' : 'bg-gray-100 text-gray-400' }} flex items-center justify-center flex-shrink-0">
                    <x-icon name="bell" class="w-5 h-5 stroke-[2]" />
                </div>
                <div class="min-w-0">
                    <div class="flex items-center gap-2 flex-wrap">
                        <span class="text-xs font-black text-slate-900">إشعار تجهيز المتجر للعملاء (عند الدخول)</span>
                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-black {{ $isNoticeActive ? 'bg-emerald-100 text-emerald-800' : 'bg-gray-200 text-gray-700' }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ $isNoticeActive ? 'bg-emerald-500 animate-pulse' : 'bg-gray-400' }}"></span>
                            {{ $isNoticeActive ? 'مفعل وظاهر للعملاء' : 'معطل ومخفي' }}
                        </span>
                    </div>
                    <p class="text-xs text-slate-600 mt-1 font-bold truncate">
                        "{{ $noticeText }}"
                    </p>
                </div>
            </div>

            <div class="flex items-center gap-2 flex-wrap w-full md:w-auto justify-end flex-shrink-0">
                <form action="{{ route('admin.settings.toggle-launch-notice') }}" method="POST">
                    @csrf
                    <button type="submit" class="px-3.5 py-2 rounded-xl text-xs font-bold transition flex items-center gap-1.5 shadow-2xs {{ $isNoticeActive ? 'bg-amber-100 hover:bg-amber-200 text-amber-900' : 'bg-emerald-600 hover:bg-emerald-700 text-white' }}">
                        <span>{{ $isNoticeActive ? 'إيقاف الظهور مؤقتاً' : 'تفعيل ظهور الإشعار' }}</span>
                    </button>
                </form>

                <a href="{{ route('admin.settings.index') }}#launch-notice-section" class="bg-white hover:bg-gray-50 border border-gray-200 text-gray-700 px-3.5 py-2 rounded-xl text-xs font-bold transition flex items-center gap-1 shadow-2xs">
                    <span>تعديل النص &larr;</span>
                </a>

                <a href="{{ route('home', ['preview_notice' => 1]) }}" target="_blank" class="bg-[#0B2E75] hover:bg-[#071E50] text-white px-3.5 py-2 rounded-xl text-xs font-bold transition flex items-center gap-1 shadow-2xs">
                    <span>معاينة بالمتجر</span>
                    <x-icon name="arrow-up-right" class="w-3.5 h-3.5" />
                </a>
            </div>
        </div>
        
        <!-- Metrics Row -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 text-right">
            <!-- Revenue -->
            <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-xs flex items-center justify-between">
                <div>
                    <span class="text-xs font-semibold text-gray-500 block">إجمالي المبيعات المدفوعة</span>
                    <span class="text-2xl font-black text-[#242424] mt-1 block">
                        {{ number_format($totalRevenue, 2) }} <span class="text-xs font-normal text-gray-500">ر.س</span>
                    </span>
                    <span class="text-[11px] text-emerald-600 font-medium mt-1 block">مكتملة ومؤكدة</span>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-blue-50 text-[#0B2E75] flex items-center justify-center">
                    <x-icon name="credit-card" class="w-6 h-6" />
                </div>
            </div>

            <!-- Orders Paid -->
            <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-xs flex items-center justify-between">
                <div>
                    <span class="text-xs font-semibold text-gray-500 block">الطلبات المدفوعة</span>
                    <span class="text-2xl font-black text-[#242424] mt-1 block">{{ $paidOrdersCount }}</span>
                    <span class="text-[11px] text-gray-500 font-medium mt-1 block">من أصل {{ $totalOrdersCount }} طلب كلي</span>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                    <x-icon name="check-circle" class="w-6 h-6" />
                </div>
            </div>

            <!-- Orders Pending -->
            <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-xs flex items-center justify-between">
                <div>
                    <span class="text-xs font-semibold text-gray-500 block">طلبات بانتظار التجهيز</span>
                    <span class="text-2xl font-black text-amber-600 mt-1 block">{{ $pendingOrdersCount }}</span>
                    <span class="text-[11px] text-gray-500 font-medium mt-1 block">تتطلب مراجعة وشحن</span>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center">
                    <x-icon name="package" class="w-6 h-6" />
                </div>
            </div>

            <!-- Low Stock Alert -->
            <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-xs flex items-center justify-between">
                <div>
                    <span class="text-xs font-semibold text-gray-500 block">تنبيهات انخفاض المخزون</span>
                    <span class="text-2xl font-black {{ $lowStockCount > 0 ? 'text-red-600' : 'text-gray-700' }} mt-1 block">
                        {{ $lowStockCount }} منتجات
                    </span>
                    <span class="text-[11px] text-gray-500 font-medium mt-1 block">مخزونها أقل من 5 قطع</span>
                </div>
                <div class="w-12 h-12 rounded-2xl {{ $lowStockCount > 0 ? 'bg-red-50 text-red-600' : 'bg-gray-100 text-gray-400' }} flex items-center justify-center">
                    <x-icon name="shield-check" class="w-6 h-6" />
                </div>
            </div>
        </div>

        <!-- Recent Orders Card -->
        <div class="bg-white rounded-2xl border border-gray-200 shadow-xs overflow-hidden text-right">
            <div class="p-6 border-b border-gray-200 flex items-center justify-between">
                <h3 class="text-sm font-bold text-[#242424]">أحدث الطلبات المستلمة</h3>
                <a href="{{ route('admin.orders.index') }}" class="text-xs font-bold text-[#0B2E75] hover:text-[#071E50] hover:underline">
                    عرض جميع الطلبات ←
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-xs text-right">
                    <thead class="bg-gray-50 text-gray-500 border-b border-gray-200 uppercase font-semibold">
                        <tr>
                            <th class="px-6 py-3.5">رقم الطلب</th>
                            <th class="px-6 py-3.5">العميل</th>
                            <th class="px-6 py-3.5">المدينة</th>
                            <th class="px-6 py-3.5">المجموع</th>
                            <th class="px-6 py-3.5">حالة الدفع</th>
                            <th class="px-6 py-3.5">حالة الشحنة</th>
                            <th class="px-6 py-3.5">التاريخ</th>
                            <th class="px-6 py-3.5">الإجراء</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($recentOrders as $order)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 font-mono font-bold text-[#242424]">{{ $order->order_number }}</td>
                            <td class="px-6 py-4 font-semibold text-[#242424]">
                                {{ $order->customer_name }}
                                <span class="block text-[10px] text-gray-500 font-normal">{{ $order->customer_phone }}</span>
                            </td>
                            <td class="px-6 py-4 text-gray-600">{{ $order->city }}</td>
                            <td class="px-6 py-4 font-bold text-[#242424]">{{ number_format($order->total, 2) }} ر.س</td>
                            <td class="px-6 py-4">
                                <span class="inline-block px-2.5 py-1 rounded-md text-[11px] font-bold {{ $order->payment_status === 'paid' ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">
                                    {{ $order->payment_status_label_ar }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-block px-2.5 py-1 rounded-md text-[11px] font-bold bg-blue-50 text-blue-800">
                                    {{ $order->status_label_ar }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-gray-500">{{ $order->created_at->format('Y/m/d H:i') }}</td>
                            <td class="px-6 py-4">
                                <a href="{{ route('admin.orders.show', $order->id) }}" class="text-[#0B2E75] hover:underline font-bold">
                                    التفاصيل
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="px-6 py-8 text-center text-gray-500">
                                لا توجد طلبات مسجلة بعد.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</x-admin-layout>

