<x-admin-layout>
    <x-slot:title>إضافة منتج جديد</x-slot:title>

    <div class="max-w-4xl mx-auto text-right" 
         x-data="{
             primaryPreview: null,
             galleryCount: 0,
             handlePrimaryImage(e) {
                 const file = e.target.files[0];
                 if (file) {
                     this.primaryPreview = URL.createObjectURL(file);
                 }
             },
             handleGalleryImages(e) {
                 this.galleryCount = e.target.files.length;
             }
         }">
        <div class="bg-white rounded-2xl border border-gray-200 p-6 sm:p-8 shadow-xs">
            <div class="border-b border-gray-200 pb-4 mb-6 flex items-center justify-between">
                <div>
                    <h2 class="text-base font-bold text-[#242424]">إضافة منتج فاخر جديد</h2>
                    <p class="text-xs text-gray-500 mt-0.5">يمكنك رفع الصور مباشرة من كاميرا الجوال أو ألبوم الصور</p>
                </div>
                <span class="text-xs font-semibold bg-[#FAF8F4] text-[#C5A059] border border-[#EDE6DA] px-3 py-1 rounded-full">
                    يدعم رفع الصور من الجوال
                </span>
            </div>

            <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-bold text-[#242424] mb-1.5">اسم المنتج بالعربية <span class="text-red-500">*</span></label>
                        <input type="text" name="name_ar" value="{{ old('name_ar') }}" required placeholder="مثال: أرز بسمتي سيلا هندي ممتاز 5 كجم" class="w-full bg-[#FAF8F4] border border-[#EDE6DA] rounded-lg px-3.5 py-2.5 text-xs text-[#242424] focus:outline-none focus:border-[#C5A059]">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-[#242424] mb-1.5">الاسم بالإنجليزية (اختياري)</label>
                        <input type="text" name="name_en" value="{{ old('name_en') }}" placeholder="Royal Lavender Perfume" class="w-full bg-[#FAF8F4] border border-[#EDE6DA] rounded-lg px-3.5 py-2.5 text-xs text-[#242424] focus:outline-none focus:border-[#C5A059]">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-[#242424] mb-1.5">القسم والتصنيف <span class="text-red-500">*</span></label>
                        <select name="category_id" required class="w-full bg-[#FAF8F4] border border-[#EDE6DA] rounded-lg px-3.5 py-2.5 text-xs text-[#242424] focus:outline-none focus:border-[#C5A059]">
                            @foreach($categories as $parent)
                                @if($parent->children->isNotEmpty())
                                    <optgroup label="── {{ $parent->name_ar }} ──">
                                        <option value="{{ $parent->id }}">القسم العام: {{ $parent->name_ar }}</option>
                                        @foreach($parent->children as $sub)
                                            <option value="{{ $sub->id }}" {{ old('category_id') == $sub->id ? 'selected' : '' }}>
                                                {{ $sub->name_ar }}
                                            </option>
                                        @endforeach
                                    </optgroup>
                                @else
                                    <option value="{{ $parent->id }}" {{ old('category_id') == $parent->id ? 'selected' : '' }}>
                                        {{ $parent->name_ar }}
                                    </option>
                                @endif
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-[#242424] mb-1.5">رمز المنتج (SKU) <span class="text-red-500">*</span></label>
                        <input type="text" name="sku" value="{{ old('sku', 'INC-' . rand(100, 999)) }}" required class="w-full bg-[#FAF8F4] border border-[#EDE6DA] rounded-lg px-3.5 py-2.5 text-xs text-[#242424] focus:outline-none focus:border-[#C5A059]">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-[#242424] mb-1.5">السعر الأساسي (ر.س) <span class="text-red-500">*</span></label>
                        <input type="number" step="0.01" name="regular_price" value="{{ old('regular_price') }}" required placeholder="250.00" class="w-full bg-[#FAF8F4] border border-[#EDE6DA] rounded-lg px-3.5 py-2.5 text-xs text-[#242424] focus:outline-none focus:border-[#C5A059]">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-[#242424] mb-1.5">سعر التخفيض (اختياري)</label>
                        <input type="number" step="0.01" name="sale_price" value="{{ old('sale_price') }}" placeholder="199.00" class="w-full bg-[#FAF8F4] border border-[#EDE6DA] rounded-lg px-3.5 py-2.5 text-xs text-[#242424] focus:outline-none focus:border-[#C5A059]">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-[#242424] mb-1.5">كمية المخزون <span class="text-red-500">*</span></label>
                        <input type="number" name="stock_quantity" value="{{ old('stock_quantity', 15) }}" required class="w-full bg-[#FAF8F4] border border-[#EDE6DA] rounded-lg px-3.5 py-2.5 text-xs text-[#242424] focus:outline-none focus:border-[#C5A059]">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-[#242424] mb-1.5">الوزن / الحجم (مثال: 5 كجم، 1 لتر، كرتون)</label>
                        <input type="text" name="size" value="{{ old('size') }}" placeholder="100 مل" class="w-full bg-[#FAF8F4] border border-[#EDE6DA] rounded-lg px-3.5 py-2.5 text-xs text-[#242424] focus:outline-none focus:border-[#C5A059]">
                    </div>

                    <!-- Photo Upload Section (Primary & Mobile Camera) -->
                    <div class="sm:col-span-2 bg-[#FAF8F4] p-5 rounded-2xl border border-[#EDE6DA] space-y-4">
                        <div>
                            <label class="block text-xs font-bold text-[#242424] mb-1">
                                صورة المنتج الرئيسية (التقطها بالكاميرا أو ارفعها من الجوال) <span class="text-red-500">*</span>
                            </label>
                            
                            <div class="border-2 border-dashed border-[#C5A059]/50 hover:border-[#C5A059] rounded-xl p-4 bg-white text-center cursor-pointer transition">
                                <label for="product_image_file" class="cursor-pointer block">
                                    <template x-if="primaryPreview">
                                        <div class="relative">
                                            <img :src="primaryPreview" class="max-h-56 mx-auto rounded-xl object-contain shadow-sm mb-2">
                                            <span class="text-xs text-[#C5A059] font-bold block">اضغط لتغيير الصورة أو اختيار لقطة أخرى</span>
                                        </div>
                                    </template>

                                    <template x-if="!primaryPreview">
                                        <div class="py-4">
                                            <div class="w-12 h-12 rounded-full bg-[#FAF8F4] border border-[#EDE6DA] flex items-center justify-center mx-auto text-[#C5A059] mb-2">
                                                <x-icon name="camera" class="w-6 h-6" />
                                            </div>
                                            <span class="text-xs font-bold text-[#242424] block">التقط صورة بكاميرا الجوال أو اختر من الألبوم</span>
                                            <span class="text-[10px] text-gray-500 block mt-1">يدعم JPG, PNG, WEBP (حجم أقصى 10 ميجابايت)</span>
                                        </div>
                                    </template>

                                    <input type="file" 
                                           id="product_image_file" 
                                           name="image_file" 
                                           accept="image/*" 
                                           @change="handlePrimaryImage($event)"
                                           class="hidden">
                                </label>
                            </div>
                        </div>

                        <!-- Additional Photos from Mobile -->
                        <div class="pt-2 border-t border-[#EDE6DA]">
                            <label class="block text-xs font-bold text-[#242424] mb-1">
                                صور إضافية للمعرض (اختياري - يمكنك اختيار عدة صور معاً من الجوال)
                            </label>
                            <label for="gallery_files" class="flex items-center justify-between bg-white border border-[#EDE6DA] rounded-xl px-4 py-3 cursor-pointer hover:border-[#C5A059] transition">
                                <span class="text-xs text-gray-600 flex items-center gap-2">
                                    <x-icon name="images" class="w-4 h-4 text-[#C5A059]" />
                                    <span x-text="galleryCount > 0 ? ('تم اختيار ' + galleryCount + ' صور إضافية') : 'اختر صور إضافية لزوايا المنتج من الجوال'"></span>
                                </span>
                                <span class="bg-[#FAF8F4] text-[#C5A059] text-xs font-bold px-3 py-1 rounded-lg border border-[#EDE6DA]">تحديد الصور</span>
                                <input type="file" 
                                       id="gallery_files" 
                                       name="gallery_files[]" 
                                       accept="image/*" 
                                       multiple 
                                       @change="handleGalleryImages($event)"
                                       class="hidden">
                            </label>
                        </div>

                        <!-- Fallback URL input -->
                        <div class="pt-2 border-t border-[#EDE6DA]">
                            <label class="block text-[11px] text-gray-600 mb-1">أو إذا رغبت، ضع رابط صورة مباشر:</label>
                            <input type="url" name="image_url" placeholder="https://images.unsplash.com/..." class="w-full bg-white border border-[#EDE6DA] rounded-lg px-3 py-2 text-xs text-[#242424] focus:outline-none focus:border-[#C5A059]">
                        </div>
                    </div>

                    <!-- Fragrance Notes -->
                    <div>
                        <label class="block text-xs font-bold text-[#242424] mb-1.5">المواصفات الأساسية (Feature 1)</label>
                        <input type="text" name="fragrance_top" value="{{ old('fragrance_top') }}" placeholder="الهيل، البرغموت..." class="w-full bg-[#FAF8F4] border border-[#EDE6DA] rounded-lg px-3.5 py-2.5 text-xs text-[#242424] focus:outline-none focus:border-[#C5A059]">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-[#242424] mb-1.5">الجودة وسلامة الغذاء (Feature 2)</label>
                        <input type="text" name="fragrance_heart" value="{{ old('fragrance_heart') }}" placeholder="الورد، الياسمين..." class="w-full bg-[#FAF8F4] border border-[#EDE6DA] rounded-lg px-3.5 py-2.5 text-xs text-[#242424] focus:outline-none focus:border-[#C5A059]">
                    </div>

                    <div class="sm:col-span-2">
                        <label class="block text-xs font-bold text-[#242424] mb-1.5">المصدر والتعبئة (Feature 3)</label>
                        <input type="text" name="fragrance_base" value="{{ old('fragrance_base') }}" placeholder="المكونات أو المواصفات الرئيسية..." class="w-full bg-[#FAF8F4] border border-[#EDE6DA] rounded-lg px-3.5 py-2.5 text-xs text-[#242424] focus:outline-none focus:border-[#C5A059]">
                    </div>

                    <div class="sm:col-span-2">
                        <label class="block text-xs font-bold text-[#242424] mb-1.5">الوصف التفصيلي بالعربية <span class="text-red-500">*</span></label>
                        <textarea name="description_ar" rows="4" required placeholder="اكتب وصفاً مميزاً للمنتج ومكوناته..." class="w-full bg-[#FAF8F4] border border-[#EDE6DA] rounded-lg p-3 text-xs text-[#242424] focus:outline-none focus:border-[#C5A059]">{{ old('description_ar') }}</textarea>
                    </div>

                    <!-- SEO & Search Engine Optimization -->
                    <div class="sm:col-span-2 bg-amber-50/60 p-4 rounded-xl border border-amber-200/70 space-y-3">
                        <h4 class="text-xs font-bold text-amber-900 flex items-center gap-1.5">
                            <x-icon name="search" class="w-3.5 h-3.5 text-amber-700" />
                            <span>تحسين محركات البحث والأرشفة للمنتج (SEO & Google) — اختياري</span>
                        </h4>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div>
                                <label class="block text-[11px] font-bold text-[#242424] mb-1">عنوان الميتا المخصص (SEO Title)</label>
                                <input type="text" name="seo_title" value="{{ old('seo_title') }}" placeholder="اتركه فارغاً ليتم استخدام اسم المنتج تلقائياً" class="w-full bg-white border border-[#EDE6DA] rounded-lg px-3 py-2 text-xs text-[#242424] focus:outline-none focus:border-[#C5A059]">
                            </div>
                            <div>
                                <label class="block text-[11px] font-bold text-[#242424] mb-1">وصف الميتا في نتائج بحث جوجل (SEO Description)</label>
                                <input type="text" name="seo_description" value="{{ old('seo_description') }}" placeholder="اتركه فارغاً ليتم استخلاص وصف تلقائي من وصف المنتج" class="w-full bg-white border border-[#EDE6DA] rounded-lg px-3 py-2 text-xs text-[#242424] focus:outline-none focus:border-[#C5A059]">
                            </div>
                        </div>
                    </div>

                    <div class="sm:col-span-2 flex items-center gap-6 pt-2">
                        <label class="flex items-center gap-2 text-xs font-semibold text-[#242424] cursor-pointer">
                            <input type="checkbox" name="is_featured" value="1" class="text-[#C5A059] rounded">
                            <span>عرض في قسم الأكثر طلباً بالرئيسية</span>
                        </label>

                        <label class="flex items-center gap-2 text-xs font-semibold text-[#242424] cursor-pointer">
                            <input type="checkbox" name="is_active" value="1" checked class="text-[#C5A059] rounded">
                            <span>تفعيل ظهور المنتج فوراً بالمتجر</span>
                        </label>
                    </div>
                </div>

                <div class="pt-6 border-t border-gray-200 flex items-center gap-3">
                    <button type="submit" class="bg-[#C5A059] hover:bg-[#AF8B44] text-white text-xs font-bold px-7 py-3 rounded-xl transition shadow-md flex items-center gap-2">
                        <x-icon name="check" class="w-4 h-4" />
                        <span>حفظ ونشر المنتج</span>
                    </button>
                    <a href="{{ route('admin.products.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-semibold px-5 py-3 rounded-xl transition">
                        إلغاء
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-admin-layout>
