@php
    $parentCategories = \App\Models\Category::parents()->with(['children' => function($q) {
        $q->where('is_active', true)->orderBy('display_order');
    }])->get();
@endphp

<header x-data="{ 
    isScrolled: false, 
    searchOpen: false, 
    searchQuery: ''
}" 
@scroll.window="isScrolled = (window.pageYOffset > 20)"
class="sticky top-0 z-40 transition-all duration-300"
:class="isScrolled ? 'bg-white/95 backdrop-blur-md shadow-sm border-b border-[#E2E8F0]' : 'bg-[#F8FAFC] border-b border-[#E2E8F0]'">

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-20">
            
            <!-- Mobile Menu Trigger -->
            <div class="flex items-center lg:hidden">
                <button @click="$store.mobileMenu.open()" type="button" class="p-2 text-[#0F172A] hover:text-[#0B2E75] transition focus:outline-none" aria-label="القائمة">
                    <x-icon name="menu" class="w-6 h-6" />
                </button>
            </div>

            <!-- Brand Logo -->
            <div class="flex items-center">
                <a href="{{ route('home') }}" class="group flex items-center gap-2" title="متجر زاد نيكس - ZADNEX STORE">
                    <img src="{{ asset('images/logo.png') }}" 
                         alt="متجر زاد نيكس - ZADNEX STORE" 
                         class="h-12 sm:h-14 w-auto object-contain transition-transform duration-300 group-hover:scale-105">
                </a>
            </div>

            <!-- Desktop Nav Links with Dropdowns -->
            <nav class="hidden lg:flex items-center gap-5 xl:gap-7">
                <a href="{{ route('home') }}" class="text-xs xl:text-sm font-bold transition py-2 whitespace-nowrap relative {{ request()->routeIs('home') ? 'text-[#0B2E75]' : 'text-[#0F172A] hover:text-[#0B2E75]' }}">
                    الرئيسية
                    @if(request()->routeIs('home'))
                        <span class="absolute bottom-0 right-0 left-0 h-0.5 bg-[#FBB404] rounded-full"></span>
                    @endif
                </a>

                <!-- About Us Link -->
                <a href="{{ route('about') }}" class="text-xs xl:text-sm font-bold transition py-2 whitespace-nowrap relative {{ request()->routeIs('about') ? 'text-[#0B2E75]' : 'text-[#0F172A] hover:text-[#0B2E75]' }}">
                    <span>من نحن</span>
                    @if(request()->routeIs('about'))
                        <span class="absolute bottom-0 right-0 left-0 h-0.5 bg-[#FBB404] rounded-full"></span>
                    @endif
                </a>

                <!-- Desktop Products Direct Link -->
                <a href="{{ route('products.index') }}" class="text-xs xl:text-sm font-bold transition py-2 whitespace-nowrap relative {{ request()->routeIs('products.index') && !request('category') ? 'text-[#0B2E75]' : 'text-[#0F172A] hover:text-[#0B2E75]' }}">
                    <span>جميع المنتجات</span>
                    @if(request()->routeIs('products.index') && !request('category'))
                        <span class="absolute bottom-0 right-0 left-0 h-0.5 bg-[#FBB404] rounded-full"></span>
                    @endif
                </a>

                <!-- Individual Parent Category Tabs with Sub-Dropdowns -->
                @foreach($parentCategories as $parent)
                <div class="relative group" x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false">
                    <a href="{{ route('category.show', $parent->slug) }}" class="text-xs xl:text-sm font-bold transition py-2 whitespace-nowrap flex items-center gap-1 {{ request()->is('category/' . $parent->slug) ? 'text-[#0B2E75]' : 'text-[#0F172A] hover:text-[#0B2E75]' }}">
                        <span>{{ $parent->name_ar }}</span>
                        @if($parent->children->isNotEmpty())
                        <x-icon name="chevron-down" class="w-3 h-3 text-[#94A3B8] group-hover:text-[#0B2E75] transition-transform duration-200" ::class="open ? 'rotate-180' : ''" />
                        @endif
                        @if(request()->is('category/' . $parent->slug))
                            <span class="absolute bottom-0 right-0 left-0 h-0.5 bg-[#FBB404] rounded-full"></span>
                        @endif
                    </a>

                    @if($parent->children->isNotEmpty())
                    <!-- Subcategory Dropdown Menu -->
                    <div x-show="open" 
                         x-transition:enter="transition ease-out duration-150"
                         x-transition:enter-start="opacity-0 translate-y-2"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-100"
                         x-transition:leave-start="opacity-100 translate-y-0"
                         x-transition:leave-end="opacity-0 translate-y-2"
                         class="absolute right-0 top-full pt-2 w-48 z-50 pointer-events-auto"
                         style="display: none;">
                        <div class="bg-white rounded-xl shadow-xl border border-[#E2E8F0] p-2 text-right space-y-1">
                            @foreach($parent->children as $sub)
                            <a href="{{ route('category.show', $sub->slug) }}" class="block px-3 py-2 text-xs text-[#0F172A] hover:bg-[#EFF6FF] hover:text-[#0B2E75] rounded-lg transition font-medium">
                                {{ $sub->name_ar }}
                            </a>
                            @endforeach
                            <div class="pt-1 border-t border-[#F1F5F9]">
                                <a href="{{ route('category.show', $parent->slug) }}" class="block px-3 py-1.5 text-[11px] font-bold text-[#0B2E75] hover:bg-[#EFF6FF] rounded-lg transition">
                                    تصفح قسم {{ $parent->name_ar }}
                                </a>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
                @endforeach

                <!-- Contact Us Link -->
                <a href="{{ route('contact') }}" class="text-xs xl:text-sm font-bold transition py-2 whitespace-nowrap relative {{ request()->routeIs('contact') ? 'text-[#0B2E75]' : 'text-[#0F172A] hover:text-[#0B2E75]' }}">
                    <span>اتصل بنا</span>
                    @if(request()->routeIs('contact'))
                        <span class="absolute bottom-0 right-0 left-0 h-0.5 bg-[#FBB404] rounded-full"></span>
                    @endif
                </a>
            </nav>

            <!-- Actions (Search, Account, Cart) -->
            <div class="flex items-center gap-2 sm:gap-3">
                <!-- Search Button -->
                <button @click="searchOpen = true; $nextTick(() => $refs.searchInput.focus())" type="button" class="p-2 text-[#0F172A] hover:text-[#0B2E75] transition rounded-full hover:bg-white" title="البحث">
                    <x-icon name="search" class="w-5 h-5" />
                </button>

                <!-- Account / Admin -->
                @auth
                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="p-2 text-[#0B2E75] hover:bg-white transition rounded-full flex items-center gap-1.5 text-xs font-semibold" title="لوحة التحكم">
                            <x-icon name="user" class="w-5 h-5" />
                            <span class="hidden md:inline">الإدارة</span>
                        </a>
                    @else
                        <a href="{{ route('customer.dashboard') }}" class="p-2 text-[#0F172A] hover:text-[#0B2E75] hover:bg-white transition rounded-full flex items-center gap-1.5" title="لوحة تحكم حسابي">
                            <x-icon name="user" class="w-5 h-5" />
                            <span class="hidden md:inline text-xs font-bold text-[#0B2E75]">حسابي</span>
                        </a>
                    @endif
                @else
                    <a href="{{ route('login') }}" class="p-2 text-[#0F172A] hover:text-[#0B2E75] hover:bg-white transition rounded-full" title="تسجيل الدخول">
                        <x-icon name="user" class="w-5 h-5" />
                    </a>
                @endauth

                <!-- Cart Button -->
                <button @click="$store.cart.open()" type="button" class="relative p-2 text-[#0F172A] hover:text-[#0B2E75] hover:bg-white transition rounded-full group" title="سلة التسوق">
                    <x-icon name="shopping-bag" class="w-5 h-5 group-hover:scale-105 transition-transform" />
                    <span x-show="$store.cart.count > 0" 
                          x-text="$store.cart.count" 
                          x-cloak
                          class="absolute -top-1 -left-1 bg-[#FBB404] text-[#071E50] text-[10px] font-black w-5 h-5 rounded-full flex items-center justify-center shadow-sm">
                    </span>
                </button>
            </div>
        </div>
    </div>

    <!-- Animated Search Overlay with Live Search Results -->
    <div x-show="searchOpen" 
         x-cloak
         x-data="{
             query: '',
             results: [],
             loading: false,
             performSearch() {
                 if (this.query.trim().length < 2) {
                     this.results = [];
                     return;
                 }
                 this.loading = true;
                 fetch('/api/search?q=' + encodeURIComponent(this.query.trim()))
                     .then(res => res.json())
                     .then(data => {
                         this.results = data.products || [];
                         this.loading = false;
                     })
                     .catch(() => {
                         this.loading = false;
                     });
             }
         }"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 -translate-y-4"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 -translate-y-4"
         @keydown.escape.window="searchOpen = false"
         class="absolute top-full left-0 right-0 bg-white border-b border-[#E2E8F0] shadow-2xl p-6 z-50">
        <div class="max-w-3xl mx-auto">
            <form action="{{ route('products.index') }}" method="GET" class="relative flex items-center">
                <input x-ref="searchInput" 
                       type="text" 
                       name="q" 
                       x-model="query"
                       @input.debounce.300ms="performSearch()"
                       placeholder="ابحث عن مواد غذائية، مشروبات، كراتين مياه، منظفات، كماليات..." 
                       style="padding-right: 64px !important; padding-left: 52px !important;"
                       class="w-full bg-[#F8FAFC] border border-[#E2E8F0] rounded-full py-3.5 text-sm text-[#0F172A] placeholder-[#94A3B8] focus:outline-none focus:border-[#0B2E75] focus:ring-1 focus:ring-[#0B2E75]">
                <x-icon name="search" class="w-5 h-5 text-[#0B2E75] absolute right-4 pointer-events-none" />
                <button @click="searchOpen = false" type="button" class="absolute left-4 p-1 text-[#64748B] hover:text-[#0F172A]" aria-label="إغلاق البحث">
                    <x-icon name="x" class="w-5 h-5" />
                </button>
            </form>

            <!-- Live Search Suggestions Dropdown -->
            <div x-show="query.trim().length >= 2" class="mt-4 pt-4 border-t border-[#E2E8F0]" x-cloak>
                <div x-show="loading" class="py-4 text-center text-xs text-[#64748B]">
                    جاري البحث في منتجات متجر زاد نيكس...
                </div>

                <div x-show="!loading && results.length > 0" class="space-y-2">
                    <p class="text-[11px] font-semibold text-[#64748B] mb-2">المنتجات المطابقة للبحث:</p>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                        <template x-for="item in results" :key="item.id">
                            <a :href="item.url" class="flex items-center gap-3 p-2 rounded-xl hover:bg-[#F8FAFC] transition border border-transparent hover:border-[#E2E8F0] group">
                                <img :src="item.image" :alt="item.name" class="w-12 h-12 object-cover rounded-lg bg-gray-50 flex-shrink-0">
                                <div class="flex-1 min-w-0">
                                    <h4 class="text-xs font-bold text-[#0F172A] group-hover:text-[#0B2E75] transition truncate" x-text="item.name"></h4>
                                    <p class="text-[10px] text-[#64748B]" x-text="item.category"></p>
                                    <p class="text-xs font-bold text-[#0B2E75]" x-text="item.price"></p>
                                </div>
                            </a>
                        </template>
                    </div>
                    <div class="pt-2 text-center">
                        <a :href="'{{ route('products.index') }}?q=' + encodeURIComponent(query.trim())" class="inline-block text-xs font-bold text-[#0B2E75] hover:underline">
                            عرض جميع النتائج المطابقة &larr;
                        </a>
                    </div>
                </div>

                <div x-show="!loading && results.length === 0" class="py-4 text-center text-xs text-[#64748B]">
                    لم يتم العثور على منتجات مطابقة لـ "<span x-text="query" class="font-bold text-[#0F172A]"></span>"
                </div>
            </div>

            <!-- Popular Searches -->
            <div x-show="query.trim().length < 2" class="mt-3 flex items-center gap-2 text-xs text-[#64748B] pr-2 flex-wrap">
                <span class="font-bold text-[#0F172A]">الأكثر بحثاً:</span>
                <a href="{{ route('products.index', ['q' => 'أرز']) }}" class="bg-[#F8FAFC] hover:bg-[#E2E8F0] text-[#0F172A] px-2.5 py-1 rounded-full transition text-[11px]">أرز بسمتي فاخر</a>
                <a href="{{ route('products.index', ['q' => 'مياه']) }}" class="bg-[#F8FAFC] hover:bg-[#E2E8F0] text-[#0F172A] px-2.5 py-1 rounded-full transition text-[11px]">كرتون مياه</a>
                <a href="{{ route('products.index', ['q' => 'زيت']) }}" class="bg-[#F8FAFC] hover:bg-[#E2E8F0] text-[#0F172A] px-2.5 py-1 rounded-full transition text-[11px]">زيت زيتون بكر</a>
                <a href="{{ route('products.index', ['q' => 'غسيل']) }}" class="bg-[#F8FAFC] hover:bg-[#E2E8F0] text-[#0F172A] px-2.5 py-1 rounded-full transition text-[11px]">مسحوق غسيل</a>
                <a href="{{ route('products.index', ['q' => 'مناديل']) }}" class="bg-[#F8FAFC] hover:bg-[#E2E8F0] text-[#0F172A] px-2.5 py-1 rounded-full transition text-[11px]">مناديل ورقية</a>
            </div>
        </div>
    </div>
</header>
