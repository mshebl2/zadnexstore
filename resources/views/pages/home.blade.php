<x-app-layout>
    <x-slot:title>متجر زاد نيكس (ZADNEX) | تجارة المواد الغذائية والمشروبات والمنظفات والكماليات بمكة</x-slot:title>
    <x-slot:description>متجر زاد نيكس في مكة المكرمة — خيارك الأول لأجود المواد الغذائية التموينية، المشروبات، المنظفات ومستلزمات البيت بأفضل الأسعار وسلامة غذائية معتمدة 100%.</x-slot:description>

    <div class="space-y-10 sm:space-y-16 pb-16">

        <!-- =========================================================================
             SECTION: PROMOTIONAL BANNER SUITE
             ========================================================================= -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-4 sm:pt-6 space-y-4 sm:space-y-6">

            <!-- Hero Banners -->
            @if(($settings['homepage_section_hero_visible'] ?? '1') === '1' && $heroBanners->isNotEmpty())
            <section class="relative" 
                     x-data="{ 
                         active: 0, 
                         total: {{ $heroBanners->count() }},
                         timer: null,
                         next() { this.active = (this.active + 1) % this.total; },
                         prev() { this.active = (this.active - 1 + this.total) % this.total; },
                         start() { if (this.total > 1) { this.timer = setInterval(() => this.next(), 6000); } },
                         stop() { if (this.timer) { clearInterval(this.timer); this.timer = null; } }
                     }"
                     x-init="start()"
                     @mouseenter="stop()"
                     @mouseleave="start()">

                <div class="relative rounded-2xl sm:rounded-3xl overflow-hidden shadow-sm border border-[#E2E8F0] bg-white">
                    @foreach($heroBanners as $index => $banner)
                    <div x-show="active === {{ $index }}"
                         x-transition:enter="transition-opacity duration-700 ease-out"
                         x-transition:enter-start="opacity-0"
                         x-transition:enter-end="opacity-100"
                         x-transition:leave="transition-opacity duration-500 ease-in absolute inset-0"
                         x-transition:leave-start="opacity-100"
                         x-transition:leave-end="opacity-0"
                         class="relative w-full">

                        <!-- Entire Banner Clickable Link -->
                        <a href="{{ $banner->button_url ?: route('products.index') }}" 
                           class="block relative w-full group overflow-hidden"
                           title="{{ $banner->title_ar }}">
                            
                            <picture class="block w-full">
                                @if($banner->mobile_image_url)
                                <source media="(max-width: 640px)" srcset="{{ $banner->mobile_image_url }}">
                                @endif
                                <img src="{{ $banner->image_url }}" 
                                     alt="{{ $banner->title_ar }}" 
                                     class="w-full h-auto min-h-[240px] sm:min-h-[360px] md:min-h-[440px] lg:min-h-[500px] max-h-[560px] object-cover object-center group-hover:scale-[1.015] transition-transform duration-700 ease-out">
                            </picture>

                            <!-- Gradient Protection & Promotional Overlay Content -->
                            <div class="absolute inset-0 bg-gradient-to-t from-[#071E50]/90 via-[#0B2E75]/40 to-transparent flex flex-col justify-end p-5 sm:p-8 lg:p-12 text-white">
                                <div class="max-w-2xl text-right space-y-2 sm:space-y-3">
                                    @if($banner->badge_ar)
                                    <span class="inline-flex items-center gap-1.5 bg-[#FBB404] text-[#071E50] text-[11px] sm:text-xs font-black px-3 py-1 rounded-full shadow-xs">
                                        {{ $banner->badge_ar }}
                                    </span>
                                    @endif

                                    <h2 class="text-xl sm:text-3xl lg:text-4xl font-extrabold text-white leading-tight drop-shadow-md">
                                        {{ $banner->title_ar }}
                                    </h2>

                                    @if($banner->subtitle_ar)
                                    <p class="text-xs sm:text-sm text-gray-100 line-clamp-2 max-w-xl leading-relaxed drop-shadow-xs">
                                        {{ $banner->subtitle_ar }}
                                    </p>
                                    @endif

                                    <!-- Bottom Row: Payment Badges on Left & CTA on Right -->
                                    <div class="pt-2 sm:pt-4 flex items-center justify-between gap-3">
                                        <!-- CTA Button -->
                                        <span class="bg-[#FBB404] hover:bg-[#E5A300] text-[#071E50] text-xs sm:text-sm font-black px-6 py-2.5 sm:py-3 rounded-xl transition duration-300 flex items-center gap-2 shadow-lg">
                                            <span>{{ $banner->button_text_ar ?: 'تسوق الآن' }}</span>
                                            <x-icon name="arrow-left" class="w-3.5 h-3.5" />
                                        </span>

                                        @if(\App\Models\StoreSetting::get('tamara_enabled', '0') === '1' || \App\Models\StoreSetting::get('tabby_enabled', '0') === '1')
                                        <!-- Sub-badge: Tabby & Tamara installment support indicator -->
                                        <div class="hidden sm:flex items-center gap-1.5 bg-black/40 backdrop-blur-md border border-white/10 px-3 py-1.5 rounded-xl">
                                            @if(\App\Models\StoreSetting::get('tabby_enabled', '0') === '1')
                                            <img src="{{ asset('images/payments/tabby.png') }}" alt="تابي" class="h-4 w-auto object-contain">
                                            @endif
                                            @if(\App\Models\StoreSetting::get('tamara_enabled', '0') === '1')
                                            <img src="{{ asset('images/payments/tamara.png') }}" alt="تمارا" class="h-4 w-auto object-contain">
                                            @endif
                                            <span class="text-[10px] text-gray-200">قسم فاتورتك بدون فوائد</span>
                                        </div>
                                        @else
                                        <!-- Trial Makkah & WhatsApp Badge -->
                                        <div class="hidden sm:flex items-center gap-2 bg-[#0B2E75]/80 backdrop-blur-md border border-[#FBB404]/30 px-3.5 py-1.5 rounded-xl text-white">
                                            <span class="w-2 h-2 rounded-full bg-emerald-400 animate-ping"></span>
                                            <span class="text-xs font-bold text-[#FBB404]">توصيل حصري بمكة المكرمة ⚡</span>
                                            <span class="text-[11px] text-gray-200">| الدفع عند الاستلام</span>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                    @endforeach

                    <!-- Slider Controls -->
                    @if($heroBanners->count() > 1)
                    <button @click="prev()" 
                            type="button" 
                            class="absolute top-1/2 -translate-y-1/2 right-3 sm:right-5 w-9 h-9 sm:w-11 sm:h-11 rounded-full bg-black/40 hover:bg-[#FBB404] hover:text-[#071E50] text-white flex items-center justify-center transition backdrop-blur-xs z-10"
                            aria-label="السابق">
                        <x-icon name="chevron-right" class="w-5 h-5" />
                    </button>
                    <button @click="next()" 
                            type="button" 
                            class="absolute top-1/2 -translate-y-1/2 left-3 sm:left-5 w-9 h-9 sm:w-11 sm:h-11 rounded-full bg-black/40 hover:bg-[#FBB404] hover:text-[#071E50] text-white flex items-center justify-center transition backdrop-blur-xs z-10"
                            aria-label="التالي">
                        <x-icon name="chevron-left" class="w-5 h-5" />
                    </button>
                    @endif
                </div>
            </section>
            @endif


            <!-- Secondary Banners (2 Banners in One Row) -->
            @if($secondaryBanners->isNotEmpty())
            <section class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
                @foreach($secondaryBanners as $banner)
                <div class="relative rounded-2xl sm:rounded-3xl overflow-hidden shadow-xs border border-[#E2E8F0] bg-white group hover:shadow-md transition-all duration-300">
                    <a href="{{ $banner->button_url ?: route('products.index') }}" 
                       class="block relative w-full h-full"
                       title="{{ $banner->title_ar }}">
                        
                        <picture class="block w-full">
                            @if($banner->mobile_image_url)
                            <source media="(max-width: 640px)" srcset="{{ $banner->mobile_image_url }}">
                            @endif
                            <img src="{{ $banner->image_url }}" 
                                 alt="{{ $banner->title_ar }}" 
                                 class="w-full h-56 sm:h-64 md:h-72 lg:h-80 object-cover object-center group-hover:scale-[1.02] transition-transform duration-700 ease-out">
                        </picture>

                        <!-- Content Overlay -->
                        <div class="absolute inset-0 bg-gradient-to-t from-[#071E50]/90 via-[#0B2E75]/30 to-transparent p-5 sm:p-7 flex flex-col justify-end text-white">
                            <div class="text-right space-y-2">
                                @if($banner->badge_ar)
                                <span class="inline-block bg-[#FBB404] text-[#071E50] text-[10px] font-black px-2.5 py-0.5 rounded-full shadow-xs">
                                    {{ $banner->badge_ar }}
                                </span>
                                @endif

                                <h3 class="text-lg sm:text-xl font-bold text-white drop-shadow-md">
                                    {{ $banner->title_ar }}
                                </h3>

                                @if($banner->subtitle_ar)
                                <p class="text-[11px] sm:text-xs text-gray-200 line-clamp-1">
                                    {{ $banner->subtitle_ar }}
                                </p>
                                @endif

                                <div class="pt-2 flex items-center justify-between">
                                    <span class="inline-flex items-center gap-1.5 bg-[#0B2E75] group-hover:bg-[#FBB404] group-hover:text-[#071E50] text-white text-xs font-bold px-4 py-2 rounded-xl transition duration-200 shadow-sm border border-white/20">
                                        <span>{{ $banner->button_text_ar ?: 'تسوق الآن' }}</span>
                                        <x-icon name="arrow-left" class="w-3 h-3" />
                                    </span>

                                    @if(\App\Models\StoreSetting::get('tamara_enabled', '0') === '1' || \App\Models\StoreSetting::get('tabby_enabled', '0') === '1')
                                    <div class="flex items-center gap-1 bg-black/40 px-2 py-1 rounded-md">
                                        @if(\App\Models\StoreSetting::get('tabby_enabled', '0') === '1')
                                        <img src="{{ asset('images/payments/tabby.png') }}" alt="تابي" class="h-3 w-auto object-contain">
                                        @endif
                                        @if(\App\Models\StoreSetting::get('tamara_enabled', '0') === '1')
                                        <img src="{{ asset('images/payments/tamara.png') }}" alt="تمارا" class="h-3 w-auto object-contain">
                                        @endif
                                    </div>
                                    @else
                                    <div class="flex items-center gap-1 bg-[#0B2E75]/80 px-2.5 py-1 rounded-md border border-[#FBB404]/30">
                                        <span class="text-[10px] text-[#FBB404] font-bold">توصيل مكة ⚡</span>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                @endforeach
            </section>
            @endif

        </div>


        <!-- =========================================================================
             SECTION: PROMOTIONAL OFFERS GRID (عروض زاد نيكس التوفيرية - 3 Banners)
             ========================================================================= -->
        @if(($settings['homepage_section_offers_visible'] ?? '1') === '1' && $offerBanners->isNotEmpty())
        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="relative flex items-center justify-center my-6 sm:my-8">
                <div class="border-t border-[#E2E8F0] w-full absolute inset-x-0"></div>
                <div class="relative bg-[#F8FAFC] px-6 text-center">
                    <span class="text-xs font-bold text-[#0B2E75] tracking-widest uppercase block">باقات التوفير</span>
                    <h2 class="text-xl sm:text-2xl font-extrabold text-[#0F172A] tracking-tight mt-0.5">
                        {{ $settings['homepage_offers_title'] ?? 'عروض زاد نيكس الأسبوعية' }}
                    </h2>
                </div>
            </div>

            <!-- Three Promotional Banners in One Row -->
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 sm:gap-6">
                @foreach($offerBanners as $banner)
                <div class="relative rounded-2xl sm:rounded-3xl overflow-hidden shadow-xs border border-[#E2E8F0] bg-white group hover:shadow-md transition-all duration-300">
                    <a href="{{ $banner->button_url ?: route('products.index') }}" 
                       class="block relative w-full h-full"
                       title="{{ $banner->title_ar }}">
                        
                        <picture class="block w-full">
                            @if($banner->mobile_image_url)
                            <source media="(max-width: 640px)" srcset="{{ $banner->mobile_image_url }}">
                            @endif
                            <img src="{{ $banner->image_url }}" 
                                 alt="{{ $banner->title_ar }}" 
                                 class="w-full h-72 sm:h-80 md:h-96 object-cover object-center group-hover:scale-[1.03] transition-transform duration-700 ease-out">
                        </picture>

                        <!-- Discount Details Overlay -->
                        <div class="absolute inset-0 bg-gradient-to-t from-[#071E50]/95 via-[#0B2E75]/30 to-black/30 p-5 sm:p-6 flex flex-col justify-between text-white">
                            <!-- Top Tag / Badge -->
                            <div class="flex items-center justify-between">
                                @if($banner->badge_ar)
                                <span class="bg-[#FBB404] text-[#071E50] font-black text-xs px-3 py-1 rounded-full shadow-md">
                                    {{ $banner->badge_ar }}
                                </span>
                                @else
                                <span></span>
                                @endif

                                <span class="text-[10px] text-gray-200 bg-black/40 px-2.5 py-0.5 rounded-full border border-white/10">
                                    عرض توفير خاص
                                </span>
                            </div>

                            <!-- Bottom Content & Action -->
                            <div class="text-right space-y-2">
                                <h3 class="text-base sm:text-lg font-bold text-white drop-shadow-md leading-snug">
                                    {{ $banner->title_ar }}
                                </h3>

                                @if($banner->subtitle_ar)
                                <p class="text-xs sm:text-sm font-bold text-[#FBB404]">
                                    {{ $banner->subtitle_ar }}
                                </p>
                                @endif

                                <div class="pt-2 flex items-center justify-between">
                                    <span class="inline-flex items-center gap-1.5 bg-[#0B2E75] group-hover:bg-[#FBB404] group-hover:text-[#071E50] border border-white/20 text-white text-xs font-bold px-4 py-2 rounded-xl transition duration-200 shadow-sm">
                                        <span>{{ $banner->button_text_ar ?: 'تسوق العرض' }}</span>
                                        <x-icon name="arrow-left" class="w-3.5 h-3.5" />
                                    </span>

                                    @if(\App\Models\StoreSetting::get('tamara_enabled', '0') === '1' || \App\Models\StoreSetting::get('tabby_enabled', '0') === '1')
                                    <div class="flex items-center gap-1 bg-black/40 px-2 py-1 rounded-md">
                                        @if(\App\Models\StoreSetting::get('tabby_enabled', '0') === '1')
                                        <img src="{{ asset('images/payments/tabby.png') }}" alt="تابي" class="h-3 w-auto object-contain">
                                        @endif
                                        @if(\App\Models\StoreSetting::get('tamara_enabled', '0') === '1')
                                        <img src="{{ asset('images/payments/tamara.png') }}" alt="تمارا" class="h-3 w-auto object-contain">
                                        @endif
                                    </div>
                                    @else
                                    <div class="flex items-center gap-1 bg-[#0B2E75]/80 px-2.5 py-1 rounded-md border border-[#FBB404]/30">
                                        <span class="text-[10px] text-[#FBB404] font-bold">توصيل مكة ⚡</span>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                @endforeach
            </div>
        </section>
        @endif


        <!-- =========================================================================
             SECTION: 4 MAIN STORE CATEGORIES
             ========================================================================= -->
        @if(($settings['homepage_section_categories_visible'] ?? '1') === '1')
        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-xl mx-auto mb-8 sm:mb-12">
                <span class="text-xs font-bold text-[#0B2E75] tracking-widest uppercase">أقسام المتجر الرئيسية</span>
                <h2 class="text-2xl sm:text-3xl font-extrabold text-[#0F172A] tracking-tight mt-1">تسوق حسب القسم</h2>
                <p class="text-xs sm:text-sm text-[#64748B] mt-1.5 font-medium">مواد غذائية، مشروبات، منظفات، وكماليات منزلية بأعلى جودة</p>
                <div class="w-12 h-1 bg-[#FBB404] mx-auto mt-3 rounded-full"></div>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 text-center">
                @foreach($categories as $cat)
                <a href="{{ route('category.show', $cat->slug) }}" 
                   class="group bg-white rounded-2xl p-5 border border-[#E2E8F0] hover:border-[#0B2E75] hover:shadow-lg transition-all duration-300 flex flex-col items-center">
                    <div class="w-20 h-20 sm:w-24 sm:h-24 rounded-2xl overflow-hidden bg-[#EFF6FF] mb-4 border border-[#E2E8F0] group-hover:scale-105 transition-transform duration-300 flex items-center justify-center">
                        <img src="{{ $cat->image }}" alt="{{ $cat->name_ar }}" class="w-full h-full object-cover">
                    </div>
                    <h3 class="text-sm sm:text-base font-bold text-[#0F172A] group-hover:text-[#0B2E75] transition truncate w-full">
                        {{ $cat->name_ar }}
                    </h3>
                    <p class="text-[11px] text-[#64748B] mt-1 line-clamp-2 px-2 hidden sm:block">
                        {{ $cat->description_ar }}
                    </p>
                    <span class="inline-block mt-3 text-[11px] font-bold text-[#0B2E75] bg-[#EFF6FF] group-hover:bg-[#FBB404] group-hover:text-[#071E50] px-3 py-1 rounded-full transition">
                        تصفح القسم &larr;
                    </span>
                </a>
                @endforeach
            </div>
        </section>
        @endif


        <!-- =========================================================================
             SECTION: BEST SELLERS (الأكثر طلباً وتوفيراً)
             ========================================================================= -->
        @if(($settings['homepage_section_bestsellers_visible'] ?? '1') === '1')
        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-end justify-between mb-8 pb-4 border-b border-[#E2E8F0]">
                <div class="text-right">
                    <span class="text-xs font-bold text-[#0B2E75] tracking-widest uppercase">الأكثر طلباً</span>
                    <h2 class="text-xl sm:text-2xl font-extrabold text-[#0F172A] mt-1">السلع والمنتجات الأكثر طلباً في زاد نيكس</h2>
                </div>
                <a href="{{ route('products.index') }}" class="text-xs font-bold text-[#0B2E75] hover:text-[#071E50] flex items-center gap-1 transition">
                    <span>عرض جميع المنتجات</span>
                    <x-icon name="arrow-left" class="w-3.5 h-3.5" />
                </a>
            </div>

            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
                @foreach($featuredProducts as $product)
                    <x-product-card :product="$product" />
                @endforeach
            </div>
        </section>
        @endif


        <!-- =========================================================================
             SHOPPING BENEFITS TRUST BAR
             ========================================================================= -->
        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-gradient-to-r from-[#0B2E75] to-[#071E50] rounded-2xl sm:rounded-3xl p-6 sm:p-8 text-white shadow-md border-b-4 border-[#FBB404]">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-6 text-right">
                    <div class="flex items-center gap-3.5">
                        <div class="w-12 h-12 rounded-xl bg-white/10 border border-white/15 flex items-center justify-center text-[#FBB404] flex-shrink-0">
                            <x-icon name="truck" class="w-6 h-6" />
                        </div>
                        <div>
                            <h4 class="text-xs sm:text-sm font-bold text-white">توصيل سريع ومبرد</h4>
                            <p class="text-[11px] text-gray-200">في مكة وكافة مدن المملكة</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-3.5">
                        <div class="w-12 h-12 rounded-xl bg-white/10 border border-white/15 flex items-center justify-center text-[#FBB404] flex-shrink-0">
                            <x-icon name="shield-check" class="w-6 h-6" />
                        </div>
                        <div>
                            <h4 class="text-xs sm:text-sm font-bold text-white">سلامة غذائية 100%</h4>
                            <p class="text-[11px] text-gray-200">شهادة سلامة معتمدة 101565890</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-3.5">
                        <div class="w-12 h-12 rounded-xl bg-white/10 border border-white/15 flex items-center justify-center text-[#FBB404] flex-shrink-0">
                            <x-icon name="award" class="w-6 h-6" />
                        </div>
                        <div>
                            <h4 class="text-xs sm:text-sm font-bold text-white">سجل تجاري نشط</h4>
                            <p class="text-[11px] text-gray-200">رقم موحد: 7055435114</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-3.5">
                        <div class="w-12 h-12 rounded-xl bg-white/10 border border-white/15 flex items-center justify-center text-[#FBB404] flex-shrink-0">
                            <x-icon name="credit-card" class="w-6 h-6" />
                        </div>
                        <div>
                            <h4 class="text-xs sm:text-sm font-bold text-white">دفع آمن بالتقسيط</h4>
                            <p class="text-[11px] text-gray-200">مدى، أبل باي، تابي، وتمارا</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>


        <!-- =========================================================================
             SECTION: NEW ARRIVALS (وصل حديثاً إلى مستودعاتنا)
             ========================================================================= -->
        @if(($settings['homepage_section_new_arrivals_visible'] ?? '1') === '1')
        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-end justify-between mb-8 pb-4 border-b border-[#E2E8F0]">
                <div class="text-right">
                    <span class="text-xs font-bold text-[#0B2E75] tracking-widest uppercase">جديد التموين</span>
                    <h2 class="text-xl sm:text-2xl font-extrabold text-[#0F172A] mt-1">وصل حديثاً لمخازن زاد نيكس</h2>
                </div>
                <a href="{{ route('products.index') }}" class="text-xs font-bold text-[#0B2E75] hover:text-[#071E50] flex items-center gap-1 transition">
                    <span>عرض الكل</span>
                    <x-icon name="arrow-left" class="w-3.5 h-3.5" />
                </a>
            </div>

            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
                @foreach($newArrivals as $product)
                    <x-product-card :product="$product" />
                @endforeach
            </div>
        </section>
        @endif


        <!-- =========================================================================
             SECTION: ABOUT ZAD NEX & OFFICIAL COMMITMENT
             ========================================================================= -->
        @if(($settings['homepage_section_story_visible'] ?? '1') === '1')
        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-3xl border border-[#E2E8F0] p-8 sm:p-12 text-center space-y-6 shadow-xs">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-[#EFF6FF] border border-[#BFDBFE] text-[#0B2E75] shadow-xs">
                    <x-icon name="award" class="w-8 h-8 text-[#0B2E75]" />
                </div>
                
                <span class="text-xs font-bold text-[#0B2E75] tracking-widest uppercase block">متجر زاد نيكس • جودة وتوفير وسلامة موثقة</span>

                <h2 class="text-2xl sm:text-3xl text-[#0F172A] font-extrabold tracking-wide">
                    شريكك الموثوق لتسوق المواد الغذائية والمستلزمات المنزلية
                </h2>
                
                <p class="text-xs sm:text-sm text-gray-600 leading-relaxed max-w-3xl mx-auto font-medium">
                    نحن في <strong class="text-[#0B2E75]">متجر زاد نيكس (ZADNEX STORE)</strong> بمكة المكرمة نضع سلامة غذائكم ورضاكم في قمة أولوياتنا. نوفر تشكيلة واسعة من المواد الغذائية الأساسية، المشروبات والعصائر، المنظفات عالية الجودة، ومستلزمات البيت اليومية مع التزام تام باشتراطات السلامة واللوائح المعتمدة بالمملكة.
                </p>

                <div class="pt-2 flex items-center justify-center gap-4 flex-wrap">
                    <a href="{{ route('about') }}" class="bg-[#0B2E75] hover:bg-[#071E50] text-white text-xs font-bold py-3 px-6 rounded-xl transition shadow-sm flex items-center gap-2">
                        <span>تعرف على تفاصيل من نحن والتراخيص</span>
                        <x-icon name="arrow-left" class="w-3.5 h-3.5 text-[#FBB404]" />
                    </a>
                    <a href="{{ route('contact') }}" class="bg-[#F8FAFC] hover:bg-[#E2E8F0] text-[#0F172A] border border-[#E2E8F0] text-xs font-bold py-3 px-6 rounded-xl transition flex items-center gap-2">
                        <span>زيارة موقعنا بمكة المكرمة</span>
                        <x-icon name="map-pin" class="w-3.5 h-3.5 text-[#0B2E75]" />
                    </a>
                </div>

                <div class="pt-6 border-t border-[#E2E8F0] flex items-center justify-center gap-6 sm:gap-12 text-xs text-gray-500">
                    <div class="flex flex-col items-center">
                        <span class="text-lg sm:text-xl font-bold text-[#0B2E75]">7055435114</span>
                        <span class="text-[11px]">سجل تجاري نشط</span>
                    </div>
                    <div class="h-8 w-px bg-[#E2E8F0]"></div>
                    <div class="flex flex-col items-center">
                        <span class="text-lg sm:text-xl font-bold text-[#0B2E75]">101565890</span>
                        <span class="text-[11px]">شهادة سلامة معتمدة</span>
                    </div>
                    <div class="h-8 w-px bg-[#E2E8F0]"></div>
                    <div class="flex flex-col items-center">
                        <span class="text-lg sm:text-xl font-bold text-[#0B2E75]">أم الجود - مكة</span>
                        <span class="text-[11px]">الموقع والمعرض</span>
                    </div>
                </div>
            </div>
        </section>
        @endif

    </div>

</x-app-layout>
