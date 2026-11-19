<x-admin-layout>
    <x-slot:title>إدارة الصفحة الرئيسية والبنرات الترويجية</x-slot:title>

    <div class="space-y-8 text-right" x-data="{ 
            currentTab: 'all',
            createModalOpen: false,
            editModalOpen: false,
            activeBanner: null
         }">
        
        <!-- Top Bar with Actions -->
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 pb-4 border-b border-[#EDE6DA]">
            <div>
                <h2 class="text-xl font-bold text-[#242424] flex items-center gap-2">
                    <x-icon name="image" class="w-5 h-5 text-[#C5A059]" />
                    <span>إدارة الصفحة الرئيسية والبنرات الترويجية</span>
                </h2>
                <p class="text-xs text-[#777777] mt-1">
                    تحكم بالكامل في بنرات الواجهة (البنر الرئيسي، البنران الفرعيان، شبكة عروض زاد نيكس التوفيرية الـ 3) وأقسام المتجر بدون تعديل الكود.
                </p>
            </div>
            
            <div class="flex items-center gap-2.5">
                <a href="{{ route('home') }}" target="_blank" class="bg-white hover:bg-gray-50 border border-[#EDE6DA] text-[#242424] text-xs font-bold px-4 py-2.5 rounded-xl shadow-xs transition flex items-center gap-1.5">
                    <x-icon name="external-link" class="w-4 h-4 text-[#C5A059]" />
                    <span>معاينة المتجر المباشرة</span>
                </a>

                <button @click="createModalOpen = true" 
                        type="button" 
                        class="bg-[#C5A059] hover:bg-[#AF8B44] text-white text-xs sm:text-sm font-bold px-5 py-2.5 rounded-xl shadow-sm transition flex items-center gap-2">
                    <x-icon name="plus" class="w-4 h-4" />
                    <span>إضافة بنر ترويجي جديد</span>
                </button>
            </div>
        </div>

        <!-- Filter Tabs -->
        <div class="flex items-center gap-2 border-b border-[#EDE6DA] pb-2 overflow-x-auto text-xs font-bold">
            <button @click="currentTab = 'all'" 
                    class="px-4 py-2 rounded-xl transition flex items-center gap-1.5 whitespace-nowrap"
                    :class="currentTab === 'all' ? 'bg-[#C5A059] text-white shadow-xs' : 'text-[#777777] hover:bg-white hover:text-[#242424]'">
                <span>جميع البنرات</span>
                <span class="bg-black/15 text-[10px] px-1.5 py-0.5 rounded-full font-mono">{{ $banners->count() }}</span>
            </button>

            <button @click="currentTab = 'hero'" 
                    class="px-4 py-2 rounded-xl transition flex items-center gap-1.5 whitespace-nowrap"
                    :class="currentTab === 'hero' ? 'bg-[#C5A059] text-white shadow-xs' : 'text-[#777777] hover:bg-white hover:text-[#242424]'">
                <span>البنر الرئيسي الكبير (Hero)</span>
                <span class="bg-black/15 text-[10px] px-1.5 py-0.5 rounded-full font-mono">{{ $banners->where('placement', 'hero')->count() }}</span>
            </button>

            <button @click="currentTab = 'secondary'" 
                    class="px-4 py-2 rounded-xl transition flex items-center gap-1.5 whitespace-nowrap"
                    :class="currentTab === 'secondary' ? 'bg-[#C5A059] text-white shadow-xs' : 'text-[#777777] hover:bg-white hover:text-[#242424]'">
                <span>البنران الفرعيان (Secondary - 2)</span>
                <span class="bg-black/15 text-[10px] px-1.5 py-0.5 rounded-full font-mono">{{ $banners->where('placement', 'secondary')->count() }}</span>
            </button>

            <button @click="currentTab = 'offers'" 
                    class="px-4 py-2 rounded-xl transition flex items-center gap-1.5 whitespace-nowrap"
                    :class="currentTab === 'offers' ? 'bg-[#C5A059] text-white shadow-xs' : 'text-[#777777] hover:bg-white hover:text-[#242424]'">
                <span>عروض زاد نيكس التوفيرية</span>
                <span class="bg-black/15 text-[10px] px-1.5 py-0.5 rounded-full font-mono">{{ $banners->where('placement', 'offers')->count() }}</span>
            </button>

            <button @click="currentTab = 'sections'" 
                    class="px-4 py-2 rounded-xl transition flex items-center gap-1.5 whitespace-nowrap"
                    :class="currentTab === 'sections' ? 'bg-[#242424] text-white shadow-xs' : 'text-[#777777] hover:bg-white hover:text-[#242424]'">
                <x-icon name="sliders" class="w-3.5 h-3.5 text-[#C5A059]" />
                <span>التحكم في أقسام وعناوين الصفحة</span>
            </button>
        </div>

        <!-- TAB 1: Banners Grid List -->
        <div x-show="currentTab !== 'sections'" class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($banners as $banner)
                <div x-show="currentTab === 'all' || currentTab === '{{ $banner->placement }}'" 
                     class="bg-white rounded-2xl border border-[#EDE6DA] overflow-hidden shadow-xs flex flex-col justify-between transition hover:shadow-md">
                    
                    <!-- Preview Image & Badges -->
                    <div class="h-48 relative bg-gray-900 group overflow-hidden">
                        <img src="{{ $banner->image_url }}" alt="" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 opacity-80">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/85 via-black/30 to-black/30 p-4 flex flex-col justify-between text-white">
                            <div class="flex items-center justify-between">
                                <span class="text-[10px] font-bold px-2.5 py-0.5 rounded-full shadow-xs
                                    {{ $banner->placement === 'hero' ? 'bg-[#C5A059] text-black' : ($banner->placement === 'secondary' ? 'bg-blue-600 text-white' : 'bg-emerald-600 text-white') }}">
                                    {{ $banner->placement === 'hero' ? 'بنر رئيسي' : ($banner->placement === 'secondary' ? 'بنر فرعي (2)' : 'عرض توفيري (3)') }}
                                </span>

                                <form action="{{ route('admin.banners.toggle', $banner->id) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="text-[11px] px-2.5 py-0.5 rounded-full font-bold transition {{ $banner->is_active ? 'bg-emerald-500/20 text-emerald-300 border border-emerald-500/40' : 'bg-gray-600/40 text-gray-300 border border-gray-600' }}">
                                        {{ $banner->is_active ? '● مفعل' : '○ متوقف' }}
                                    </button>
                                </form>
                            </div>

                            <div>
                                @if($banner->badge_ar)
                                <span class="text-[10px] text-[#E3CD98] bg-black/40 px-2 py-0.5 rounded-md border border-[#C5A059]/30 inline-block mb-1">
                                    {{ $banner->badge_ar }}
                                </span>
                                @endif
                                <h3 class="font-bold text-sm text-white line-clamp-1">{{ $banner->title_ar }}</h3>
                                @if($banner->subtitle_ar)
                                <p class="text-[11px] text-gray-300 mt-0.5 line-clamp-1">{{ $banner->subtitle_ar }}</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Details & Controls -->
                    <div class="p-4 space-y-3 flex-1 flex flex-col justify-between">
                        <div class="space-y-1.5 text-xs text-[#777777]">
                            <div class="flex items-center justify-between">
                                <span class="font-medium text-gray-500">الوجهة:</span>
                                <span class="font-mono text-[#242424] truncate max-w-[180px] dir-ltr text-left">{{ $banner->button_url ?: '/' }}</span>
                            </div>

                            <div class="flex items-center justify-between">
                                <span class="font-medium text-gray-500">ترتيب الظهور:</span>
                                <span class="font-bold text-[#C5A059]">#{{ $banner->sort_order }}</span>
                            </div>

                            @if($banner->mobile_image_url)
                            <div class="flex items-center justify-between">
                                <span class="font-medium text-gray-500">نسخة الجوال:</span>
                                <span class="text-emerald-600 font-bold text-[10px] flex items-center gap-1">
                                    <x-icon name="smartphone" class="w-3.5 h-3.5" />
                                    <span>مخصصة</span>
                                </span>
                            </div>
                            @endif
                        </div>

                        <!-- Action Buttons -->
                        <div class="pt-3 border-t border-[#EDE6DA] flex items-center justify-between gap-2">
                            <form action="{{ route('admin.banners.destroy', $banner->id) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من حذف هذا البنر؟');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-700 text-xs font-semibold p-1.5 rounded-lg hover:bg-red-50 transition" title="حذف البنر">
                                    <x-icon name="trash" class="w-4 h-4" />
                                </button>
                            </form>

                            <button @click="activeBanner = {{ json_encode($banner) }}; editModalOpen = true" 
                                    type="button" 
                                    class="bg-[#FAF8F4] hover:bg-[#F3ECE1] border border-[#EDE6DA] text-[#242424] text-xs font-bold px-3 py-1.5 rounded-lg transition flex items-center gap-1.5">
                                <x-icon name="edit" class="w-3.5 h-3.5 text-[#C5A059]" />
                                <span>تعديل البنر والصور</span>
                            </button>
                        </div>
                    </div>

                </div>
                @empty
                <div class="col-span-full py-16 text-center bg-white rounded-2xl border border-[#EDE6DA]">
                    <x-icon name="image" class="w-12 h-12 text-gray-300 mx-auto mb-3" />
                    <h4 class="font-bold text-gray-700 text-base">لا توجد بنرات ترويجية حالياً</h4>
                    <p class="text-xs text-gray-500 mt-1">اضغط على زر "إضافة بنر ترويجي جديد" بالأعلى لإنشاء أول بنر في المتجر</p>
                </div>
                @endforelse
            </div>
        </div>

        <!-- TAB 2: Homepage Sections Visibility & Titles Editor -->
        <div x-show="currentTab === 'sections'" class="bg-white p-6 sm:p-8 rounded-2xl border border-[#EDE6DA] shadow-xs max-w-4xl space-y-6">
            <div class="border-b border-[#EDE6DA] pb-4">
                <h3 class="text-base font-bold text-[#242424]">التحكم في أقسام وعناوين الصفحة الرئيسية</h3>
                <p class="text-xs text-[#777777] mt-1">يمكنك تفعيل أو إخفاء أي قسم من أقسام المتجر الرئيسية وتعديل عناوينه بما يناسب خططك التسويقية</p>
            </div>

            <form action="{{ route('admin.homepage.sections') }}" method="POST" class="space-y-6">
                @csrf

                <!-- Section: Promotional Offers Title -->
                <div class="p-4 bg-[#FAF8F4] rounded-xl border border-[#EDE6DA] space-y-3">
                    <div class="flex items-center justify-between">
                        <div>
                            <span class="text-xs font-bold text-[#242424] block">شبكة عروض زاد نيكس التوفيرية (الـ 3 بنرات)</span>
                            <span class="text-[11px] text-[#777777]">تظهر بعد البنرات الفرعية كشبكة ثلاثية لعروض الخصم</span>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="homepage_section_offers_visible" value="1" {{ ($settings['homepage_section_offers_visible'] ?? '1') === '1' ? 'checked' : '' }} class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:right-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#C5A059]"></div>
                        </label>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-2 border-t border-[#EDE6DA]/60">
                        <div>
                            <label class="block text-[11px] font-bold text-gray-700 mb-1">عنوان القسم:</label>
                            <input type="text" name="homepage_offers_title" value="{{ $settings['homepage_offers_title'] ?? 'عروض زاد نيكس التوفيرية' }}" class="w-full bg-white border border-[#EDE6DA] focus:border-[#C5A059] rounded-lg p-2.5 text-xs text-[#242424]">
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold text-gray-700 mb-1">الوصف الفرعي التوضيحي:</label>
                            <input type="text" name="homepage_offers_subtitle" value="{{ $settings['homepage_offers_subtitle'] ?? 'اكتشف باقة مختارة من أجود المواد الغذائية والمنظفات بخصومات استثنائية' }}" class="w-full bg-white border border-[#EDE6DA] focus:border-[#C5A059] rounded-lg p-2.5 text-xs text-[#242424]">
                        </div>
                    </div>
                </div>

                <!-- Toggles for other sections -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- Hero Section -->
                    <div class="flex items-center justify-between p-3.5 bg-gray-50 rounded-xl border border-gray-200">
                        <span class="text-xs font-bold text-gray-800">البنر الرئيسي الكبير (Hero)</span>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="homepage_section_hero_visible" value="1" {{ ($settings['homepage_section_hero_visible'] ?? '1') === '1' ? 'checked' : '' }} class="sr-only peer">
                            <div class="w-9 h-5 bg-gray-200 rounded-full peer peer-checked:after:translate-x-full after:absolute after:top-[2px] after:right-[2px] after:bg-white after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-[#C5A059]"></div>
                        </label>
                    </div>

                    <!-- Secondary Banners -->
                    <div class="flex items-center justify-between p-3.5 bg-gray-50 rounded-xl border border-gray-200">
                        <span class="text-xs font-bold text-gray-800">البنران الفرعيان (Secondary)</span>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="homepage_section_secondary_visible" value="1" {{ ($settings['homepage_section_secondary_visible'] ?? '1') === '1' ? 'checked' : '' }} class="sr-only peer">
                            <div class="w-9 h-5 bg-gray-200 rounded-full peer peer-checked:after:translate-x-full after:absolute after:top-[2px] after:right-[2px] after:bg-white after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-[#C5A059]"></div>
                        </label>
                    </div>

                    <!-- Categories Section -->
                    <div class="flex items-center justify-between p-3.5 bg-gray-50 rounded-xl border border-gray-200">
                        <span class="text-xs font-bold text-gray-800">أقسام وتصنيفات المتجر</span>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="homepage_section_categories_visible" value="1" {{ ($settings['homepage_section_categories_visible'] ?? '1') === '1' ? 'checked' : '' }} class="sr-only peer">
                            <div class="w-9 h-5 bg-gray-200 rounded-full peer peer-checked:after:translate-x-full after:absolute after:top-[2px] after:right-[2px] after:bg-white after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-[#C5A059]"></div>
                        </label>
                    </div>

                    <!-- Best Sellers -->
                    <div class="flex items-center justify-between p-3.5 bg-gray-50 rounded-xl border border-gray-200">
                        <span class="text-xs font-bold text-gray-800">الأكثر مبيعاً (Best Sellers)</span>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="homepage_section_bestsellers_visible" value="1" {{ ($settings['homepage_section_bestsellers_visible'] ?? '1') === '1' ? 'checked' : '' }} class="sr-only peer">
                            <div class="w-9 h-5 bg-gray-200 rounded-full peer peer-checked:after:translate-x-full after:absolute after:top-[2px] after:right-[2px] after:bg-white after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-[#C5A059]"></div>
                        </label>
                    </div>

                    <!-- New Arrivals -->
                    <div class="flex items-center justify-between p-3.5 bg-gray-50 rounded-xl border border-gray-200">
                        <span class="text-xs font-bold text-gray-800">وصل حديثاً (New Arrivals)</span>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="homepage_section_new_arrivals_visible" value="1" {{ ($settings['homepage_section_new_arrivals_visible'] ?? '1') === '1' ? 'checked' : '' }} class="sr-only peer">
                            <div class="w-9 h-5 bg-gray-200 rounded-full peer peer-checked:after:translate-x-full after:absolute after:top-[2px] after:right-[2px] after:bg-white after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-[#C5A059]"></div>
                        </label>
                    </div>

                    <!-- Brand Story -->
                    <div class="flex items-center justify-between p-3.5 bg-gray-50 rounded-xl border border-gray-200">
                        <span class="text-xs font-bold text-gray-800">قصة إبداع زاد نيكس (Brand Story)</span>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="homepage_section_story_visible" value="1" {{ ($settings['homepage_section_story_visible'] ?? '1') === '1' ? 'checked' : '' }} class="sr-only peer">
                            <div class="w-9 h-5 bg-gray-200 rounded-full peer peer-checked:after:translate-x-full after:absolute after:top-[2px] after:right-[2px] after:bg-white after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-[#C5A059]"></div>
                        </label>
                    </div>
                </div>

                <div class="pt-4 border-t border-[#EDE6DA] flex justify-end">
                    <button type="submit" class="bg-[#C5A059] hover:bg-[#AF8B44] text-white text-xs font-bold px-6 py-2.5 rounded-xl shadow-sm transition">
                        حفظ إعدادات الأقسام
                    </button>
                </div>
            </form>
        </div>

        <!-- CREATE BANNER MODAL -->
        <div x-show="createModalOpen" 
             x-cloak 
             class="fixed inset-0 z-50 overflow-y-auto bg-black/60 backdrop-blur-xs flex items-center justify-center p-4">
            <div @click.away="createModalOpen = false" 
                 class="bg-white rounded-3xl max-w-2xl w-full p-6 sm:p-8 shadow-2xl border border-[#EDE6DA] text-right space-y-5 animate-fade-in"
                 x-data="{ 
                     desktopPreview: null, 
                     mobilePreview: null,
                     linkType: 'category',
                     selectedCategory: '',
                     selectedProduct: '',
                     customUrl: ''
                 }">
                
                <div class="flex items-center justify-between pb-3 border-b border-[#EDE6DA]">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-lg bg-[#FAF8F4] text-[#C5A059] flex items-center justify-center">
                            <x-icon name="plus" class="w-4 h-4" />
                        </div>
                        <h3 class="font-bold text-base text-[#242424]">إضافة بنر ترويجي جديد</h3>
                    </div>
                    <button @click="createModalOpen = false" type="button" class="text-gray-400 hover:text-gray-600 p-1.5 rounded-lg">
                        <x-icon name="x" class="w-5 h-5" />
                    </button>
                </div>

                <form action="{{ route('admin.banners.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf

                    <!-- Placement & Order -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-[#242424] mb-1.5">موقع البنر في الصفحة (Placement): <span class="text-red-500">*</span></label>
                            <select name="placement" required class="w-full bg-[#FAF8F4] border border-[#EDE6DA] focus:border-[#C5A059] rounded-xl p-3 text-xs outline-none">
                                <option value="hero">البنر الرئيسي الكبير (Hero Top)</option>
                                <option value="secondary">البنران الفرعيان (Secondary - 2 في صف)</option>
                                <option value="offers">عروض زاد نيكس (Offers - 3 في صف)</option>
                                <option value="promo">بنر ترويجي إضافي (Promo)</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-[#242424] mb-1.5">ترتيب الظهور في القسم:</label>
                            <input type="number" name="sort_order" value="1" min="1" class="w-full bg-[#FAF8F4] border border-[#EDE6DA] focus:border-[#C5A059] rounded-xl p-3 text-xs outline-none">
                        </div>
                    </div>

                    <!-- Title & Badge -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-[#242424] mb-1.5">العنوان الرئيسي للبنر: <span class="text-red-500">*</span></label>
                            <input type="text" name="title_ar" required placeholder="مثال: كرتون مياه شرب نقية" class="w-full bg-[#FAF8F4] border border-[#EDE6DA] focus:border-[#C5A059] rounded-xl p-3 text-xs outline-none">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-[#242424] mb-1.5">الشارة الترويجية (Badge):</label>
                            <input type="text" name="badge_ar" placeholder="مثال: خصم 60% أو تشكيلة حصرية" class="w-full bg-[#FAF8F4] border border-[#EDE6DA] focus:border-[#C5A059] rounded-xl p-3 text-xs outline-none">
                        </div>
                    </div>

                    <!-- Subtitle -->
                    <div>
                        <label class="block text-xs font-bold text-[#242424] mb-1.5">الوصف الفرعي (اختياري):</label>
                        <input type="text" name="subtitle_ar" placeholder="مثال: 87 ر.س بدلاً من 120 ر.س على سلال التموين والمشروبات" class="w-full bg-[#FAF8F4] border border-[#EDE6DA] focus:border-[#C5A059] rounded-xl p-3 text-xs outline-none">
                    </div>

                    <!-- Destination Link -->
                    <div class="p-3.5 bg-[#FAF8F4] rounded-xl border border-[#EDE6DA] space-y-3">
                        <label class="block text-xs font-bold text-[#242424]">رابط وتوجيه البنر عند النقر:</label>
                        
                        <div class="grid grid-cols-3 gap-2">
                            <label class="flex items-center gap-1.5 text-xs cursor-pointer">
                                <input type="radio" name="link_type" value="category" x-model="linkType" class="text-[#C5A059]">
                                <span>قسم بالمتجر</span>
                            </label>
                            <label class="flex items-center gap-1.5 text-xs cursor-pointer">
                                <input type="radio" name="link_type" value="product" x-model="linkType" class="text-[#C5A059]">
                                <span>منتج محدد</span>
                            </label>
                            <label class="flex items-center gap-1.5 text-xs cursor-pointer">
                                <input type="radio" name="link_type" value="url" x-model="linkType" class="text-[#C5A059]">
                                <span>رابط مخصص</span>
                            </label>
                        </div>

                        <!-- Category Selector -->
                        <div x-show="linkType === 'category'">
                            <select @change="customUrl = $event.target.value" class="w-full bg-white border border-[#EDE6DA] rounded-lg p-2.5 text-xs">
                                <option value="/products">-- اختر قسماً بالمتجر --</option>
                                @foreach($categories as $cat)
                                <option value="/category/{{ $cat->slug }}">{{ $cat->name_ar }}</option>
                                @foreach($cat->children as $sub)
                                <option value="/category/{{ $sub->slug }}">↳ {{ $sub->name_ar }}</option>
                                @endforeach
                                @endforeach
                            </select>
                        </div>

                        <!-- Product Selector -->
                        <div x-show="linkType === 'product'" style="display: none;">
                            <select @change="customUrl = $event.target.value" class="w-full bg-white border border-[#EDE6DA] rounded-lg p-2.5 text-xs">
                                <option value="/products">-- اختر منتجاً محدداً --</option>
                                @foreach($products as $p)
                                <option value="/product/{{ $p->slug }}">{{ $p->name_ar }} ({{ $p->regular_price }} ر.س)</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Custom URL Input -->
                        <div>
                            <input type="text" name="button_url" x-model="customUrl" placeholder="/category/perfumes أو https://..." class="w-full bg-white border border-[#EDE6DA] rounded-lg p-2.5 text-xs dir-ltr text-left">
                        </div>
                    </div>

                    <!-- Images Upload (Desktop & Mobile) -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2 border-t border-[#EDE6DA]">
                        <!-- Desktop Artwork -->
                        <div class="space-y-2">
                            <label class="block text-xs font-bold text-[#242424]">صورة الكمبيوتر (Desktop Artwork):</label>
                            <label class="cursor-pointer flex items-center justify-center gap-2 bg-[#FAF8F4] hover:bg-[#F3ECE1] border border-dashed border-[#C5A059] p-3 rounded-xl text-xs font-bold transition">
                                <x-icon name="camera" class="w-4 h-4 text-[#C5A059]" />
                                <span>رفع صورة من الجوال</span>
                                <input type="file" 
                                       name="image_file" 
                                       accept="image/*" 
                                       capture="environment"
                                       @change="const f = $event.target.files[0]; if(f) desktopPreview = URL.createObjectURL(f);"
                                       class="hidden">
                            </label>
                            <input type="url" name="image_url" placeholder="أو رابط صورة مباشر..." class="w-full bg-[#FAF8F4] border border-[#EDE6DA] rounded-lg p-2 text-xs dir-ltr text-left">
                            <div x-show="desktopPreview" class="h-24 bg-gray-900 rounded-lg overflow-hidden border">
                                <img :src="desktopPreview" class="w-full h-full object-cover">
                            </div>
                        </div>

                        <!-- Mobile Artwork -->
                        <div class="space-y-2">
                            <label class="block text-xs font-bold text-[#242424]">صورة الجوال (Mobile Portrait):</label>
                            <label class="cursor-pointer flex items-center justify-center gap-2 bg-[#FAF8F4] hover:bg-[#F3ECE1] border border-dashed border-gray-300 p-3 rounded-xl text-xs font-bold transition">
                                <x-icon name="smartphone" class="w-4 h-4 text-[#C5A059]" />
                                <span>رفع نسخة الجوال</span>
                                <input type="file" 
                                       name="mobile_image_file" 
                                       accept="image/*" 
                                       capture="environment"
                                       @change="const f = $event.target.files[0]; if(f) mobilePreview = URL.createObjectURL(f);"
                                       class="hidden">
                            </label>
                            <input type="url" name="mobile_image_url" placeholder="أو رابط نسخة الجوال..." class="w-full bg-[#FAF8F4] border border-[#EDE6DA] rounded-lg p-2 text-xs dir-ltr text-left">
                            <div x-show="mobilePreview" class="h-24 bg-gray-900 rounded-lg overflow-hidden border">
                                <img :src="mobilePreview" class="w-full h-full object-cover">
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-4 border-t border-[#EDE6DA]">
                        <button @click="createModalOpen = false" type="button" class="px-5 py-2.5 rounded-xl border border-gray-300 text-xs font-semibold text-gray-700 hover:bg-gray-50 transition">
                            إلغاء
                        </button>
                        <button type="submit" class="bg-[#C5A059] hover:bg-[#AF8B44] text-white text-xs font-bold px-6 py-2.5 rounded-xl shadow-md transition">
                            حفظ وتفعيل البنر
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- EDIT BANNER MODAL -->
        <div x-show="editModalOpen && activeBanner" 
             x-cloak 
             class="fixed inset-0 z-50 overflow-y-auto bg-black/60 backdrop-blur-xs flex items-center justify-center p-4">
            <div @click.away="editModalOpen = false" 
                 class="bg-white rounded-3xl max-w-2xl w-full p-6 sm:p-8 shadow-2xl border border-[#EDE6DA] text-right space-y-5 animate-fade-in"
                 x-data="{ editDesktopPreview: null, editMobilePreview: null }">
                
                <div class="flex items-center justify-between pb-3 border-b border-[#EDE6DA]">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-lg bg-[#FAF8F4] text-[#C5A059] flex items-center justify-center">
                            <x-icon name="edit" class="w-4 h-4" />
                        </div>
                        <h3 class="font-bold text-base text-[#242424]">تعديل بيانات وصور البنر الترويجي</h3>
                    </div>
                    <button @click="editModalOpen = false" type="button" class="text-gray-400 hover:text-gray-600 p-1.5 rounded-lg">
                        <x-icon name="x" class="w-5 h-5" />
                    </button>
                </div>

                <form :action="'/admin/banners/' + activeBanner.id" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-[#242424] mb-1.5">موقع البنر في الصفحة:</label>
                            <select name="placement" x-model="activeBanner.placement" required class="w-full bg-[#FAF8F4] border border-[#EDE6DA] focus:border-[#C5A059] rounded-xl p-3 text-xs outline-none">
                                <option value="hero">البنر الرئيسي الكبير (Hero Top)</option>
                                <option value="secondary">البنران الفرعيان (Secondary - 2 في صف)</option>
                                <option value="offers">عروض زاد نيكس (Offers - 3 في صف)</option>
                                <option value="promo">بنر ترويجي إضافي (Promo)</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-[#242424] mb-1.5">ترتيب الظهور:</label>
                            <input type="number" name="sort_order" x-model="activeBanner.sort_order" min="1" class="w-full bg-[#FAF8F4] border border-[#EDE6DA] focus:border-[#C5A059] rounded-xl p-3 text-xs outline-none">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-[#242424] mb-1.5">العنوان الرئيسي:</label>
                            <input type="text" name="title_ar" x-model="activeBanner.title_ar" required class="w-full bg-[#FAF8F4] border border-[#EDE6DA] focus:border-[#C5A059] rounded-xl p-3 text-xs outline-none">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-[#242424] mb-1.5">الشارة الترويجية (Badge):</label>
                            <input type="text" name="badge_ar" x-model="activeBanner.badge_ar" class="w-full bg-[#FAF8F4] border border-[#EDE6DA] focus:border-[#C5A059] rounded-xl p-3 text-xs outline-none">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-[#242424] mb-1.5">الوصف الفرعي:</label>
                        <input type="text" name="subtitle_ar" x-model="activeBanner.subtitle_ar" class="w-full bg-[#FAF8F4] border border-[#EDE6DA] focus:border-[#C5A059] rounded-xl p-3 text-xs outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-[#242424] mb-1.5">رابط التوجيه (Destination URL):</label>
                        <input type="text" name="button_url" x-model="activeBanner.button_url" required class="w-full bg-[#FAF8F4] border border-[#EDE6DA] focus:border-[#C5A059] rounded-xl p-3 text-xs dir-ltr text-left outline-none">
                    </div>

                    <!-- Images Replacement -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2 border-t border-[#EDE6DA]">
                        <div class="space-y-2">
                            <label class="block text-xs font-bold text-[#242424]">تغيير صورة الكمبيوتر (Desktop):</label>
                            <label class="cursor-pointer flex items-center justify-center gap-2 bg-[#FAF8F4] hover:bg-[#F3ECE1] border border-dashed border-[#C5A059] p-2.5 rounded-xl text-xs font-bold transition">
                                <x-icon name="camera" class="w-4 h-4 text-[#C5A059]" />
                                <span>رفع صورة جديدة</span>
                                <input type="file" 
                                       name="image_file" 
                                       accept="image/*" 
                                       capture="environment"
                                       @change="const f = $event.target.files[0]; if(f) editDesktopPreview = URL.createObjectURL(f);"
                                       class="hidden">
                            </label>
                            <input type="url" name="image_url" x-model="activeBanner.image_url" placeholder="أو رابط مباشر..." class="w-full bg-[#FAF8F4] border border-[#EDE6DA] rounded-lg p-2 text-xs dir-ltr text-left">
                            <div class="h-24 bg-gray-900 rounded-lg overflow-hidden border">
                                <img :src="editDesktopPreview || activeBanner.image_url" class="w-full h-full object-cover">
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label class="block text-xs font-bold text-[#242424]">تغيير صورة الجوال (Mobile):</label>
                            <label class="cursor-pointer flex items-center justify-center gap-2 bg-[#FAF8F4] hover:bg-[#F3ECE1] border border-dashed border-gray-300 p-2.5 rounded-xl text-xs font-bold transition">
                                <x-icon name="smartphone" class="w-4 h-4 text-[#C5A059]" />
                                <span>رفع نسخة الجوال</span>
                                <input type="file" 
                                       name="mobile_image_file" 
                                       accept="image/*" 
                                       capture="environment"
                                       @change="const f = $event.target.files[0]; if(f) editMobilePreview = URL.createObjectURL(f);"
                                       class="hidden">
                            </label>
                            <input type="url" name="mobile_image_url" x-model="activeBanner.mobile_image_url" placeholder="أو رابط مباشر..." class="w-full bg-[#FAF8F4] border border-[#EDE6DA] rounded-lg p-2 text-xs dir-ltr text-left">
                            <div class="h-24 bg-gray-900 rounded-lg overflow-hidden border">
                                <img :src="editMobilePreview || activeBanner.mobile_image_url || activeBanner.image_url" class="w-full h-full object-cover">
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-between pt-4 border-t border-[#EDE6DA]">
                        <label class="flex items-center gap-2 text-xs font-semibold text-[#242424] cursor-pointer">
                            <input type="checkbox" name="is_active" value="1" :checked="activeBanner.is_active" class="text-[#C5A059] rounded">
                            <span>مفعل بالواجهة</span>
                        </label>

                        <div class="flex items-center gap-2">
                            <button @click="editModalOpen = false" type="button" class="px-5 py-2.5 rounded-xl border border-gray-300 text-xs font-semibold text-gray-700 hover:bg-gray-50 transition">
                                إلغاء
                            </button>
                            <button type="submit" class="bg-[#C5A059] hover:bg-[#AF8B44] text-white text-xs font-bold px-6 py-2.5 rounded-xl shadow-md transition">
                                حفظ التعديلات
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

    </div>
</x-admin-layout>
