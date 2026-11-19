<!-- Authentic Saudi Payment Method Badges (Unified & Compact Size) -->
<div class="flex items-center gap-1.5 sm:gap-2 flex-wrap justify-center">

    <!-- 1. mada (مدى) -->
    <div class="w-[38px] h-[24px] bg-white rounded-[5px] flex items-center justify-center p-0.5 border border-[#EDE6DA]/30 shadow-2xs hover:scale-105 transition-transform shrink-0" 
         title="مدى mada - بطاقات الصراف والخصم المباشر السعودية">
        <img src="{{ asset('images/payments/mada.png') }}" 
             alt="مدى mada" 
             class="max-h-[14px] max-w-[30px] w-auto h-auto object-contain">
    </div>

    <!-- 2. Visa & Mastercard -->
    <div class="w-[38px] h-[24px] bg-white rounded-[5px] flex items-center justify-center p-0.5 border border-[#EDE6DA]/30 shadow-2xs hover:scale-105 transition-transform shrink-0" 
         title="فيزا وماستركارد VISA & MasterCard">
        <img src="{{ asset('images/payments/visa-mastercard.png') }}" 
             alt="VISA & MasterCard" 
             class="max-h-[14px] max-w-[30px] w-auto h-auto object-contain">
    </div>

    <!-- 3. Apple Pay -->
    <div class="w-[38px] h-[24px] bg-white rounded-[5px] flex items-center justify-center p-0.5 border border-[#EDE6DA]/30 shadow-2xs hover:scale-105 transition-transform shrink-0" 
         title="أبل باي Apple Pay">
        <img src="{{ asset('images/payments/apple-pay.png') }}" 
             alt="Apple Pay" 
             class="max-h-[13px] max-w-[28px] w-auto h-auto object-contain">
    </div>

    <!-- 4. STC Bank -->
    <div class="w-[38px] h-[24px] bg-white rounded-[5px] flex items-center justify-center p-0.5 border border-[#EDE6DA]/30 shadow-2xs hover:scale-105 transition-transform shrink-0" 
         title="STC Bank">
        <img src="{{ asset('images/payments/stc-bank.png') }}" 
             alt="STC Bank" 
             class="max-h-[12px] max-w-[32px] w-auto h-auto object-contain">
    </div>

    <!-- 5. Bank Transfer (التحويل البنكي) -->
    <div class="w-[38px] h-[24px] bg-white rounded-[5px] flex items-center justify-center p-0.5 border border-[#EDE6DA]/30 shadow-2xs hover:scale-105 transition-transform shrink-0" 
         title="التحويل البنكي المباشر">
        <img src="{{ asset('images/payments/bank-transfer.png') }}" 
             alt="تحويل بنكي" 
             class="max-h-[13px] max-w-[24px] w-auto h-auto object-contain">
    </div>

    @if(\App\Models\StoreSetting::get('tabby_enabled', '0') === '1')
    <!-- 6. Tabby (تابي) -->
    <div class="w-[38px] h-[24px] bg-white rounded-[5px] flex items-center justify-center p-0.5 border border-[#EDE6DA]/30 shadow-2xs hover:scale-105 transition-transform shrink-0" 
         title="تابي Tabby - قسّم مشترياتك على 4 دفعات بدون فوائد">
        <img src="{{ asset('images/payments/tabby.png') }}" 
             alt="Tabby تابي" 
             class="max-h-[13px] max-w-[28px] w-auto h-auto object-contain rounded-xs">
    </div>
    @endif

    @if(\App\Models\StoreSetting::get('tamara_enabled', '0') === '1')
    <!-- 7. Tamara (تمارا) -->
    <div class="w-[38px] h-[24px] bg-white rounded-[5px] flex items-center justify-center p-0.5 border border-[#EDE6DA]/30 shadow-2xs hover:scale-105 transition-transform shrink-0" 
         title="تمارا Tamara - قسّم دفعاتك بدون رسوم إضافية">
        <img src="{{ asset('images/payments/tamara.png') }}" 
             alt="Tamara تمارا" 
             class="max-h-[13px] max-w-[28px] w-auto h-auto object-contain rounded-xs">
    </div>
    @endif

    <!-- 8. Cash On Delivery (الدفع عند الاستلام) -->
    <div class="w-[38px] h-[24px] bg-white rounded-[5px] flex items-center justify-center p-0.5 border border-[#EDE6DA]/30 shadow-2xs hover:scale-105 transition-transform shrink-0" 
         title="الدفع عند الاستلام (COD) بمكة المكرمة">
        <img src="{{ asset('images/payments/cod.png') }}" 
             alt="الدفع عند الاستلام" 
             class="max-h-[13px] max-w-[26px] w-auto h-auto object-contain rounded-xs">
    </div>

    <!-- 9. WhatsApp Order -->
    <div class="w-[38px] h-[24px] bg-[#25D366] rounded-[5px] flex items-center justify-center p-0.5 text-white shadow-2xs hover:scale-105 transition-transform shrink-0" 
         title="طلب مباشر وسريع عبر واتساب">
        <x-icon name="whatsapp" class="w-3.5 h-3.5" />
    </div>

</div>
