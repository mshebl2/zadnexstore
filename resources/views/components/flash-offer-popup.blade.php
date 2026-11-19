@php
    $activePopups = \App\Models\FlashPopup::active()->get();
    
    if ($activePopups->isNotEmpty()) {
        $popup = $activePopups->random();
        $isActive = true;
        $title = $popup->title_ar;
        $subtitle = $popup->subtitle_ar;
        $couponCode = $popup->coupon_code;
        $badge = $popup->badge_ar ?? 'عرض خاص من زاد نيكس';
        $delaySeconds = (int) ($popup->delay_seconds ?? 3);
        $durationSeconds = (int) ($popup->duration_seconds ?? 6);
        $image = $popup->image_url ?? 'https://images.unsplash.com/photo-1542838132-92c53300491e?auto=format&fit=crop&w=700&q=80';
        $buttonText = $popup->button_text_ar ?? 'تسوق العروض التوفيرية';
        $buttonUrl = $popup->button_url ?? route('products.index');
    } else {
        $isActive = \App\Models\StoreSetting::get('popup_ad_active', '1') === '1';
        $title = \App\Models\StoreSetting::get('popup_ad_title', 'أهلاً بكم في متجر زاد نيكس!');
        $subtitle = \App\Models\StoreSetting::get('popup_ad_subtitle', 'احصل على خصم 10% إضافي على كافة سلال المواد الغذائية، المشروبات، المنظفات، ومستلزمات البيت.');
        $couponCode = \App\Models\StoreSetting::get('popup_ad_coupon', 'ZADNEX10');
        $badge = \App\Models\StoreSetting::get('popup_ad_badge', 'كوبون التوفير الحصري');
        $delaySeconds = (int) \App\Models\StoreSetting::get('popup_ad_delay', '3');
        $durationSeconds = (int) \App\Models\StoreSetting::get('popup_ad_duration', '6');
        $image = \App\Models\StoreSetting::get('popup_ad_image', 'https://images.unsplash.com/photo-1542838132-92c53300491e?auto=format&fit=crop&w=700&q=80');
        $buttonText = 'تسوق منتجات زاد نيكس';
        $buttonUrl = route('products.index');
    }
@endphp

