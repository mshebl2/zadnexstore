<x-admin-layout>
    <x-slot:title>تعديل منتج: {{ $product->name_ar }}</x-slot:title>

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
                    <h2 class="text-base font-bold text-[#242424]">تعديل بيانات المنتج وصوره</h2>
                    <p class="text-xs text-gray-500 mt-0.5">يمكنك تغيير الصورة برفع صورة جديدة من كاميرا الجوال أو الألبوم</p>
                </div>
                <a href="{{ route('admin.products.index') }}" class="text-xs text-[#C5A059] hover:underline font-bold">
                    العودة لقائمة المنتجات
                </a>
            </div>

            <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-bold text-[#242424] mb-1.5">اسم المنتج بالعربية <span class="text-red-500">*</span></label>
                        <input type="text" name="name_ar" value="{{ old('name_ar', $product->name_ar) }}" required class="w-full bg-[#FAF8F4] border border-[#EDE6DA] rounded-lg px-3.5 py-2.5 text-xs text-[#242424] focus:outline-none focus:border-[#C5A059]">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-[#242424] mb-1.5">الاسم بالإنجليزية</label>
                        <input type="text" name="name_en" value="{{ old('name_en', $product->name_en) }}" class="w-full bg-[#FAF8F4] border border-[#EDE6DA] rounded-lg px-3.5 py-2.5 text-xs text-[#242424] focus:outline-none focus:border-[#C5A059]">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-[#242424] mb-1.5">التصنيف <span class="text-red-500">*</span></label>
                        <select name="category_id" required class="w-full bg-[#FAF8F4] border border-[#EDE6DA] rounded-lg px-3.5 py-2.5 text-xs text-[#242424] focus:outline-none focus:border-[#C5A059]">
                            @foreach($categories as $parent)
                                @if($parent->children->isNotEmpty())
                                    <optgroup label="── {{ $parent->name_ar }} ──">
                                        <option value="{{ $parent->id }}" {{ $product->category_id == $parent->id ? 'selected' : '' }}>
                                            القسم العام: {{ $parent->name_ar }}
                                        </option>
                                        @foreach($parent->children as $sub)
                                            <option value="{{ $sub->id }}" {{ $product->category_id == $sub->id ? 'selected' : '' }}>
                                                {{ $sub->name_ar }}
                                            </option>
                                        @endforeach
                                    </optgroup>
                                @else
                                    <option value="{{ $parent->id }}" {{ $product->category_id == $parent->id ? 'selected' : '' }}>
                                        {{ $parent->name_ar }}
                                    </option>
                                @endif
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-[#242424] mb-1.5">رمز المنتج (SKU) <span class="text-red-500">*</span></label>
                        <input type="text" name="sku" value="{{ old('sku', $product->sku) }}" required class="w-full bg-[#FAF8F4] border border-[#EDE6DA] rounded-lg px-3.5 py-2.5 text-xs text-[#242424] focus:outline-none focus:border-[#C5A059]">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-[#242424] mb-1.5">السعر الأساسي (ر.س) <span class="text-red-500">*</span></label>
                        <input type="number" step="0.01" name="regular_price" value="{{ old('regular_price', $product->regular_price) }}" required class="w-full bg-[#FAF8F4] border border-[#EDE6DA] rounded-lg px-3.5 py-2.5 text-xs text-[#242424] focus:outline-none focus:border-[#C5A059]">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-[#242424] mb-1.5">سعر التخفيض (اختياري)</label>
                        <input type="number" step="0.01" name="sale_price" value="{{ old('sale_price', $product->sale_price) }}" class="w-full bg-[#FAF8F4] border border-[#EDE6DA] rounded-lg px-3.5 py-2.5 text-xs text-[#242424] focus:outline-none focus:border-[#C5A059]">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-[#242424] mb-1.5">كمية المخزون <span class="text-red-500">*</span></label>
                        <input type="number" name="stock_quantity" value="{{ old('stock_quantity', $product->stock_quantity) }}" required class="w-full bg-[#FAF8F4] border border-[#EDE6DA] rounded-lg px-3.5 py-2.5 text-xs text-[#242424] focus:outline-none focus:border-[#C5A059]">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-[#242424] mb-1.5">السعة / الحجم</label>
                        <input type="text" name="size" value="{{ old('size', $product->size) }}" class="w-full bg-[#FAF8F4] border border-[#EDE6DA] rounded-lg px-3.5 py-2.5 text-xs text-[#242424] focus:outline-none focus:border-[#C5A059]">
                    </div>

                    <!-- Photo Upload Section (Primary & Mobile Camera) -->
                    <div class="sm:col-span-2 bg-[#FAF8F4] p-5 rounded-2xl border border-[#EDE6DA] space-y-4">
                        <div>
                            <label class="block text-xs font-bold text-[#242424] mb-2">
                                صورة المنتج الحالية / رفع صورة جديدة من الجوال
                            </label>
                            
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 items-center">
                                <!-- Current Photo Display -->
                                @if($product->primary_image)
                                <div class="bg-white p-3 rounded-xl border border-gray-200 text-center">
                                    <span class="text-[10px] text-gray-400 block mb-1">الصورة الحالية:</span>
                                    <img src="{{ $product->primary_image }}" alt="" class="h-32 object-contain mx-auto rounded-lg">
                                </div>
                                @endif

                                <!-- Mobile Upload New Photo Dropzone -->
                                <div class="border-2 border-dashed border-[#C5A059]/60 hover:border-[#C5A059] rounded-xl p-4 bg-white text-center cursor-pointer transition">
                                    <label for="product_edit_image_file" class="cursor-pointer block">
                                        <template x-if="primaryPreview">
                                            <div>
                                                <img :src="primaryPreview" class="h-32 object-contain mx-auto rounded-lg mb-1 shadow-xs">
                                                <span class="text-xs text-[#C5A059] font-bold block">تم اختيار صورة جديدة</span>
                                            </div>
                                        </template>

                                        <template x-if="!primaryPreview">
                                            <div class="py-2">
                                                <x-icon name="camera" class="w-8 h-8 text-[#C5A059] mx-auto mb-1" />
                                                <span class="text-xs font-bold text-[#242424] block">التقط صورة بالكاميرا أو اختر من الجوال</span>
                                                <span class="text-[10px] text-gray-500">JPG, PNG, WEBP (حتى 10MB)</span>
                                            </div>
                                        </template>

                                        <input type="file" 
                                               id="product_edit_image_file" 
                                               name="image_file" 
                                               accept="image/*" 
                                               @change="handlePrimaryImage($event)"
                                               class="hidden">
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Additional Photos from Mobile -->
                        <div class="pt-3 border-t border-[#EDE6DA]">
                            <label class="block text-xs font-bold text-[#242424] mb-1">
                                إضافة صور جديدة للمعرض (من الجوال)
                            </label>
                            <label for="gallery_files_edit" class="flex items-center justify-between bg-white border border-[#EDE6DA] rounded-xl px-4 py-3 cursor-pointer hover:border-[#C5A059] transition">
                                <span class="text-xs text-gray-600 flex items-center gap-2">
                                    <x-icon name="images" class="w-4 h-4 text-[#C5A059]" />
                                    <span x-text="galleryCount > 0 ? ('تم اختيار ' + galleryCount + ' صور إضافية جديدة') : 'اختر صور إضافية من الجوال'"></span>
                                </span>
                                <span class="bg-[#FAF8F4] text-[#C5A059] text-xs font-bold px-3 py-1 rounded-lg border border-[#EDE6DA]">تحديد الصور</span>
                                <input type="file" 
                                       id="gallery_files_edit" 
                                       name="gallery_files[]" 
                                       accept="image/*" 
                                       multiple 
                                       @change="handleGalleryImages($event)"
                                       class="hidden">
                            </label>

                            @if($product->images->count() > 1)
                            <div class="flex items-center gap-2 mt-2 pt-2 overflow-x-auto">
                                <span class="text-[10px] text-gray-500 whitespace-nowrap">صور المعرض الحالية:</span>
                                @foreach($product->images as $img)
                                    <img src="{{ $img->image_path }}" class="w-12 h-12 object-cover rounded-lg border border-gray-200">
                                @endforeach
                            </div>
                            @endif
                        </div>

                        <!-- Fallback URL input -->
                        <div class="pt-2 border-t border-[#EDE6DA]">
                            <label class="block text-[11px] text-gray-600 mb-1">أو رابط صورة خارجي:</label>
                            <input type="url" name="image_url" value="{{ old('image_url', $product->primary_image) }}" class="w-full bg-white border border-[#EDE6DA] rounded-lg px-3 py-2 text-xs text-[#242424] focus:outline-none focus:border-[#C5A059]">
                        </div>
                    </div>

                    <!-- Fragrance Notes -->
                    <div>
                        <label class="block text-xs font-bold text-[#242424] mb-1.5">المواصفات الأساسية (Feature 1)</label>
                        <input type="text" name="fragrance_top" value="{{ old('fragrance_top', $product->fragrance_top) }}" class="w-full bg-[#FAF8F4] border border-[#EDE6DA] rounded-lg px-3.5 py-2.5 text-xs text-[#242424] focus:outline-none focus:border-[#C5A059]">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-[#242424] mb-1.5">الجودة وسلامة الغذاء (Feature 2)</label>
                        <input type="text" name="fragrance_heart" value="{{ old('fragrance_heart', $product->fragrance_heart) }}" class="w-full bg-[#FAF8F4] border border-[#EDE6DA] rounded-lg px-3.5 py-2.5 text-xs text-[#242424] focus:outline-none focus:border-[#C5A059]">
                    </div>

                    <div class="sm:col-span-2">
                        <label class="block text-xs font-bold text-[#242424] mb-1.5">المصدر والتعبئة (Feature 3)</label>
                        <input type="text" name="fragrance_base" value="{{ old('fragrance_base', $product->fragrance_base) }}" class="w-full bg-[#FAF8F4] border border-[#EDE6DA] rounded-lg px-3.5 py-2.5 text-xs text-[#242424] focus:outline-none focus:border-[#C5A059]">
                    </div>

                    <div class="sm:col-span-2">
                        <label class="block text-xs font-bold text-[#242424] mb-1.5">الوصف التفصيلي بالعربية <span class="text-red-500">*</span></label>
                        <textarea name="description_ar" rows="4" required class="w-full bg-[#FAF8F4] border border-[#EDE6DA] rounded-lg p-3 text-xs text-[#242424] focus:outline-none focus:border-[#C5A059]">{{ old('description_ar', $product->description_ar) }}</textarea>
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
                                <input type="text" name="seo_title" value="{{ old('seo_title', $product->seo_title) }}" placeholder="اتركه فارغاً ليتم استخدام اسم المنتج تلقائياً" class="w-full bg-white border border-[#EDE6DA] rounded-lg px-3 py-2 text-xs text-[#242424] focus:outline-none focus:border-[#C5A059]">
                            </div>
                            <div>
                                <label class="block text-[11px] font-bold text-[#242424] mb-1">وصف الميتا في نتائج بحث جوجل (SEO Description)</label>
                                <input type="text" name="seo_description" value="{{ old('seo_description', $product->seo_description) }}" placeholder="اتركه فارغاً ليتم استخلاص وصف تلقائي من وصف المنتج" class="w-full bg-white border border-[#EDE6DA] rounded-lg px-3 py-2 text-xs text-[#242424] focus:outline-none focus:border-[#C5A059]">
                            </div>
                        </div>
                    </div>

                    <div class="sm:col-span-2 flex items-center gap-6 pt-2">
                        <label class="flex items-center gap-2 text-xs font-semibold text-[#242424] cursor-pointer">
                            <input type="checkbox" name="is_featured" value="1" {{ $product->is_featured ? 'checked' : '' }} class="text-[#C5A059] rounded">
                            <span>عرض في قسم الأكثر طلباً بالرئيسية</span>
                        </label>

                        <label class="flex items-center gap-2 text-xs font-semibold text-[#242424] cursor-pointer">
                            <input type="checkbox" name="is_active" value="1" {{ $product->is_active ? 'checked' : '' }} class="text-[#C5A059] rounded">
                            <span>تفعيل ظهور المنتج بالمتجر</span>
                        </label>
                    </div>
                </div>

                <div class="pt-6 border-t border-gray-200 flex items-center gap-3">
                    <button type="submit" class="bg-[#C5A059] hover:bg-[#AF8B44] text-white text-xs font-bold px-7 py-3 rounded-xl transition shadow-md flex items-center gap-2">
                        <x-icon name="check" class="w-4 h-4" />
                        <span>حفظ التعديلات</span>
                    </button>
                    <a href="{{ route('admin.products.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-semibold px-5 py-3 rounded-xl transition">
                        إلغاء
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-admin-layout>
