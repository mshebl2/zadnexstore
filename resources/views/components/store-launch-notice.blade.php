@php
    $isActive = \App\Models\StoreSetting::get('store_launch_notice_active', '1') === '1';
    $isPreview = request()->has('preview_notice');
    $shouldRender = $isActive || $isPreview;

    $noticeTitle = \App\Models\StoreSetting::get('store_launch_notice_title', 'مرحباً بكم في متجر زاد نيكس');
    $noticeText = \App\Models\StoreSetting::get('store_launch_notice_text', 'المتجر قيد التجهيز لاستقبال طلباتكم بما يليق بكم');
    $noticeBadge = \App\Models\StoreSetting::get('store_launch_notice_badge', 'تنبيه رسمي • قيد التجهيز');
    $noticeSubtitle = \App\Models\StoreSetting::get('store_launch_notice_subtitle', 'يسعدنا زيارتكم! نعمل حالياً بكل شغف على إعداد وتجهيز أفضل تشكيلة من المواد الغذائية والمنظفات ومستلزماتكم بأعلى معايير الجودة والسلامة المعتمدة. ترقبوا انطلاقتنا الرسمية قريباً، ونسعد بتصفحكم لأقسام المتجر أو التواصل المباشر معنا.');
    $displayMode = \App\Models\StoreSetting::get('store_launch_notice_display', 'both'); // 'both', 'modal', 'banner'
    $btnText = \App\Models\StoreSetting::get('store_launch_notice_button_text', 'تصفح المتجر الآن');
    $showWhatsapp = \App\Models\StoreSetting::get('store_launch_notice_show_whatsapp', '1') === '1';
    $whatsapp = \App\Models\StoreSetting::get('whatsapp_number', '966580760890');
    $cleanWhatsApp = preg_replace('/[^0-9]/', '', $whatsapp);
@endphp

