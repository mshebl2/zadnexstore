<x-app-layout>
    <x-slot:title>جميع المنتجات | متجر زاد نيكس</x-slot:title>
    <x-slot:description>تصفح أجود المواد الغذائية التموينية، المشروبات، المنظفات ومستلزمات البيت بأفضل الأسعار وسلامة غذائية معتمدة.</x-slot:description>

    <!-- Header Banner -->
    <div class="bg-white border-b border-[#EDE6DA] py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-right">
            <nav class="text-xs text-[#777777] mb-2 flex items-center gap-2">
                <a href="{{ route('home') }}" class="hover:text-[#C5A059]">الرئيسية</a>
                <span>/</span>
                <span class="text-[#242424] font-medium">جميع المنتجات</span>
            </nav>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-[#242424]">تشكيلة منتجات زاد نيكس الفاخرة</h1>
            <p class="text-xs text-[#777777] mt-1">تصفح {{ $products->total() }} منتج منتقى بعناية فائقة</p>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        
        <!-- Search Bar Section -->
        <div class="mb-6 bg-white p-4 sm:p-5 rounded-2xl border border-[#EDE6DA] shadow-xs" 
             x-data="{
                 query: '{{ request('q') }}',
                 results: [],
                 loading: false,
                 isOpen: false,
                 searchProducts() {
                     if (this.query.trim().length < 2) {
                         this.results = [];
                         this.isOpen = false;
                         return;
                     }
                     this.loading = true;
                     this.isOpen = true;
                     fetch('/api/search?q=' + encodeURIComponent(this.query.trim()))
                         .then(r => r.json())
                         .then(data => {
                             this.results = data.products || [];
                             this.loading = false;
                         })
                         .catch(() => { this.loading = false; });
                 }
             }">
            <form method="GET" action="{{ route('products.index') }}" class="relative">
                @if(request('category'))
                    <input type="hidden" name="category" value="{{ request('category') }}">
                @endif
                @if(request('sort'))
                    <input type="hidden" name="sort" value="{{ request('sort') }}">
                @endif

                <div class="flex items-center gap-3">
                    <div class="relative flex-1">
                        <input type="text" 
                               name="q" 
                               x-model="query"
                               @input.debounce.300ms="searchProducts()"
                               @focus="if(query.trim().length >= 2) isOpen = true"
                               @click.away="isOpen = false"
                               placeholder="ابحث عن مواد غذائية، مشروبات، كراتين مياه، منظفات، كماليات..." 
                               style="padding-right: 68px !important; padding-left: 48px !important;"
                               class="w-full bg-[#FAF8F4] border border-[#EDE6DA] focus:border-[#C5A059] focus:ring-1 focus:ring-[#C5A059] rounded-xl py-3.5 text-xs sm:text-sm text-[#242424] placeholder-[#777777] outline-none transition">
                        
                        <!-- Search Icon (Right side with generous padding) -->
                        <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-[#C5A059] flex items-center justify-center">
                            <x-icon name="search" class="w-5 h-5" />
                        </div>
                        
                        <!-- Clear Search Button (Left side) -->
                        <button type="button" 
                                x-show="query.length > 0" 
                                @click="query = ''; results = []; isOpen = false; $el.form.submit()" 
                                class="absolute left-3.5 top-1/2 -translate-y-1/2 text-[#777777] hover:text-[#242424] p-1.5 rounded-full hover:bg-gray-100 transition"
                                title="مسح البحث">
                            <x-icon name="x" class="w-4 h-4" />
                        </button>
                    </div>

                    <button type="submit" class="bg-[#C5A059] hover:bg-[#AF8B44] text-[#121212] px-6 py-3.5 rounded-xl text-xs sm:text-sm font-bold transition flex items-center gap-2 flex-shrink-0 shadow-sm">
                        <span>بحث</span>
                    </button>
                </div>

                <!-- Instant Live Suggestions Dropdown -->
                <div x-show="isOpen && query.trim().length >= 2" 
                     x-cloak
                     class="absolute top-full right-0 left-0 mt-2 bg-white rounded-2xl border border-[#EDE6DA] shadow-2xl p-3 z-50 max-h-80 overflow-y-auto">
                    <div x-show="loading" class="py-5 text-center text-xs text-[#777777] flex items-center justify-center gap-2">
                        <x-icon name="loader" class="w-4 h-4 animate-spin text-[#C5A059]" />
                        <span>جاري البحث في التشكيلة...</span>
                    </div>

                    <div x-show="!loading && results.length > 0" class="space-y-1.5">
                        <template x-for="item in results" :key="item.id">
                            <a :href="item.url" class="flex items-center gap-3 p-2.5 rounded-xl hover:bg-[#FAF8F4] transition border border-transparent hover:border-[#EDE6DA] group">
                                <img :src="item.image" :alt="item.name" class="w-11 h-11 object-cover rounded-lg flex-shrink-0 bg-gray-100 border border-gray-100">
                                <div class="flex-1 min-w-0">
                                    <div class="text-xs font-bold text-[#242424] group-hover:text-[#C5A059] transition truncate" x-text="item.name"></div>
                                    <div class="text-[10px] text-[#777777]" x-text="item.category"></div>
                                </div>
                                <div class="text-xs font-bold text-[#C5A059] whitespace-nowrap" x-text="item.price"></div>
                            </a>
                        </template>
                    </div>

                    <div x-show="!loading && results.length === 0" class="py-6 px-4 text-center">
                        <p class="text-xs font-bold text-[#242424]">لا توجد منتجات مطابقة لـ "<span x-text="query" class="text-[#C5A059]"></span>"</p>
                        <p class="text-[11px] text-[#A59D92] mt-1">جرب البحث بكلمات مثل (أرز، مياه، زيت، مسحوق غسيل، مناديل)</p>
                    </div>
                </div>
            </form>

            @if(request('q'))
                <div class="mt-3 pt-3 border-t border-[#EDE6DA] flex items-center justify-between text-xs text-[#777777]">
                    <div class="flex items-center gap-2">
                        <span>نتائج البحث عن:</span>
                        <span class="bg-[#FAF8F4] border border-[#C5A059]/40 text-[#242424] font-bold px-2.5 py-0.5 rounded-full">
                            "{{ request('q') }}"
                        </span>
                        <span>({{ $products->total() }} منتج)</span>
                    </div>
                    <a href="{{ route('products.index', ['category' => request('category'), 'sort' => request('sort')]) }}" 
                       class="text-red-600 hover:underline flex items-center gap-1 font-medium">
                        <x-icon name="x" class="w-3.5 h-3.5" />
                        <span>إلغاء البحث</span>
                    </a>
                </div>
            @endif
        </div>

        <!-- Filter & Sort Bar -->
        <div class="bg-white p-4 rounded-xl border border-[#EDE6DA] mb-6 flex flex-col md:flex-row items-center justify-between gap-4">
            
            <!-- Category Pills -->
            <div class="flex items-center gap-2 overflow-x-auto w-full md:w-auto pb-2 md:pb-0 scrollbar-none">
                <a href="{{ route('products.index') }}" 
                   class="px-4 py-2 rounded-lg text-xs font-semibold whitespace-nowrap transition {{ !request('category') ? 'bg-[#C5A059] text-white shadow-xs' : 'bg-[#FAF8F4] text-[#242424] hover:bg-[#EDE6DA]' }}">
                    الكل
                </a>
                @foreach($categories as $cat)
                <a href="{{ route('products.index', ['category' => $cat->slug, 'sort' => request('sort')]) }}" 
                   class="px-4 py-2 rounded-lg text-xs font-semibold whitespace-nowrap transition {{ request('category') == $cat->slug ? 'bg-[#C5A059] text-white shadow-xs' : 'bg-[#FAF8F4] text-[#242424] hover:bg-[#EDE6DA]' }}">
                    {{ $cat->name_ar }}
                </a>
                @endforeach
            </div>

            <!-- Sort Dropdown -->
            <form method="GET" action="{{ route('products.index') }}" class="flex items-center gap-2 w-full md:w-auto justify-end">
                @if(request('category'))
                    <input type="hidden" name="category" value="{{ request('category') }}">
                @endif
                @if(request('q'))
                    <input type="hidden" name="q" value="{{ request('q') }}">
                @endif
                
                <label for="sort" class="text-xs text-[#777777] whitespace-nowrap">الترتيب حسب:</label>
                <select name="sort" id="sort" onchange="this.form.submit()" class="bg-[#FAF8F4] border border-[#EDE6DA] rounded-lg px-3 py-1.5 text-xs text-[#242424] focus:outline-none focus:border-[#C5A059]">
                    <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>الأحدث</option>
                    <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>السعر: من الأقل للأعلى</option>
                    <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>السعر: من الأعلى للأقل</option>
                </select>
            </form>
        </div>

        @php
            $currentCat = request('category') ? \App\Models\Category::with('children')->where('slug', request('category'))->first() : null;
        @endphp
        @if($currentCat && $currentCat->children->isNotEmpty())
        <div class="flex items-center gap-2 mb-6 pb-2 overflow-x-auto text-xs bg-white p-3 rounded-xl border border-[#EDE6DA]">
            <span class="text-[#C5A059] font-bold whitespace-nowrap">الأقسام الفرعية لـ {{ $currentCat->name_ar }}:</span>
            @foreach($currentCat->children as $sub)
            <a href="{{ route('products.index', ['category' => $sub->slug]) }}" class="bg-[#FAF8F4] hover:bg-[#C5A059] hover:text-white text-[#555555] px-3 py-1.5 rounded-lg border border-[#EDE6DA] whitespace-nowrap transition font-medium">
                {{ $sub->name_ar }}
            </a>
            @endforeach
        </div>
        @endif

        <!-- Products Grid -->
        @if($products->count() > 0)
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 sm:gap-6">
                @foreach($products as $product)
                    <x-product-card :product="$product" />
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-12">
                {{ $products->links() }}
            </div>
        @else
            <div class="text-center py-16 bg-white rounded-2xl border border-[#EDE6DA] p-8">
                <div class="w-16 h-16 bg-[#FAF8F4] rounded-full flex items-center justify-center mx-auto mb-4 text-[#C5A059]">
                    <x-icon name="search" class="w-8 h-8" />
                </div>
                <h3 class="text-base font-bold text-[#242424] mb-1">لم يتم العثور على أي منتجات</h3>
                <p class="text-xs text-[#777777] mb-6">جرب تغيير كلمات البحث أو تصفح كافة التصنيفات</p>
                <a href="{{ route('products.index') }}" class="bg-[#C5A059] text-white text-xs font-bold px-6 py-2.5 rounded-xl transition hover:bg-[#AF8B44]">
                    عرض جميع المنتجات
                </a>
            </div>
        @endif

    </div>
</x-app-layout>
