@php
    $text = \App\Models\StoreSetting::get('announcement_text', 'مرحباً بكم في متجر زاد نيكس — عروض التوفير على المواد الغذائية والمنظفات — سجل تجاري: 7055435114');
    $isActive = \App\Models\StoreSetting::get('announcement_active', '1') === '1';
    $whatsapp = \App\Models\StoreSetting::get('whatsapp_number', '966580760890');
    $cleanWhatsApp = preg_replace('/[^0-9]/', '', $whatsapp);
@endphp

@if($isActive)
<style>
@keyframes marqueeScroll {
    0% { transform: translate3d(0, 0, 0); }
    100% { transform: translate3d(-50%, 0, 0); }
}
.announcement-marquee {
    display: flex;
    width: max-content;
    animation: marqueeScroll 28s linear infinite;
    will-change: transform;
}
.announcement-marquee:hover {
    animation-play-state: paused;
}
</style>

<!-- Zad Nex Continuous Moving Top Announcement Bar -->
<div class="bg-[#071E50] text-gray-100 text-[11px] sm:text-xs py-2 border-b border-[#0B2E75] relative overflow-hidden select-none z-30" dir="ltr">
    
    <div class="announcement-marquee flex items-center gap-8 cursor-default" dir="rtl">
        
        <!-- Track 1 (Original items) -->
        <div class="flex items-center gap-8 flex-shrink-0">
            <span class="flex items-center gap-2 font-bold text-[#FBB404]">
                <x-icon name="truck" class="w-3.5 h-3.5 text-[#FBB404]" />
                <span>{!! $text !!}</span>
            </span>

            <span class="text-[#FBB404] text-xs font-bold">✦</span>

            <span class="flex items-center gap-2 font-medium text-white">
                <x-icon name="shield-check" class="w-3.5 h-3.5 text-[#FBB404]" />
                <span>سلامة غذائية معتمدة 100% — شهادة سلامة رقم 101565890</span>
            </span>

            <span class="text-[#FBB404] text-xs font-bold">✦</span>

            @if(\App\Models\StoreSetting::get('tamara_enabled', '0') === '1' || \App\Models\StoreSetting::get('tabby_enabled', '0') === '1')
            <span class="flex items-center gap-2 font-medium">
                @if(\App\Models\StoreSetting::get('tabby_enabled', '0') === '1')
                <span class="bg-[#3BFF9E] text-black font-black text-[9px] px-1.5 py-0.5 rounded font-sans">tabby</span>
                @endif
                @if(\App\Models\StoreSetting::get('tamara_enabled', '0') === '1')
                <span class="bg-gradient-to-r from-[#FFB4A2] to-[#9CD5FF] text-black font-bold text-[9px] px-1.5 py-0.5 rounded font-sans">tamara</span>
                @endif
                <span>قسّم مشترياتك على 4 دفعات بدون أي فوائد</span>
            </span>
            @else
            <span class="flex items-center gap-2 font-bold text-[#FBB404]">
                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-ping"></span>
                <span>توصيل سريع حصرياً داخل مكة المكرمة ⚡ والدفع عند الاستلام (COD)</span>
            </span>
            @endif

            <span class="text-[#FBB404] text-xs font-bold">✦</span>

            <span class="flex items-center gap-2 font-medium text-white">
                <x-icon name="map-pin" class="w-3.5 h-3.5 text-[#FBB404]" />
                <span>معرضنا بمكة المكرمة - حي أم الجود - شارع عبد الله بن أبي بكر الصديق</span>
            </span>

            <span class="text-[#FBB404] text-xs font-bold">✦</span>

            <a href="https://wa.me/{{ $cleanWhatsApp }}" target="_blank" class="flex items-center gap-1.5 text-[#25D366] hover:underline font-bold">
                <x-icon name="whatsapp" class="w-3.5 h-3.5" />
                <span>واتساب وطلبات فورية: +966 58 076 0890</span>
            </a>

            <span class="text-[#FBB404] text-xs font-bold">✦</span>
        </div>

        <!-- Track 2 (Identical duplicate for seamless continuous infinite loop) -->
        <div class="flex items-center gap-8 flex-shrink-0" aria-hidden="true">
            <span class="flex items-center gap-2 font-bold text-[#FBB404]">
                <x-icon name="truck" class="w-3.5 h-3.5 text-[#FBB404]" />
                <span>{!! $text !!}</span>
            </span>

            <span class="text-[#FBB404] text-xs font-bold">✦</span>

            <span class="flex items-center gap-2 font-medium text-white">
                <x-icon name="shield-check" class="w-3.5 h-3.5 text-[#FBB404]" />
                <span>سلامة غذائية معتمدة 100% — شهادة سلامة رقم 101565890</span>
            </span>

            <span class="text-[#FBB404] text-xs font-bold">✦</span>

            @if(\App\Models\StoreSetting::get('tamara_enabled', '0') === '1' || \App\Models\StoreSetting::get('tabby_enabled', '0') === '1')
            <span class="flex items-center gap-2 font-medium">
                @if(\App\Models\StoreSetting::get('tabby_enabled', '0') === '1')
                <span class="bg-[#3BFF9E] text-black font-black text-[9px] px-1.5 py-0.5 rounded font-sans">tabby</span>
                @endif
                @if(\App\Models\StoreSetting::get('tamara_enabled', '0') === '1')
                <span class="bg-gradient-to-r from-[#FFB4A2] to-[#9CD5FF] text-black font-bold text-[9px] px-1.5 py-0.5 rounded font-sans">tamara</span>
                @endif
                <span>قسّم مشترياتك على 4 دفعات بدون أي فوائد</span>
            </span>
            @else
            <span class="flex items-center gap-2 font-bold text-[#FBB404]">
                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-ping"></span>
                <span>توصيل سريع حصرياً داخل مكة المكرمة ⚡ والدفع عند الاستلام (COD)</span>
            </span>
            @endif

            <span class="text-[#FBB404] text-xs font-bold">✦</span>

            <span class="flex items-center gap-2 font-medium text-white">
                <x-icon name="map-pin" class="w-3.5 h-3.5 text-[#FBB404]" />
                <span>معرضنا بمكة المكرمة - حي أم الجود - شارع عبد الله بن أبي بكر الصديق</span>
            </span>

            <span class="text-[#FBB404] text-xs font-bold">✦</span>

            <a href="https://wa.me/{{ $cleanWhatsApp }}" target="_blank" class="flex items-center gap-1.5 text-[#25D366] hover:underline font-bold">
                <x-icon name="whatsapp" class="w-3.5 h-3.5" />
                <span>واتساب وطلبات فورية: +966 58 076 0890</span>
            </a>

            <span class="text-[#FBB404] text-xs font-bold">✦</span>
        </div>

    </div>
</div>
@endif
