<x-admin-layout>
    <x-slot:title>إدارة مناطق ورسوم الشحن والتوصيل</x-slot:title>

    <div class="space-y-8" x-data="{ addModalOpen: false, editModalOpen: false, currentZone: {} }">
        
        <!-- Header -->
        <div class="bg-white p-6 sm:p-8 rounded-2xl border border-[#EDE6DA] shadow-xs flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div>
                <h2 class="text-lg sm:text-xl font-bold text-[#242424] flex items-center gap-2">
                    <x-icon name="truck" class="w-5 h-5 text-[#C5A059]" />
                    <span>مناطق التوصيل ورسوم الشحن بالمملكة</span>
                </h2>
                <p class="text-xs text-[#777777] mt-1 leading-relaxed">
                    تحكم في أسعار التوصيل لكل مدينة أو منطقة، وحدود الشحن المجاني، والمدة المتوقعة لوصول الطلب للعميل.
                </p>
            </div>

            <button @click="addModalOpen = true" 
                    type="button" 
                    class="bg-[#C5A059] hover:bg-[#AF8B44] text-white px-5 py-2.5 rounded-xl font-bold text-xs flex items-center gap-2 shadow-xs transition">
                <x-icon name="plus" class="w-4 h-4" />
                <span>إضافة منطقة شحن جديدة</span>
            </button>
        </div>

        <!-- Shipping Zones Table -->
        <div class="bg-white rounded-2xl border border-[#EDE6DA] shadow-xs overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-right text-xs">
                    <thead class="bg-[#FAF8F4] text-[#777777] border-b border-[#EDE6DA] font-semibold">
                        <tr>
                            <th class="p-4">المدينة / المنطقة</th>
                            <th class="p-4">سعر الشحن</th>
                            <th class="p-4">حد الشحن المجاني</th>
                            <th class="p-4">مدة التوصيل المتوقعة</th>
                            <th class="p-4">الحالة</th>
                            <th class="p-4 text-center">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#EDE6DA]/60">
                        @forelse($zones as $zone)
                            <tr class="hover:bg-[#FAF8F4]/50 transition">
                                <td class="p-4 font-bold text-[#242424]">
                                    {{ $zone->city_name_ar }}
                                    @if($zone->city_name_en)
                                        <span class="block text-[10px] text-[#777777] font-normal font-sans">{{ $zone->city_name_en }}</span>
                                    @endif
                                </td>
                                <td class="p-4 font-mono font-bold text-[#C5A059]">
                                    {{ number_format($zone->shipping_cost, 2) }} ر.س
                                </td>
                                <td class="p-4">
                                    @if($zone->free_shipping_threshold)
                                        <span class="bg-emerald-50 text-emerald-700 border border-emerald-200 px-2.5 py-1 rounded-full font-bold text-[10px]">
                                            مجاني فوق {{ number_format($zone->free_shipping_threshold, 0) }} ر.س
                                        </span>
                                    @else
                                        <span class="text-[#777777]">—</span>
                                    @endif
                                </td>
                                <td class="p-4 text-[#777777]">
                                    {{ $zone->estimated_days }}
                                </td>
                                <td class="p-4">
                                    @if($zone->is_active)
                                        <span class="text-emerald-700 font-bold flex items-center gap-1">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                            مفعلة
                                        </span>
                                    @else
                                        <span class="text-gray-400 font-medium">معطلة</span>
                                    @endif
                                </td>
                                <td class="p-4">
                                    <div class="flex items-center justify-center gap-2">
                                        <button @click="currentZone = {{ json_encode($zone) }}; editModalOpen = true" 
                                                class="p-2 text-[#777777] hover:text-[#C5A059] hover:bg-[#FAF8F4] rounded-lg transition"
                                                title="تعديل">
                                            <x-icon name="eye" class="w-4 h-4" />
                                        </button>

                                        <form action="{{ route('admin.shipping.destroy', $zone->id) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من حذف هذه المنطقة؟')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2 text-red-500 hover:text-red-700 hover:bg-red-50 rounded-lg transition" title="حذف">
                                                <x-icon name="trash" class="w-4 h-4" />
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="p-8 text-center text-xs text-[#777777]">
                                    لا توجد مناطق شحن مسجلة. أضف مناطق الشحن لتحديد الرسوم بدقة.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Add Modal -->
        <div x-show="addModalOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="fixed inset-0 bg-black/50" @click="addModalOpen = false"></div>
            <div class="relative bg-white rounded-2xl w-full max-w-md p-6 shadow-2xl z-10 text-right space-y-4">
                <h3 class="text-base font-bold text-[#242424]">إضافة منطقة شحن جديدة</h3>
                <form action="{{ route('admin.shipping.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold text-[#242424] mb-1">اسم المدينة / المنطقة (عربي)</label>
                        <input type="text" name="city_name_ar" required placeholder="مثل: الرياض، جدة ومكة المكرمة" class="w-full bg-[#FAF8F4] border border-[#EDE6DA] rounded-xl px-3 py-2 text-xs">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-[#242424] mb-1">الاسم بالإنجليزي (اختياري)</label>
                        <input type="text" name="city_name_en" placeholder="e.g. Riyadh" class="w-full bg-[#FAF8F4] border border-[#EDE6DA] rounded-xl px-3 py-2 text-xs">
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-[#242424] mb-1">سعر الشحن (ر.س)</label>
                            <input type="number" step="0.01" name="shipping_cost" required value="25.00" class="w-full bg-[#FAF8F4] border border-[#EDE6DA] rounded-xl px-3 py-2 text-xs font-bold">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-[#242424] mb-1">حد الشحن المجاني</label>
                            <input type="number" step="0.01" name="free_shipping_threshold" value="299.00" class="w-full bg-[#FAF8F4] border border-[#EDE6DA] rounded-xl px-3 py-2 text-xs">
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-[#242424] mb-1">مدة التوصيل المتوقعة</label>
                        <input type="text" name="estimated_days" value="2 - 4 أيام عمل" class="w-full bg-[#FAF8F4] border border-[#EDE6DA] rounded-xl px-3 py-2 text-xs">
                    </div>
                    <div class="pt-2 flex justify-end gap-2">
                        <button type="button" @click="addModalOpen = false" class="px-4 py-2 text-xs text-[#777777]">إلغاء</button>
                        <button type="submit" class="bg-[#C5A059] text-white px-5 py-2 rounded-xl text-xs font-bold">إضافة</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Edit Modal -->
        <div x-show="editModalOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="fixed inset-0 bg-black/50" @click="editModalOpen = false"></div>
            <div class="relative bg-white rounded-2xl w-full max-w-md p-6 shadow-2xl z-10 text-right space-y-4">
                <h3 class="text-base font-bold text-[#242424]">تعديل منطقة الشحن</h3>
                <form :action="'/admin/shipping/' + currentZone.id" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')
                    <div>
                        <label class="block text-xs font-bold text-[#242424] mb-1">اسم المدينة / المنطقة</label>
                        <input type="text" name="city_name_ar" x-model="currentZone.city_name_ar" required class="w-full bg-[#FAF8F4] border border-[#EDE6DA] rounded-xl px-3 py-2 text-xs">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-[#242424] mb-1">الاسم بالإنجليزي</label>
                        <input type="text" name="city_name_en" x-model="currentZone.city_name_en" class="w-full bg-[#FAF8F4] border border-[#EDE6DA] rounded-xl px-3 py-2 text-xs">
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-[#242424] mb-1">سعر الشحن (ر.س)</label>
                            <input type="number" step="0.01" name="shipping_cost" x-model="currentZone.shipping_cost" required class="w-full bg-[#FAF8F4] border border-[#EDE6DA] rounded-xl px-3 py-2 text-xs font-bold">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-[#242424] mb-1">حد الشحن المجاني</label>
                            <input type="number" step="0.01" name="free_shipping_threshold" x-model="currentZone.free_shipping_threshold" class="w-full bg-[#FAF8F4] border border-[#EDE6DA] rounded-xl px-3 py-2 text-xs">
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-[#242424] mb-1">مدة التوصيل المتوقعة</label>
                        <input type="text" name="estimated_days" x-model="currentZone.estimated_days" class="w-full bg-[#FAF8F4] border border-[#EDE6DA] rounded-xl px-3 py-2 text-xs">
                    </div>
                    <div class="flex items-center gap-2">
                        <input type="checkbox" name="is_active" value="1" :checked="currentZone.is_active" id="edit_is_active" class="rounded text-[#C5A059]">
                        <label for="edit_is_active" class="text-xs text-[#242424] font-medium">المنطقة مفعلة وتستقبل الطلبات</label>
                    </div>
                    <div class="pt-2 flex justify-end gap-2">
                        <button type="button" @click="editModalOpen = false" class="px-4 py-2 text-xs text-[#777777]">إلغاء</button>
                        <button type="submit" class="bg-[#C5A059] text-white px-5 py-2 rounded-xl text-xs font-bold">حفظ التعديلات</button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</x-admin-layout>

