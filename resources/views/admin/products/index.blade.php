<x-admin-layout>
    <x-slot:title>إدارة المنتجات والمخزون</x-slot:title>

    <div class="space-y-6 text-right" x-data="{ showImportModal: false }">
        <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
            <h2 class="text-base font-bold text-[#242424]">قائمة المنتجات ({{ $products->total() }})</h2>
            <div class="flex items-center gap-2">
                <button type="button" @click="showImportModal = true" class="bg-white border border-[#C5A059] text-[#C5A059] hover:bg-[#C5A059] hover:text-white text-xs font-bold px-3.5 py-2.5 rounded-xl shadow-xs flex items-center gap-1.5 transition">
                    <x-icon name="upload" class="w-4 h-4" />
                    <span>استيراد من ملف (CSV)</span>
                </button>
                <a href="{{ route('admin.products.create') }}" class="bg-[#C5A059] hover:bg-[#AF8B44] text-white text-xs font-bold px-4 py-2.5 rounded-xl shadow-xs flex items-center gap-1.5 transition">
                    <x-icon name="plus" class="w-4 h-4" />
                    <span>إضافة منتج جديد</span>
                </a>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden shadow-xs">
            <div class="overflow-x-auto">
                <table class="w-full text-xs text-right">
                    <thead class="bg-gray-50 text-gray-500 border-b border-gray-200 uppercase font-semibold">
                        <tr>
                            <th class="px-6 py-3.5">المنتج</th>
                            <th class="px-6 py-3.5">التصنيف</th>
                            <th class="px-6 py-3.5">السعر الأساسي</th>
                            <th class="px-6 py-3.5">سعر التخفيض</th>
                            <th class="px-6 py-3.5">المخزون المتوفر</th>
                            <th class="px-6 py-3.5">الحالة</th>
                            <th class="px-6 py-3.5">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($products as $product)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 flex items-center gap-3">
                                <img src="{{ $product->primary_image }}" alt="" class="w-12 h-12 object-cover rounded-lg border border-gray-200 bg-[#FAF8F4]">
                                <div>
                                    <h4 class="font-bold text-[#242424]">{{ $product->name_ar }}</h4>
                                    <span class="text-[10px] text-gray-400 font-mono">{{ $product->sku }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-gray-600 font-medium">{{ $product->category->name_ar }}</td>
                            <td class="px-6 py-4 font-bold text-[#242424]">{{ number_format($product->regular_price, 2) }} ر.س</td>
                            <td class="px-6 py-4">
                                @if($product->sale_price)
                                    <span class="text-emerald-700 font-bold">{{ number_format($product->sale_price, 2) }} ر.س</span>
                                @else
                                    <span class="text-gray-400">—</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2.5 py-1 rounded-md text-[11px] font-bold {{ $product->stock_quantity > 5 ? 'bg-emerald-50 text-emerald-700' : ($product->stock_quantity > 0 ? 'bg-amber-50 text-amber-700' : 'bg-red-50 text-red-700') }}">
                                    {{ $product->stock_quantity }} قطع
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-0.5 rounded text-[10px] font-semibold {{ $product->is_active ? 'bg-emerald-100 text-emerald-800' : 'bg-gray-100 text-gray-600' }}">
                                    {{ $product->is_active ? 'نشط' : 'معطل' }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('admin.products.edit', $product->id) }}" class="p-1.5 text-blue-600 hover:bg-blue-50 rounded-lg transition" title="تعديل">
                                        تعديل
                                    </a>
                                    <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من حذف هذا المنتج؟')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 text-red-600 hover:bg-red-50 rounded-lg transition" title="حذف">
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
                {{ $products->links() }}
            </div>
        </div>

        <!-- Import CSV Modal -->
        <div x-show="showImportModal" 
             x-cloak 
             class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4 bg-black/60 backdrop-blur-xs">
            
            <div @click.away="showImportModal = false" 
                 class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-2xl border border-[#EDE6DA] text-right space-y-5 animate-in fade-in duration-200">
                
                <div class="flex items-center justify-between border-b border-[#EDE6DA] pb-3">
                    <h3 class="text-sm font-bold text-[#242424] flex items-center gap-2">
                        <x-icon name="upload" class="w-4 h-4 text-[#C5A059]" />
                        <span>استيراد وتحديث المنتجات من ملف CSV</span>
                    </h3>
                    <button type="button" @click="showImportModal = false" class="text-gray-400 hover:text-gray-600 text-lg font-bold">
                        &times;
                    </button>
                </div>

                <div class="p-3.5 bg-amber-50/80 border border-amber-200 rounded-xl space-y-2">
                    <p class="text-xs text-amber-900 font-semibold leading-relaxed">
                        قم بتحميل النموذج التجريبي أولاً، ثم املأ بيانات منتجاتك وأعد رفع الملف:
                    </p>
                    <a href="{{ route('admin.products.template') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-[#C5A059] bg-white border border-[#C5A059] hover:bg-[#C5A059] hover:text-white px-3 py-1.5 rounded-lg transition">
                        <x-icon name="download" class="w-3.5 h-3.5" />
                        <span>تحميل نموذج ملف المنتجات (CSV Template)</span>
                    </a>
                </div>

                <form action="{{ route('admin.products.import') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold text-[#242424] mb-1.5">اختر ملف المنتجات (.csv أو .txt)</label>
                        <input type="file" name="file" accept=".csv,text/csv,text/plain" required class="w-full bg-[#FAF8F4] border border-[#EDE6DA] rounded-xl p-2.5 text-xs text-[#242424] focus:outline-none focus:border-[#C5A059]">
                    </div>

                    <div class="text-[11px] text-gray-500 space-y-1 bg-gray-50 p-3 rounded-lg border border-gray-200">
                        <p class="font-bold text-gray-700">ملاحظات هامة:</p>
                        <ul class="list-disc list-inside space-y-0.5">
                            <li>يدعم الملف اللغة العربية بالكامل (ترميز UTF-8).</li>
                            <li>إذا كان كود المنتج (SKU) موجوداً بالفعل، سيتم تحديث بياناته وسعره ومخزونه تلقائياً.</li>
                            <li>إذا لم يكن القسم موجوداً، سيقوم النظام بإنشاء القسم تلقائياً وتعيين المنتج داخله.</li>
                        </ul>
                    </div>

                    <div class="flex items-center justify-end gap-2 pt-2">
                        <button type="button" @click="showImportModal = false" class="px-4 py-2 text-xs font-semibold text-gray-600 hover:bg-gray-100 rounded-xl transition">
                            إلغاء
                        </button>
                        <button type="submit" class="bg-[#C5A059] hover:bg-[#AF8B44] text-white text-xs font-bold px-6 py-2.5 rounded-xl transition shadow-md flex items-center gap-1.5">
                            <x-icon name="check" class="w-4 h-4" />
                            <span>بدء الاستيراد الآن</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-admin-layout>

