<x-admin-layout>
    <x-slot:title>إدارة كوبونات الخصم</x-slot:title>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 text-right">
        <!-- Add Coupon Form -->
        <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-xs h-fit space-y-4">
            <h3 class="text-sm font-bold text-[#242424] border-b border-gray-100 pb-3">إنشاء كوبون جديد</h3>

            <form action="{{ route('admin.coupons.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">كود الخصم (الكوبون) <span class="text-red-500">*</span></label>
                    <input type="text" name="code" required placeholder="مثال: EID2026" class="w-full bg-[#FAF8F4] border border-[#EDE6DA] rounded-lg p-2.5 text-xs text-[#242424] uppercase font-mono focus:outline-none focus:border-[#C5A059]">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">نوع الخصم <span class="text-red-500">*</span></label>
                    <select name="type" class="w-full bg-[#FAF8F4] border border-[#EDE6DA] rounded-lg p-2.5 text-xs text-[#242424] focus:outline-none focus:border-[#C5A059]">
                        <option value="percent">نسبة مئوية (%)</option>
                        <option value="fixed">مبلغ ثابت (ر.س)</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">قيمة الخصم <span class="text-red-500">*</span></label>
                    <input type="number" step="0.01" name="value" required placeholder="مثال: 15" class="w-full bg-[#FAF8F4] border border-[#EDE6DA] rounded-lg p-2.5 text-xs text-[#242424] focus:outline-none focus:border-[#C5A059]">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">الحد الأدنى لقيمة الطلب (ر.س)</label>
                    <input type="number" step="0.01" name="min_order_amount" value="0" class="w-full bg-[#FAF8F4] border border-[#EDE6DA] rounded-lg p-2.5 text-xs text-[#242424] focus:outline-none focus:border-[#C5A059]">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">الحد الأقصى لمرات الاستخدام</label>
                    <input type="number" name="max_uses" placeholder="اتركه فارغاً للاستخدام غير المحدود" class="w-full bg-[#FAF8F4] border border-[#EDE6DA] rounded-lg p-2.5 text-xs text-[#242424] focus:outline-none focus:border-[#C5A059]">
                </div>

                <button type="submit" class="w-full bg-[#C5A059] hover:bg-[#AF8B44] text-white text-xs font-bold py-2.5 px-4 rounded-xl transition">
                    حفظ وتفعيل الكوبون
                </button>
            </form>
        </div>

        <!-- Coupons List -->
        <div class="lg:col-span-2 bg-white rounded-2xl border border-gray-200 overflow-hidden shadow-xs">
            <div class="p-5 border-b border-gray-200">
                <h3 class="text-sm font-bold text-[#242424]">الكوبونات المفعلة ({{ $coupons->count() }})</h3>
            </div>

            <div class="divide-y divide-gray-100">
                @foreach($coupons as $coupon)
                <div class="p-4 flex items-center justify-between gap-4 hover:bg-gray-50 transition">
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="font-mono font-bold text-xs bg-[#FAF8F4] border border-[#EDE6DA] text-[#242424] px-2.5 py-1 rounded-md">
                                {{ $coupon->code }}
                            </span>
                            <span class="text-xs font-bold text-[#C5A059]">
                                {{ $coupon->type === 'percent' ? $coupon->value . '%' : $coupon->value . ' ر.س' }}
                            </span>
                        </div>
                        <div class="text-[11px] text-gray-500 mt-1 flex items-center gap-2">
                            <span>الحد الأدنى: {{ $coupon->min_order_amount }} ر.س</span>
                            <span>•</span>
                            <span>الاستخدامات: {{ $coupon->used_count }} {{ $coupon->max_uses ? '/ ' . $coupon->max_uses : '' }}</span>
                        </div>
                    </div>

                    <form action="{{ route('admin.coupons.destroy', $coupon->id) }}" method="POST" onsubmit="return confirm('حذف هذا الكوبون؟')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-500 hover:text-red-700 text-xs px-2.5 py-1 rounded hover:bg-red-50 transition">
                            حذف
                        </button>
                    </form>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</x-admin-layout>

