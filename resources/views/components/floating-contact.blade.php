@php
    $whatsapp = \App\Models\StoreSetting::get('whatsapp_number', '966580760890');
    $phone = \App\Models\StoreSetting::get('contact_phone', '+966 58 076 0890');
    $cleanWhatsApp = preg_replace('/[^0-9]/', '', $whatsapp);
    $cleanPhone = preg_replace('/[^0-9+]/', '', $phone);
    $defaultMessage = urlencode('مرحباً متجر زاد نيكس، أود الاستفسار عن المنتجات وطلبات التوصيل.');
@endphp

<!-- Zad Nex Floating WhatsApp & Direct Call Widget -->
<div class="fixed bottom-20 left-4 sm:left-6 md:bottom-8 md:left-8 z-40 flex flex-col gap-3 items-center" dir="ltr">
    
    <!-- Direct Call Button -->
    <div class="relative group/call">
        <a href="tel:{{ $cleanPhone }}" 
           class="w-11 h-11 sm:w-12 sm:h-12 rounded-full bg-[#0B2E75] hover:bg-[#FBB404] text-white hover:text-[#071E50] border border-white/20 flex items-center justify-center shadow-lg hover:shadow-xl hover:scale-110 active:scale-95 transition-all duration-300" 
           title="اتصل بنا مباشرة" 
           aria-label="اتصال هاتفي مباشر بمتجر زاد نيكس">
            <x-icon name="phone" class="w-5 h-5" />
        </a>
        
        <!-- Tooltip -->
        <div class="absolute left-full ml-3 top-1/2 -translate-y-1/2 px-3 py-1.5 bg-[#071E50] text-white text-xs font-bold rounded-lg shadow-xl whitespace-nowrap opacity-0 pointer-events-none group-hover/call:opacity-100 -translate-x-2 group-hover/call:translate-x-0 transition-all duration-200 hidden sm:flex items-center gap-1.5 border border-white/10" dir="rtl">
            <x-icon name="phone" class="w-3.5 h-3.5 text-[#FBB404]" />
            <span>اتصال هاتفي: {{ $phone }}</span>
        </div>
    </div>

    <!-- WhatsApp Floating Button with Radar Pulse -->
    <div class="relative group/wa">
        <!-- Radar Ring Effect -->
        <span class="absolute inset-0 rounded-full bg-[#25D366] animate-ping opacity-30 pointer-events-none"></span>

        <a href="https://wa.me/{{ $cleanWhatsApp }}?text={{ $defaultMessage }}" 
           target="_blank" 
           rel="noopener noreferrer" 
           class="relative w-13 h-13 sm:w-14 sm:h-14 rounded-full bg-[#25D366] hover:bg-[#20ba5a] text-white flex items-center justify-center shadow-xl hover:shadow-2xl hover:scale-110 active:scale-95 transition-all duration-300" 
           title="تواصل معنا عبر واتساب" 
           aria-label="محادثة واتساب مباشرة مع متجر زاد نيكس">
            <x-icon name="whatsapp" class="w-7 h-7 sm:w-8 sm:h-8" />
        </a>

        <!-- Tooltip -->
        <div class="absolute left-full ml-3 top-1/2 -translate-y-1/2 px-3.5 py-2 bg-[#071E50] text-white text-xs font-bold rounded-lg shadow-xl whitespace-nowrap opacity-0 pointer-events-none group-hover/wa:opacity-100 -translate-x-2 group-hover/wa:translate-x-0 transition-all duration-200 hidden sm:flex items-center gap-2 border border-white/10" dir="rtl">
            <span class="w-2 h-2 rounded-full bg-[#25D366] animate-pulse"></span>
            <span>محادثة واتساب متجر زاد نيكس</span>
        </div>
    </div>

</div>
