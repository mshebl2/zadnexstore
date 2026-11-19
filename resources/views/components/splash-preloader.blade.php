@php
    $alreadyShown = session()->has('splash_shown') && !request()->has('preview_splash');
    if (!$alreadyShown) {
        session()->put('splash_shown', true);
    }
@endphp

@if(!$alreadyShown)
<!-- Zad Nex Splash Preloader -->
<div x-data="{
        show: !sessionStorage.getItem('zadnex_splash_shown') || new URLSearchParams(window.location.search).has('preview_splash'),
        progress: 0,
        timer: null,
        progressInterval: null,
        init() {
            if (sessionStorage.getItem('zadnex_splash_shown') && !new URLSearchParams(window.location.search).has('preview_splash')) {
                this.show = false;
                return;
            }

            sessionStorage.setItem('zadnex_splash_shown', 'true');
            this.show = true;

            const durationMs = 1500;
            const stepMs = 20;
            const increment = (stepMs / durationMs) * 100;

            this.progressInterval = setInterval(() => {
                this.progress = Math.min(100, this.progress + increment);
            }, stepMs);

            this.timer = setTimeout(() => {
                this.dismiss();
            }, durationMs);
        },
        dismiss() {
            this.show = false;
            clearInterval(this.progressInterval);
            clearTimeout(this.timer);
        }
    }"
    x-cloak
    x-show="show"
    x-transition:leave="transition ease-out duration-500 transform"
    x-transition:leave-start="opacity-100 scale-100"
    x-transition:leave-end="opacity-0 scale-95 pointer-events-none"
    class="fixed inset-0 z-[100] flex flex-col items-center justify-center bg-[#F8FAFC]/95 backdrop-blur-md select-none"
    role="dialog"
    aria-label="شاشة التحميل">

    <!-- Skip Button (Top Right) -->
    <div class="absolute top-5 right-5 sm:top-7 sm:right-7 z-10">
        <button @click="dismiss()" 
                type="button" 
                class="bg-white hover:bg-gray-50 text-[#0F172A] text-xs font-bold px-4 py-1.5 rounded-full shadow-xs border border-[#E2E8F0] transition-all duration-200 hover:scale-105 flex items-center gap-1.5 cursor-pointer"
                title="تخطي">
            <span>تخطي</span>
        </button>
    </div>

    <!-- Centered Brand Logo & Progress Bar -->
    <div class="flex flex-col items-center justify-center text-center space-y-5 px-6 animate-fade-in">
        <!-- Logo -->
        <div class="relative transform transition-all duration-500 hover:scale-105">
            <img src="{{ asset('images/logo.png') }}" 
                 alt="متجر زاد نيكس - ZADNEX STORE" 
                 class="h-16 sm:h-24 w-auto object-contain filter drop-shadow-sm">
        </div>

        <!-- Brand Yellow & Blue Progress Bar -->
        <div class="w-40 sm:w-52 h-1.5 bg-[#E2E8F0] rounded-full overflow-hidden shadow-inner">
            <div class="h-full bg-gradient-to-r from-[#0B2E75] via-[#FBB404] to-[#0B2E75] rounded-full transition-all duration-75 ease-linear shadow-xs"
                 :style="'width: ' + progress + '%'"></div>
        </div>

        <p class="text-xs font-bold text-[#0B2E75] tracking-wider animate-pulse">
            متجر زاد نيكس • جودة وتوفير وسلامة موثقة
        </p>
    </div>
</div>
@endif
