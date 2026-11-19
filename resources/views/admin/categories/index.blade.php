<x-admin-layout>
    <x-slot:title>إدارة تصنيفات المتجر</x-slot:title>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 text-right" 
         x-data="{ 
             editModalOpen: false, 
             currentCat: {},
             createPreview: null,
             editPreview: null,
             handleCreateImage(e) {
                 const file = e.target.files[0];
                 if (file) {
                     this.createPreview = URL.createObjectURL(file);
                 }
             },
             handleEditImage(e) {
                 const file = e.target.files[0];
                 if (file) {
                     this.editPreview = URL.createObjectURL(file);
                 }
             }
         }">
        
        <!-- Add Category Form (1 Col) -->
        <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-xs h-fit space-y-4">
            <h3 class="text-sm font-bold text-[#242424] border-b border-gray-100 pb-3 flex items-center justify-between">
                <span>إضافة قسم جديد</span>
                <span class="text-[10px] text-[#C5A059] bg-[#FAF8F4] px-2 py-0.5 rounded-full border border-[#EDE6DA]">يدعم رفع الصور من الجوال</span>
            </h3>

            <form action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">اسم القسم بالعربية <span class="text-red-500">*</span></label>
                    <input type="text" name="name_ar" required placeholder="مثال: مواد غذائية وتموين" class="w-full bg-[#FAF8F4] border border-[#EDE6DA] rounded-lg p-2.5 text-xs text-[#242424] focus:outline-none focus:border-[#C5A059]">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">الاسم بالإنجليزية (اختياري)</label>
                    <input type="text" name="name_en" placeholder="Musk & Powder Perfumes" class="w-full bg-[#FAF8F4] border border-[#EDE6DA] rounded-lg p-2.5 text-xs text-[#242424] focus:outline-none focus:border-[#C5A059]">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">تبع القسم (القسم الرئيسي)</label>
                    <select name="parent_id" class="w-full bg-[#FAF8F4] border border-[#EDE6DA] rounded-lg p-2.5 text-xs text-[#242424] focus:outline-none focus:border-[#C5A059]">
                        <option value="">-- قسم رئيسي مستقل (بدون قسم أب) --</option>
                        @foreach($parentCategories as $pCat)
                            <option value="{{ $pCat->id }}">قسم فرعي يتبع لـ: {{ $pCat->name_ar }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Mobile Photo Upload Box -->
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">صورة القسم (رفع من الجوال أو الكاميرا)</label>
                    
                    <div class="border-2 border-dashed border-[#EDE6DA] hover:border-[#C5A059] rounded-xl p-3 bg-[#FAF8F4] text-center transition">
                        <label for="cat_image_file" class="cursor-pointer block">
                            <!-- Preview if selected -->
                            <template x-if="createPreview">
                                <div class="relative mb-2">
                                    <img :src="createPreview" class="w-full h-32 object-cover rounded-lg mx-auto shadow-xs">
                                    <span class="text-[10px] text-[#C5A059] font-bold mt-1 block">اضغط لتغيير الصورة</span>
                                </div>
                            </template>

                            <template x-if="!createPreview">
                                <div class="py-3">
                                    <x-icon name="image" class="w-8 h-8 text-[#C5A059] mx-auto mb-1" />
                                    <span class="text-xs font-bold text-[#242424] block">التقط صورة بالكاميرا أو اختر من الجوال</span>
                                    <span class="text-[10px] text-gray-500">JPG, PNG, WEBP (حتى 10 ميجابايت)</span>
                                </div>
                            </template>

                            <input type="file" 
                                   id="cat_image_file" 
                                   name="image_file" 
                                   accept="image/*" 
                                   @change="handleCreateImage($event)"
                                   class="hidden">
                        </label>
                    </div>

                    <!-- Fallback URL input -->
                    <div class="mt-2">
                        <span class="text-[10px] text-gray-500 block mb-0.5">أو ضع رابط صورة خارجي:</span>
                        <input type="url" name="image_url" placeholder="https://..." class="w-full bg-[#FAF8F4] border border-[#EDE6DA] rounded-lg p-2 text-xs text-[#242424] focus:outline-none focus:border-[#C5A059]">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">وصف مختصر للقسم</label>
                    <textarea name="description_ar" rows="2" placeholder="وصف يعبر عن منتجات هذا القسم الغذائي أو المنزلي..." class="w-full bg-[#FAF8F4] border border-[#EDE6DA] rounded-lg p-2.5 text-xs text-[#242424] focus:outline-none focus:border-[#C5A059]"></textarea>
                </div>

                <button type="submit" class="w-full bg-[#C5A059] hover:bg-[#AF8B44] text-white text-xs font-bold py-3 px-4 rounded-xl transition shadow-md flex items-center justify-center gap-2">
                    <x-icon name="plus" class="w-4 h-4" />
                    <span>إضافة القسم الآن</span>
                </button>
            </form>
        </div>

        <!-- Categories List (2 Cols) -->
        <div class="lg:col-span-2 bg-white rounded-2xl border border-gray-200 overflow-hidden shadow-xs">
            <div class="p-5 border-b border-gray-200 flex items-center justify-between">
                <h3 class="text-sm font-bold text-[#242424]">الأقسام والتصنيفات الحالية ({{ $categories->count() }})</h3>
                <span class="text-xs text-gray-500">مرتبة حسب الأقسام الرئيسية والفرعية</span>
            </div>

            <div class="divide-y divide-gray-100 max-h-[700px] overflow-y-auto">
                @foreach($categories as $category)
                <div class="p-4 flex items-center justify-between gap-4 hover:bg-gray-50 transition">
                    <div class="flex items-center gap-3">
                        <img src="{{ $category->image }}" alt="" class="w-14 h-14 object-cover rounded-xl border border-gray-200 bg-[#FAF8F4] flex-shrink-0">
                        <div>
                            <div class="flex items-center gap-2">
                                <h4 class="font-bold text-xs text-[#242424]">{{ $category->name_ar }}</h4>
                                @if($category->parent)
                                    <span class="text-[10px] bg-blue-50 text-blue-600 px-2 py-0.5 rounded-full border border-blue-100 font-medium">
                                        فرعي من: {{ $category->parent->name_ar }}
                                    </span>
                                @else
                                    <span class="text-[10px] bg-amber-50 text-[#C5A059] px-2 py-0.5 rounded-full border border-amber-200 font-bold">
                                        قسم رئيسي
                                    </span>
                                @endif
                            </div>
                            <span class="text-[10px] text-gray-400 font-mono block mt-0.5">{{ $category->slug }}</span>
                            <span class="text-[10px] text-[#C5A059] font-medium block">{{ $category->products->count() }} منتجات مرتبطة</span>
                        </div>
                    </div>

                    <div class="flex items-center gap-2 flex-shrink-0">
                        <button type="button" 
                                @click="currentCat = {{ json_encode($category) }}; editPreview = null; editModalOpen = true" 
                                class="text-[#C5A059] hover:bg-[#FAF8F4] text-xs px-3 py-1.5 rounded-lg transition border border-[#C5A059]/40 font-bold">
                            تعديل
                        </button>

                        <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من حذف هذا القسم؟')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-500 hover:text-red-700 text-xs px-2.5 py-1.5 rounded-lg hover:bg-red-50 transition">
                                حذف
                            </button>
                        </form>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Edit Category Modal -->
        <div x-show="editModalOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="fixed inset-0 bg-black/60 backdrop-blur-xs" @click="editModalOpen = false"></div>
            <div class="relative bg-white rounded-2xl w-full max-w-md p-6 shadow-2xl z-10 text-right space-y-4 max-h-[90vh] overflow-y-auto">
                <h3 class="text-base font-bold text-[#242424] border-b border-gray-100 pb-3">تعديل بيانات القسم والصورة</h3>
                
                <form :action="'/admin/categories/' + currentCat.id" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    @method('PUT')
                    <div>
                        <label class="block text-xs font-bold text-[#242424] mb-1">اسم القسم بالعربية <span class="text-red-500">*</span></label>
                        <input type="text" name="name_ar" x-model="currentCat.name_ar" required class="w-full bg-[#FAF8F4] border border-[#EDE6DA] rounded-xl px-3 py-2 text-xs">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-[#242424] mb-1">الاسم بالإنجليزي</label>
                        <input type="text" name="name_en" x-model="currentCat.name_en" class="w-full bg-[#FAF8F4] border border-[#EDE6DA] rounded-xl px-3 py-2 text-xs">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-[#242424] mb-1">القسم الرئيسي التابع له</label>
                        <select name="parent_id" x-model="currentCat.parent_id" class="w-full bg-[#FAF8F4] border border-[#EDE6DA] rounded-xl px-3 py-2 text-xs">
                            <option :value="null">-- قسم رئيسي مستقل --</option>
                            @foreach($parentCategories as $pCat)
                                <option value="{{ $pCat->id }}">يتبع لـ: {{ $pCat->name_ar }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Upload New Photo from mobile inside Edit Modal -->
                    <div>
                        <label class="block text-xs font-bold text-[#242424] mb-1">تحديث الصورة (رفع من الجوال أو الكاميرا)</label>
                        <div class="border-2 border-dashed border-[#EDE6DA] rounded-xl p-3 bg-[#FAF8F4] text-center">
                            <label for="cat_edit_image_file" class="cursor-pointer block">
                                <template x-if="editPreview">
                                    <img :src="editPreview" class="w-full h-32 object-cover rounded-lg mx-auto shadow-xs mb-1">
                                </template>
                                <template x-if="!editPreview && currentCat.image">
                                    <img :src="currentCat.image" class="w-full h-28 object-cover rounded-lg mx-auto shadow-xs mb-1">
                                </template>
                                <span class="text-xs font-bold text-[#C5A059] block">اختر صورة جديدة من الجوال</span>
                                <input type="file" 
                                       id="cat_edit_image_file" 
                                       name="image_file" 
                                       accept="image/*" 
                                       @change="handleEditImage($event)"
                                       class="hidden">
                            </label>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-[#242424] mb-1">رابط صورة خارجي بديل</label>
                        <input type="url" name="image_url" x-model="currentCat.image" class="w-full bg-[#FAF8F4] border border-[#EDE6DA] rounded-xl px-3 py-2 text-xs">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-[#242424] mb-1">الوصف</label>
                        <textarea name="description_ar" x-model="currentCat.description_ar" rows="3" class="w-full bg-[#FAF8F4] border border-[#EDE6DA] rounded-xl px-3 py-2 text-xs"></textarea>
                    </div>
                    <div class="pt-2 flex justify-end gap-2 border-t border-gray-100">
                        <button type="button" @click="editModalOpen = false" class="px-4 py-2 text-xs text-[#777777]">إلغاء</button>
                        <button type="submit" class="bg-[#C5A059] text-white px-5 py-2.5 rounded-xl text-xs font-bold hover:bg-[#AF8B44] transition shadow-md">حفظ التعديلات</button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</x-admin-layout>
