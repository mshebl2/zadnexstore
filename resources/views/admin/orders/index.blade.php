<x-admin-layout>
    <x-slot:title>إدارة الطلبات</x-slot:title>

    <div class="space-y-6 text-right">
        <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <h2 class="text-base font-bold text-[#242424]">سجل طلبات العملاء ({{ $orders->total() }})</h2>
                <a href="{{ route('admin.orders.export', request()->query()) }}" class="bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold px-3 py-1.5 rounded-lg transition shadow-xs flex items-center gap-1.5" title="تصدير كملف إكسل / CSV متوافق مع الحروف العربية">
                    <x-icon name="download" class="w-3.5 h-3.5" />
                    <span>تصدير الطلبات (Excel / CSV)</span>
                </a>
            </div>

            <!-- Status Filter -->
            <form method="GET" action="{{ route('admin.orders.index') }}" class="flex items-center gap-2">
                <select name="status" onchange="this.form.submit()" class="bg-white border border-gray-300 rounded-lg px-3 py-1.5 text-xs text-[#242424] focus:outline-none focus:border-[#C5A059]">
                    <option value="">جميع حالات الشحن</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>قيد المراجعة</option>
                    <option value="processing" {{ request('status') === 'processing' ? 'selected' : '' }}>قيد التجهيز</option>
                    <option value="shipped" {{ request('status') === 'shipped' ? 'selected' : '' }}>تم الشحن</option>
                    <option value="delivered" {{ request('status') === 'delivered' ? 'selected' : '' }}>تم التوصيل</option>
                    <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>ملغي</option>
                </select>

                <select name="payment_status" onchange="this.form.submit()" class="bg-white border border-gray-300 rounded-lg px-3 py-1.5 text-xs text-[#242424] focus:outline-none focus:border-[#C5A059]">
                    <option value="">جميع حالات الدفع</option>
                    <option value="paid" {{ request('payment_status') === 'paid' ? 'selected' : '' }}>مدفوع</option>
                    <option value="unpaid" {{ request('payment_status') === 'unpaid' ? 'selected' : '' }}>غير مدفوع</option>
                    <option value="failed" {{ request('payment_status') === 'failed' ? 'selected' : '' }}>فشل الدفع</option>
                </select>
            </form>
        </div>

        <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden shadow-xs">
            <div class="overflow-x-auto">
                <table class="w-full text-xs text-right">
                    <thead class="bg-gray-50 text-gray-500 border-b border-gray-200 uppercase font-semibold">
                        <tr>
                            <th class="px-6 py-3.5">رقم الطلب</th>
                            <th class="px-6 py-3.5">العميل</th>
                            <th class="px-6 py-3.5">المدينة</th>
                            <th class="px-6 py-3.5">المجموع الكلي</th>
                            <th class="px-6 py-3.5">حالة الدفع</th>
                            <th class="px-6 py-3.5">حالة التوصيل</th>
                            <th class="px-6 py-3.5">التاريخ</th>
                            <th class="px-6 py-3.5">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($orders as $order)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 font-mono font-bold text-[#242424]">{{ $order->order_number }}</td>
                            <td class="px-6 py-4">
                                <span class="font-bold text-[#242424] block">{{ $order->customer_name }}</span>
                                <span class="text-[10px] text-gray-500 font-mono" dir="ltr">{{ $order->customer_phone }}</span>
                            </td>
                            <td class="px-6 py-4 text-gray-600">{{ $order->city }}</td>
                            <td class="px-6 py-4 font-bold text-[#242424]">{{ number_format($order->total, 2) }} ر.س</td>
                            <td class="px-6 py-4">
                                <span class="px-2.5 py-1 rounded-md text-[11px] font-bold {{ $order->payment_status === 'paid' ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">
                                    {{ $order->payment_status_label_ar }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2.5 py-1 rounded-md text-[11px] font-bold bg-blue-50 text-blue-800">
                                    {{ $order->status_label_ar }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-gray-500">{{ $order->created_at->format('Y-m-d H:i') }}</td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-1.5">
                                    <a href="{{ route('admin.orders.show', $order->id) }}" class="bg-[#FAF8F4] border border-[#EDE6DA] text-[#242424] hover:bg-[#C5A059] hover:text-white px-2.5 py-1.5 rounded-lg text-[11px] font-semibold transition inline-block">
                                        معاينة
                                    </a>
                                    <a href="{{ route('admin.orders.invoice', $order->id) }}" target="_blank" class="bg-gray-50 border border-gray-200 text-gray-700 hover:bg-gray-200 px-2.5 py-1.5 rounded-lg text-[11px] font-semibold transition inline-block">
                                        فاتورة
                                    </a>
                                    <form action="{{ route('admin.orders.destroy', $order->id) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من حذف الطلب {{ $order->order_number }} نهائياً وكافة سجلاته؟ لا يمكن التراجع عن هذا الإجراء.');" class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="bg-red-50 border border-red-200 text-red-600 hover:bg-red-600 hover:text-white px-2.5 py-1.5 rounded-lg text-[11px] font-semibold transition inline-block">
                                            حذف
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="p-4 border-t border-gray-200">
                {{ $orders->links() }}
            </div>
        </div>
    </div>
</x-admin-layout>