@if($shouldRender)
<!-- Store Launch & Preparation Notice System -->
<div x-data="{
        showModal: false,
        showBanner: true,
        isPreview: {{ $isPreview ? 'true' : 'false' }},
        displayMode: '{{ $displayMode }}',
        init() {
            // Check Modal Visibility
            if (this.displayMode === 'modal' || this.displayMode === 'both') {
                const dismissed = sessionStorage.getItem('zadnex_launch_modal_dismissed');
                if (!dismissed || this.isPreview) {
                    // Micro-delay so initial DOM and splash settle smoothly
                    const hasSplash = !sessionStorage.getItem('zadnex_splash_shown') && !this.isPreview;
                    const delay = hasSplash ? 1600 : 350;
                    setTimeout(() => {
                        this.showModal = true;
                    }, delay);
                }
            }

            // Check Top Banner Visibility
            if (this.displayMode === 'banner' || this.displayMode === 'both') {
                if (sessionStorage.getItem('zadnex_launch_banner_hidden') && !this.isPreview) {
                    this.showBanner = false;
                }
            }
        },
        dismissModal() {
            this.showModal = false;
            sessionStorage.setItem('zadnex_launch_modal_dismissed', 'true');
        },
        dismissBanner() {
            this.showBanner = false;
            sessionStorage.setItem('zadnex_launch_banner_hidden', 'true');
        }
    }"
    x-cloak>

    <!-- 1. Prominent Top Sticky Banner (If display mode includes banner) -->
    @if($displayMode === 'banner' || $displayMode === 'both')
    <div x-show="showBanner"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="-translate-y-full opacity-0"
         x-transition:enter-end="translate-y-0 opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="translate-y-0 opacity-100"
         x-transition:leave-end="-translate-y-full opacity-0"
         class="bg-gradient-to-r from-amber-500 via-[#FBB404] to-amber-500 text-slate-950 py-2 sm:py-2.5 px-4 text-xs font-bold border-b border-amber-600/30 shadow-xs relative z-30 select-none">
        <div class="max-w-7xl mx-auto flex items-center justify-between gap-3">
            
            <div class="flex items-center gap-2.5 min-w-0">
                <!-- Pulse Dot -->
                <span class="relative flex h-2.5 w-2.5 flex-shrink-0">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-slate-900 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-slate-950"></span>
                </span>

                <div class="flex items-center gap-2 flex-wrap min-w-0">
                    <span class="bg-slate-950 text-[#FBB404] text-[10px] sm:text-[11px] font-black px-2 py-0.5 rounded-full uppercase tracking-wider flex-shrink-0">
                        {{ $noticeBadge }}
                    </span>
                    <span class="font-extrabold text-slate-950 text-xs sm:text-sm tracking-tight truncate">
                        {{ $noticeText }}
                    </span>
                </div>
            </div>

            <!-- Actions (Left side in RTL) -->
            <div class="flex items-center gap-2 flex-shrink-0">
                @if($showWhatsapp)
                <a href="https://wa.me/{{ $cleanWhatsApp }}?text={{ urlencode('السلام عليكم ورحمة الله، استفسار بخصوص المتجر وطلبات التجهيز') }}" 
                   target="_blank" 
                   class="hidden md:inline-flex items-center gap-1.5 bg-slate-950/90 hover:bg-slate-950 text-white text-[11px] font-bold px-3 py-1 rounded-xl shadow-2xs transition">
                    <x-icon name="whatsapp" class="w-3.5 h-3.5 text-[#25D366]" />
                    <span>تواصل مباشر</span>
                </a>
                @endif

                <button @click="dismissBanner()" 
                        type="button" 
                        class="p-1 rounded-lg text-slate-900 hover:text-black hover:bg-black/10 transition" 
                        title="إغلاق التنبيه">
                    <x-icon name="x" class="w-4 h-4" />
                </button>
            </div>

        </div>
    </div>
    @endif

    <!-- 2. Prominent Luxury Welcome / Launch Modal (Appears directly upon entry) -->
    @if($displayMode === 'modal' || $displayMode === 'both')
    <div x-show="showModal"
         class="fixed inset-0 z-[95] flex items-center justify-center p-4 sm:p-6 overflow-y-auto"
         role="dialog"
         aria-modal="true"
         aria-labelledby="launch-notice-title"
         @keydown.escape.window="dismissModal()">

        <!-- Backdrop with Glassmorphism -->
        <div x-show="showModal"
             x-transition:enter="transition-opacity ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             @click="dismissModal()"
             class="fixed inset-0 bg-slate-950/70 backdrop-blur-md"></div>

        <!-- Luxury Modal Card -->
        <div x-show="showModal"
             x-transition:enter="transition ease-out duration-400 transform"
             x-transition:enter-start="opacity-0 scale-95 translate-y-4"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
             x-transition:leave="transition ease-in duration-200 transform"
             x-transition:leave-start="opacity-100 scale-100 translate-y-0"
             x-transition:leave-end="opacity-0 scale-95 translate-y-4"
             class="relative w-full max-w-lg bg-white rounded-3xl shadow-2xl border border-amber-200/80 overflow-hidden text-right z-10 my-8">

            <!-- Decorative Top Gradient Accent Bar -->
            <div class="h-2 w-full bg-gradient-to-r from-[#0B2E75] via-[#FBB404] to-[#0B2E75]"></div>

            <!-- Modal Header Banner -->
            <div class="bg-gradient-to-br from-[#071E50] via-[#0B2E75] to-[#071E50] p-6 text-white relative overflow-hidden">
                <!-- Ambient Subtle Lighting -->
                <div class="absolute -top-16 -right-16 w-48 h-48 bg-[#FBB404]/15 rounded-full blur-2xl pointer-events-none"></div>
                <div class="absolute -bottom-16 -left-16 w-48 h-48 bg-blue-400/10 rounded-full blur-2xl pointer-events-none"></div>

                <!-- Close Button (Top-left in RTL) -->
                <button @click="dismissModal()"
                        type="button"
                        class="absolute top-4 left-4 w-9 h-9 rounded-2xl bg-white/10 hover:bg-white/20 text-white flex items-center justify-center transition-all duration-200 hover:rotate-90 cursor-pointer"
                        title="إغلاق">
                    <x-icon name="x" class="w-4.5 h-4.5 stroke-[2]" />
                </button>

                <div class="relative z-10 flex items-center gap-4">
                    <!-- Icon container with glowing ring -->
                    <div class="relative flex-shrink-0">
                        <div class="w-14 h-14 rounded-2xl bg-gradient-to-tr from-[#FBB404] to-amber-300 text-slate-950 flex items-center justify-center shadow-lg border border-amber-200">
                            <x-icon name="bell" class="w-7 h-7 stroke-[2]" />
                        </div>
                        <span class="absolute -bottom-1 -right-1 flex h-4 w-4">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-[#FBB404] opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-4 w-4 bg-[#FBB404] border-2 border-[#0B2E75]"></span>
                        </span>
                    </div>

                    <div>
                        <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-white/10 backdrop-blur-md border border-white/20 text-[#FBB404] text-[11px] font-bold">
                            <x-icon name="sparkles" class="w-3.5 h-3.5" />
                            <span>{{ $noticeBadge }}</span>
                        </div>
                        <h3 id="launch-notice-title" class="text-xl sm:text-2xl font-black text-white tracking-tight mt-1">
                            {{ $noticeTitle }}
                        </h3>
                    </div>
                </div>
            </div>

            <!-- Modal Body Content -->
            <div class="p-6 sm:p-7 space-y-5">

                <!-- Core Announcement Statement Box (High Prominence) -->
                <div class="relative bg-gradient-to-br from-amber-50 via-amber-100/40 to-yellow-50/70 border-2 border-amber-300/90 rounded-2xl p-5 shadow-xs">
                    <div class="flex items-start gap-3.5">
                        <div class="w-8 h-8 rounded-xl bg-amber-500/20 text-amber-800 flex items-center justify-center flex-shrink-0 mt-0.5">
                            <x-icon name="alert-triangle" class="w-4.5 h-4.5 text-amber-700" />
                        </div>
                        <div>
                            <span class="text-[10px] font-black uppercase tracking-wider text-amber-800 block mb-0.5">إشعار هام</span>
                            <p class="text-base sm:text-lg font-black text-slate-900 leading-snug tracking-tight">
                                {{ $noticeText }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Subtitle Description -->
                @if(!empty($noticeSubtitle))
                <p class="text-xs sm:text-sm text-slate-600 leading-relaxed font-medium">
                    {{ $noticeSubtitle }}
                </p>
                @endif

                <!-- Official Reassurance Badges -->
                <div class="grid grid-cols-3 gap-2 pt-2 border-t border-slate-100 text-center">
                    <div class="bg-slate-50 border border-slate-200/80 rounded-xl p-2.5">
                        <x-icon name="shield-check" class="w-4 h-4 mx-auto text-[#0B2E75] mb-1" />
                        <span class="block text-[10px] font-bold text-slate-800">سجل تجاري موثق</span>
                        <span class="block text-[9px] text-slate-400 font-mono">7055435114</span>
                    </div>

                    <div class="bg-slate-50 border border-slate-200/80 rounded-xl p-2.5">
                        <x-icon name="award" class="w-4 h-4 mx-auto text-amber-600 mb-1" />
                        <span class="block text-[10px] font-bold text-slate-800">سلامة الغذاء 100%</span>
                        <span class="block text-[9px] text-slate-400 font-mono">101565890</span>
                    </div>

                    <div class="bg-slate-50 border border-slate-200/80 rounded-xl p-2.5">
                        <x-icon name="map-pin" class="w-4 h-4 mx-auto text-emerald-600 mb-1" />
                        <span class="block text-[10px] font-bold text-slate-800">مكة المكرمة</span>
                        <span class="block text-[9px] text-slate-400">حي أم الجود</span>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="space-y-2.5 pt-2">
                    <!-- Primary Button: Dismiss & Browse Store -->
                    <button @click="dismissModal()"
                            type="button"
                            class="w-full bg-[#0B2E75] hover:bg-[#071E50] text-white font-bold py-3.5 px-5 rounded-2xl shadow-sm hover:shadow-md transition-all duration-200 flex items-center justify-center gap-2 text-sm cursor-pointer hover:scale-[1.01] active:scale-[0.99]">
                        <span>{{ $btnText }}</span>
                        <x-icon name="arrow-left" class="w-4 h-4" />
                    </button>

                    <!-- Secondary Button: WhatsApp Direct Support (If enabled) -->
                    @if($showWhatsapp)
                    <a href="https://wa.me/{{ $cleanWhatsApp }}?text={{ urlencode('السلام عليكم ورحمة الله، استفسار بخصوص المتجر وطلبات التجهيز') }}"
                       target="_blank"
                       class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-3 px-5 rounded-2xl shadow-2xs hover:shadow-xs transition-all duration-200 flex items-center justify-center gap-2 text-xs sm:text-sm">
                        <x-icon name="whatsapp" class="w-4 h-4" />
                        <span>تواصل معنا للاستفسار عبر واتساب</span>
                    </a>
                    @endif
                </div>

            </div>

            <!-- Footer Compliance Reassurance Note -->
            <div class="bg-slate-50 px-6 py-3 border-t border-slate-100 flex items-center justify-between text-[11px] text-slate-500">
                <span>متجر زاد نيكس الرسمي • المملكة العربية السعودية</span>
                <span class="text-[10px] font-bold text-[#0B2E75]">ZADNEX STORE</span>
            </div>

        </div>

    </div>
    @endif

</div>
@endif