@if($isActive)
<!-- Zad Nex Promotional Offer Modal -->
<div x-data="{
        show: false,
        timeLeft: {{ $durationSeconds }},
        progress: 100,
        copied: false,
        timer: null,
        progressInterval: null,
        init() {
            if (sessionStorage.getItem('zadnex_offer_popup_seen')) {
                return;
            }

            setTimeout(() => {
                this.openPopup();
            }, {{ $delaySeconds * 1000 }});
        },
        openPopup() {
            this.show = true;
            this.timeLeft = {{ $durationSeconds }};
            this.progress = 100;

            const totalMs = {{ $durationSeconds * 1000 }};
            const intervalMs = 50;
            const stepPercent = (intervalMs / totalMs) * 100;

            this.progressInterval = setInterval(() => {
                this.progress = Math.max(0, this.progress - stepPercent);
            }, intervalMs);

            this.timer = setInterval(() => {
                this.timeLeft--;
                if (this.timeLeft <= 0) {
                    this.closePopup();
                }
            }, 1000);
        },
        closePopup() {
            this.show = false;
            clearInterval(this.timer);
            clearInterval(this.progressInterval);
            sessionStorage.setItem('zadnex_offer_popup_seen', 'true');
        },
        copyCoupon() {
            navigator.clipboard.writeText('{{ $couponCode }}');
            this.copied = true;
            setTimeout(() => { this.copied = false; }, 3000);
        }
    }"
    x-cloak
    x-show="show"
    class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6"
    role="dialog"
    aria-modal="true"
    @keydown.escape.window="closePopup()">

    <!-- Semi-transparent Backdrop -->
    <div x-show="show" 
         x-transition:enter="transition-opacity ease-out duration-300" 
         x-transition:enter-start="opacity-0" 
         x-transition:enter-end="opacity-100" 
         x-transition:leave="transition-opacity ease-in duration-200" 
         x-transition:leave-start="opacity-100" 
         x-transition:leave-end="opacity-0" 
         @click="closePopup()"
         class="fixed inset-0 bg-black/60 backdrop-blur-xs"></div>

    <!-- Zad Nex Card -->
    <div x-show="show" 
         x-transition:enter="transition ease-out duration-400 transform" 
         x-transition:enter-start="opacity-0 scale-90 translate-y-4" 
         x-transition:enter-end="opacity-100 scale-100 translate-y-0" 
         x-transition:leave="transition ease-in duration-300 transform" 
         x-transition:leave-start="opacity-100 scale-100 translate-y-0" 
         x-transition:leave-end="opacity-0 scale-90 translate-y-4" 
         class="relative w-full max-w-lg bg-white rounded-3xl overflow-hidden shadow-2xl border-2 border-[#0B2E75] z-10 text-right">

        <!-- Animated Countdown Progress Bar (Top) -->
        <div class="h-1.5 w-full bg-[#E2E8F0] overflow-hidden">
            <div class="h-full bg-gradient-to-l from-[#0B2E75] via-[#FBB404] to-[#0B2E75] transition-all duration-75"
                 :style="'width: ' + progress + '%'"></div>
        </div>

        <!-- Close Button -->
        <button @click="closePopup()" 
                type="button" 
                class="absolute top-4 left-4 z-20 w-8 h-8 rounded-full bg-black/50 hover:bg-black/75 text-white flex items-center justify-center transition focus:outline-none" 
                title="إغلاق العرض"
                aria-label="إغلاق العرض">
            <x-icon name="x" class="w-4 h-4" />
        </button>

        <!-- Card Banner / Image -->
        <div class="relative h-44 sm:h-52 overflow-hidden bg-[#071E50]">
            <img src="{{ $image }}" 
                 alt="عرض زاد نيكس التوفيري" 
                 class="w-full h-full object-cover opacity-85 filter brightness-95">
            <div class="absolute inset-0 bg-gradient-to-t from-[#071E50] via-black/30 to-transparent"></div>
            
            <!-- Badge -->
            <div class="absolute top-4 right-4 bg-[#FBB404] text-[#071E50] text-[11px] font-black px-3 py-1 rounded-full shadow-md flex items-center gap-1.5">
                <x-icon name="award" class="w-3.5 h-3.5" />
                <span>{{ $badge }}</span>
            </div>

            <!-- Title over image -->
            <div class="absolute bottom-4 right-6 left-6 text-white">
                <h3 class="text-xl sm:text-2xl font-bold tracking-wide drop-shadow-md">
                    {{ $title }}
                </h3>
            </div>
        </div>

        <!-- Content Area -->
        <div class="p-6 sm:p-7 space-y-4 bg-white">
            <p class="text-xs sm:text-sm text-[#64748B] leading-relaxed">
                {{ $subtitle }}
            </p>

            @if(!empty($couponCode))
            <!-- Coupon Box -->
            <div class="bg-[#EFF6FF] border-2 border-dashed border-[#0B2E75] rounded-2xl p-3.5 flex items-center justify-between gap-3">
                <div class="flex items-center gap-2">
                    <span class="text-xs text-[#64748B] font-medium">كود الخصم:</span>
                    <span class="font-mono text-base sm:text-lg font-black text-[#0B2E75] tracking-wider select-all">
                        {{ $couponCode }}
                    </span>
                </div>

                <button type="button" 
                        @click="copyCoupon()" 
                        class="px-4 py-2 rounded-xl text-xs font-bold transition flex items-center gap-1.5 shadow-xs"
                        :class="copied ? 'bg-green-600 text-white' : 'bg-[#0B2E75] hover:bg-[#FBB404] hover:text-[#071E50] text-white'">
                    <template x-if="!copied">
                        <span class="flex items-center gap-1">
                            <x-icon name="check-circle" class="w-3.5 h-3.5" />
                            <span>نسخ الكود</span>
                        </span>
                    </template>
                    <template x-if="copied">
                        <span class="flex items-center gap-1">
                            <x-icon name="check-circle" class="w-3.5 h-3.5" />
                            <span>تم النسخ ✓</span>
                        </span>
                    </template>
                </button>
            </div>
            @endif

            <!-- Action Buttons -->
            <div class="pt-2 flex items-center gap-3">
                <a href="{{ $buttonUrl }}" 
                   @click="closePopup()" 
                   class="flex-1 bg-[#FBB404] hover:bg-[#E5A300] text-[#071E50] font-black text-xs sm:text-sm py-3 px-4 rounded-xl text-center shadow-lg transition duration-200 flex items-center justify-center gap-2">
                    <span>{{ $buttonText }}</span>
                    <x-icon name="arrow-left" class="w-4 h-4" />
                </a>

                <button @click="closePopup()" 
                        type="button" 
                        class="px-4 py-3 text-xs text-[#64748B] hover:text-[#0F172A] font-bold rounded-xl transition">
                    تخطي العرض
                </button>
            </div>

            <!-- Auto-close subtle ticker -->
            <div class="text-center pt-1">
                <span class="text-[10px] text-[#94A3B8]">
                    يغلق هذا العرض تلقائياً بعد <strong x-text="timeLeft" class="text-[#0B2E75]"></strong> ثوانٍ
                </span>
            </div>
        </div>

    </div>
</div>
@endif
