@php
    $drawerCategories = \App\Models\Category::parents()->with(['children' => function($q) {
        $q->where('is_active', true)->orderBy('display_order');
    }])->get();
    $cr = \App\Models\StoreSetting::get('commercial_registry', '7055435114');
    $safety = \App\Models\StoreSetting::get('safety_certificate', '101565890');
    $whatsapp = \App\Models\StoreSetting::get('whatsapp_number', '966580760890');
@endphp

<div x-data
     x-show="$store.mobileMenu.isOpen"
     x-cloak
     @keydown.escape.window="$store.mobileMenu.close()"
     class="fixed inset-0 z-50 overflow-hidden lg:hidden"
     role="dialog"
     aria-modal="true"
     aria-label="القائمة الرئيسية">
    
    <!-- Backdrop Overlay -->
    <div x-show="$store.mobileMenu.isOpen"
         x-transition:enter="transition-opacity ease-linear duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-linear duration-300"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click="$store.mobileMenu.close()"
         class="fixed inset-0 bg-black/60 backdrop-blur-xs"></div>

    <!-- Sliding Panel from Right (RTL-native) -->
    <div class="fixed inset-y-0 right-0 max-w-full flex z-10">
        <div x-show="$store.mobileMenu.isOpen"
             x-transition:enter="transition ease-in-out duration-300 transform"
             x-transition:enter-start="translate-x-full"
             x-transition:enter-end="translate-x-0"
             x-transition:leave="transition ease-in-out duration-300 transform"
             x-transition:leave-start="translate-x-0"
             x-transition:leave-end="translate-x-full"
             class="w-screen max-w-xs sm:max-w-sm bg-white shadow-2xl flex flex-col justify-between overflow-y-auto">
            
            <div class="p-5">
                <!-- Header with Logo and Close Button -->
                <div class="flex items-center justify-between pb-4 border-b border-[#E2E8F0]">
                    <a href="{{ route('home') }}" @click="$store.mobileMenu.close()" class="flex items-center">
                        <img src="{{ asset('images/logo.png') }}" 
                             alt="متجر زاد نيكس - ZADNEX STORE" 
                             class="h-10 w-auto object-contain">
                    </a>
                    <button @click="$store.mobileMenu.close()" 
                            type="button" 
                            class="p-2 text-[#64748B] hover:text-[#0F172A] hover:bg-[#F8FAFC] rounded-full transition focus:outline-none" 
                            aria-label="إغلاق القائمة">
                        <x-icon name="x" class="w-6 h-6" />
                    </button>
                </div>

                <!-- Navigation Links -->
                <div class="mt-5 flex flex-col space-y-1.5">
                    <!-- Home -->
                    <a href="{{ route('home') }}" 
                       @click="$store.mobileMenu.close()" 
                       class="text-sm font-bold {{ request()->routeIs('home') ? 'text-[#0B2E75] bg-[#EFF6FF]' : 'text-[#0F172A] hover:text-[#0B2E75] hover:bg-[#F8FAFC]' }} py-2.5 px-3 rounded-xl flex items-center justify-between transition">
                        <span class="flex items-center gap-2.5">
                            <x-icon name="home" class="w-4 h-4 text-[#0B2E75]" />
                            <span>الرئيسية</span>
                        </span>
                        <x-icon name="chevron-left" class="w-4 h-4 text-[#94A3B8]" />
                    </a>

                    <!-- About Us -->
                    <a href="{{ route('about') }}" 
                       @click="$store.mobileMenu.close()" 
                       class="text-sm font-bold {{ request()->routeIs('about') ? 'text-[#0B2E75] bg-[#EFF6FF]' : 'text-[#0F172A] hover:text-[#0B2E75] hover:bg-[#F8FAFC]' }} py-2.5 px-3 rounded-xl flex items-center justify-between transition">
                        <span class="flex items-center gap-2.5">
                            <x-icon name="award" class="w-4 h-4 text-[#0B2E75]" />
                            <span>من نحن والشهادات</span>
                        </span>
                        <x-icon name="chevron-left" class="w-4 h-4 text-[#94A3B8]" />
                    </a>

                    <!-- Products Direct Link -->
                    <a href="{{ route('products.index') }}" 
                       @click="$store.mobileMenu.close()" 
                       class="text-sm font-bold {{ request()->routeIs('products.*') ? 'text-[#0B2E75] bg-[#EFF6FF]' : 'text-[#0F172A] hover:text-[#0B2E75] hover:bg-[#F8FAFC]' }} py-2.5 px-3 rounded-xl flex items-center justify-between transition">
                        <span class="flex items-center gap-2.5">
                            <x-icon name="grid" class="w-4 h-4 text-[#0B2E75]" />
                            <span>جميع المنتجات والسلع</span>
                        </span>
                        <x-icon name="chevron-left" class="w-4 h-4 text-[#94A3B8]" />
                    </a>

                    <!-- Contact -->
                    <a href="{{ route('contact') }}" 
                       @click="$store.mobileMenu.close()" 
                       class="text-sm font-bold {{ request()->routeIs('contact') ? 'text-[#0B2E75] bg-[#EFF6FF]' : 'text-[#0F172A] hover:text-[#0B2E75] hover:bg-[#F8FAFC]' }} py-2.5 px-3 rounded-xl flex items-center justify-between transition">
                        <span class="flex items-center gap-2.5">
                            <x-icon name="phone" class="w-4 h-4 text-[#0B2E75]" />
                            <span>اتصل بنا وموقعنا</span>
                        </span>
                        <x-icon name="chevron-left" class="w-4 h-4 text-[#94A3B8]" />
                    </a>

                    <!-- Direct Links to Departments on Mobile -->
                    <div class="pt-3 space-y-1">
                        <span class="text-[10px] font-bold text-[#64748B] uppercase tracking-wider px-2 block mb-1">أقسام المتجر</span>
                        @foreach($drawerCategories as $parent)
                        <div x-data="{ openCategory: false }" class="border-b border-gray-100 last:border-0 pb-1">
                            <div class="flex items-center justify-between py-2 px-2 hover:bg-[#F8FAFC] rounded-xl transition">
                                <a href="{{ route('category.show', $parent->slug) }}" @click="$store.mobileMenu.close()" class="text-sm font-bold text-[#0F172A] hover:text-[#0B2E75] transition">
                                    {{ $parent->name_ar }}
                                </a>
                                @if($parent->children->isNotEmpty())
                                <button type="button" @click="openCategory = !openCategory" class="p-1 text-[#94A3B8] hover:text-[#0B2E75] transition" aria-label="عرض الأقسام الفرعية">
                                    <span class="inline-block transition-transform duration-200" :class="openCategory ? 'rotate-180 text-[#0B2E75]' : ''">
                                        <x-icon name="chevron-down" class="w-3.5 h-3.5" />
                                    </span>
                                </button>
                                @endif
                            </div>

                            @if($parent->children->isNotEmpty())
                            <div x-show="openCategory" x-collapse class="pr-3 pl-1 py-1 space-y-1 bg-[#EFF6FF]/50 rounded-xl mb-1">
                                @foreach($parent->children as $sub)
                                <a href="{{ route('category.show', $sub->slug) }}" @click="$store.mobileMenu.close()" class="block text-xs text-[#334155] hover:text-[#0B2E75] py-1.5 px-2 rounded-lg transition font-medium">
                                    {{ $sub->name_ar }}
                                </a>
                                @endforeach
                            </div>
                            @endif
                        </div>
                        @endforeach
                    </div>

                    <!-- User / Auth Actions -->
                    @auth
                        @if(auth()->user()->isAdmin())
                        <div class="pt-3 border-t border-[#E2E8F0] space-y-2">
                            <span class="text-[10px] font-bold text-[#0B2E75] uppercase tracking-wider block">إدارة المتجر</span>
                            <a href="{{ route('admin.dashboard') }}" @click="$store.mobileMenu.close()" class="flex items-center justify-between bg-[#0B2E75] text-white text-xs font-bold py-2.5 px-3 rounded-xl transition hover:bg-[#071E50]">
                                <span class="flex items-center gap-2">
                                    <x-icon name="layout-dashboard" class="w-4 h-4 text-[#FBB404]" />
                                    <span>لوحة تحكم المتجر</span>
                                </span>
                                <x-icon name="arrow-left" class="w-3.5 h-3.5" />
                            </a>
                        </div>
                        @else
                        <div class="pt-3 border-t border-[#E2E8F0] space-y-2">
                            <span class="text-[10px] font-bold text-[#0B2E75] uppercase tracking-wider block">حسابي</span>
                            <a href="{{ route('customer.dashboard') }}" @click="$store.mobileMenu.close()" class="flex items-center justify-between bg-[#0B2E75] text-white text-xs font-bold py-2.5 px-3 rounded-xl transition">
                                <span class="flex items-center gap-2">
                                    <x-icon name="user" class="w-4 h-4 text-[#FBB404]" />
                                    <span>لوحة تحكم حسابي</span>
                                </span>
                                <x-icon name="arrow-left" class="w-3.5 h-3.5" />
                            </a>
                        </div>
                        @endif
                    @else
                        <div class="pt-3 border-t border-[#E2E8F0] space-y-2">
                            <a href="{{ route('login') }}" @click="$store.mobileMenu.close()" class="flex items-center justify-center gap-2 bg-[#0B2E75] text-white text-xs font-bold py-2.5 px-3 rounded-xl transition hover:bg-[#071E50]">
                                <x-icon name="log-in" class="w-4 h-4 text-[#FBB404]" />
                                <span>تسجيل الدخول</span>
                            </a>
                            <a href="{{ route('register') }}" @click="$store.mobileMenu.close()" class="flex items-center justify-center gap-2 bg-[#F8FAFC] text-[#0B2E75] border border-[#E2E8F0] text-xs font-bold py-2 px-3 rounded-xl transition hover:bg-[#E2E8F0]">
                                <span>إنشاء حساب جديد</span>
                            </a>
                        </div>
                    @endauth
                </div>
            </div>

            <!-- Drawer Footer -->
            <div class="p-5 border-t border-[#E2E8F0] bg-[#F8FAFC] text-xs text-[#64748B] space-y-2.5">
                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $whatsapp) }}" target="_blank" class="flex items-center gap-2 text-[#25D366] font-bold hover:underline">
                    <x-icon name="whatsapp" class="w-4 h-4" />
                    <span>تواصل معنا عبر واتساب (0580760890)</span>
                </a>

                <div class="pt-2 border-t border-[#E2E8F0] space-y-1 text-[11px]">
                    <p class="flex items-center justify-between">
                        <span>السجل التجاري:</span>
                        <strong class="font-mono text-[#0B2E75]">{{ $cr }}</strong>
                    </p>
                    <p class="flex items-center justify-between">
                        <span>شهادة سلامة:</span>
                        <strong class="font-mono text-[#0B2E75]">{{ $safety }}</strong>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
