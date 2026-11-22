@php
    $categories = \App\Models\Category::whereNull('parent_id')->active()->get();
    $phone = \App\Models\StoreSetting::get('contact_phone', '+966 58 076 0890');
    $whatsapp = \App\Models\StoreSetting::get('whatsapp_number', '966580760890');
    $email = \App\Models\StoreSetting::get('contact_email', 'info@zadnexstore.com');
    $cr = \App\Models\StoreSetting::get('commercial_registry', '7055435114');
    $license = \App\Models\StoreSetting::get('municipal_license', '480526670333');
    $safety = \App\Models\StoreSetting::get('safety_certificate', '101565890');
    $address = \App\Models\StoreSetting::get('address', 'المملكة العربية السعودية، مكة المكرمة، حي أم الجود، شارع عبد الله بن أبي بكر الصديق');
@endphp

<footer class="bg-[#071E50] text-gray-200 pt-16 pb-12 border-t-4 border-[#FBB404]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-10 pb-12 border-b border-white/10">
            
            <!-- Col 1: Brand Info -->
            <div class="space-y-4">
                <div class="flex flex-col">
                    <a href="{{ route('home') }}" class="inline-block" title="متجر زاد نيكس - ZADNEX STORE">
                        <img src="{{ asset('images/logo.png') }}" 
                             alt="متجر زاد نيكس - ZADNEX STORE" 
                             class="h-16 w-auto object-contain">
                    </a>
                    <span class="text-xs text-[#FBB404] mt-2 font-bold">
                        متجر زاد نيكس • مواد غذائية • مشروبات • منظفات • كماليات
                    </span>
                </div>
                <p class="text-xs text-gray-300 leading-relaxed">
                    متجر زاد نيكس — خياركم الأول لأجود المواد الغذائية والتموينية، المشروبات، المنظفات المنزلية ومستلزمات البيت اليومية. نلتزم بأعلى معايير سلامة الغذاء ونقدم أفضل الأسعار التوفيرية مع خدمة التوصيل السريع.
                </p>
                <div class="flex items-center gap-2.5 pt-2 flex-wrap">
                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $whatsapp) }}" target="_blank" rel="noopener noreferrer" class="w-8 h-8 rounded-full bg-[#25D366] text-white flex items-center justify-center transition-all duration-300 hover:scale-110 shadow-sm flex-shrink-0" title="واتساب" aria-label="تواصل معنا عبر واتساب">
                        <x-icon name="whatsapp" class="w-4 h-4" />
                    </a>
                    <a href="tel:{{ preg_replace('/[^0-9+]/', '', $phone) }}" class="w-8 h-8 rounded-full bg-white/15 text-white hover:bg-[#FBB404] hover:text-[#071E50] flex items-center justify-center transition-all duration-300 hover:scale-110 shadow-sm flex-shrink-0" title="اتصال مباشر" aria-label="اتصل بنا">
                        <x-icon name="phone" class="w-4 h-4" />
                    </a>
                    <a href="mailto:{{ $email }}" class="w-8 h-8 rounded-full bg-white/15 text-white hover:bg-[#FBB404] hover:text-[#071E50] flex items-center justify-center transition-all duration-300 hover:scale-110 shadow-sm flex-shrink-0" title="البريد الإلكتروني" aria-label="راسلنا بالبريد">
                        <x-icon name="mail" class="w-4 h-4" />
                    </a>
                </div>
            </div>

            <!-- Col 2: Categories -->
            <div>
                <h3 class="text-sm font-bold tracking-wider text-[#FBB404] uppercase mb-4">أقسام المتجر</h3>
                <ul class="space-y-2.5 text-xs text-gray-300">
                    @foreach($categories as $cat)
                    <li>
                        <a href="{{ route('category.show', $cat->slug) }}" class="hover:text-[#FBB404] transition flex items-center gap-1.5">
                            <span class="w-1.5 h-1.5 rounded-full bg-[#FBB404]"></span>
                            <span>{{ $cat->name_ar }}</span>
                        </a>
                    </li>
                    @endforeach
                    <li>
                        <a href="{{ route('products.index') }}" class="hover:text-[#FBB404] transition flex items-center gap-1.5">
                            <span class="w-1.5 h-1.5 rounded-full bg-[#FBB404]"></span>
                            <span>جميع المنتجات والسلع</span>
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Col 3: Customer Care, Legal & Verification -->
            <div>
                <h3 class="text-sm font-bold tracking-wider text-[#FBB404] uppercase mb-4">خدمة العملاء والتوثيق</h3>
                <ul class="space-y-2.5 text-xs text-gray-300">
                    <li><a href="{{ route('about') }}" class="hover:text-[#FBB404] transition font-bold text-white flex items-center gap-1.5"><span class="w-1.5 h-1.5 rounded-full bg-[#FBB404]"></span><span>من نحن ورؤيتنا</span></a></li>
                    <li><a href="{{ route('contact') }}" class="hover:text-[#FBB404] transition font-semibold text-white flex items-center gap-1.5"><span class="w-1.5 h-1.5 rounded-full bg-[#FBB404]"></span><span>اتصل بنا وموقعنا الجغرافي</span></a></li>
                    <li><a href="{{ route('policy.shipping') }}" class="hover:text-[#FBB404] transition">الشحن والتوصيل السريع</a></li>
                    <li><a href="{{ route('policy.returns') }}" class="hover:text-[#FBB404] transition">سياسة الاستبدال والاسترجاع</a></li>
                    <li><a href="{{ route('policy.privacy') }}" class="hover:text-[#FBB404] transition">سياسة الخصوصية والأمان</a></li>
                    <li><a href="{{ route('policy.terms') }}" class="hover:text-[#FBB404] transition">الشروط والأحكام</a></li>
                </ul>

                <div class="mt-4 pt-4 border-t border-white/10 text-xs text-gray-300 space-y-2.5">
                    <p class="flex items-start gap-2">
                        <x-icon name="map-pin" class="w-3.5 h-3.5 text-[#FBB404] shrink-0 mt-0.5" />
                        <span>مكة المكرمة - حي أم الجود - شارع عبد الله بن أبي بكر الصديق</span>
                    </p>
                    <p class="flex items-center gap-2">
                        <x-icon name="user" class="w-3.5 h-3.5 text-[#FBB404] shrink-0" />
                        <span>مالك المنشأة: <strong class="text-white">منال سالم فرج</strong></span>
                    </p>
                    <p class="flex items-center gap-2">
                        <x-icon name="shield-check" class="w-3.5 h-3.5 text-[#FBB404] shrink-0" />
                        <span>السجل التجاري: <strong class="text-white font-mono" dir="ltr">{{ $cr }}</strong> (تاريخ التأسيس: 2026/10/20م)</span>
                    </p>
                    <p class="flex items-center gap-2">
                        <x-icon name="check-circle" class="w-3.5 h-3.5 text-[#FBB404] shrink-0" />
                        <span>رخصة نشاط تجاري: <strong class="text-white font-mono" dir="ltr">{{ $license }}</strong></span>
                    </p>
                    <p class="flex items-center gap-2">
                        <x-icon name="award" class="w-3.5 h-3.5 text-[#FBB404] shrink-0" />
                        <span>شهادة سلامة الغذاء: <strong class="text-white font-mono" dir="ltr">{{ $safety }}</strong></span>
                    </p>
                </div>
            </div>

            <!-- Col 4: Newsletter & Contact Direct -->
            <div>
                <h3 class="text-sm font-bold tracking-wider text-[#FBB404] uppercase mb-4">عروض التوفير الأسبوعية</h3>
                <p class="text-xs text-gray-300 mb-3 leading-relaxed">
                    سجل بريدك الإلكتروني لتصلك أحدث العروض والخصومات على المواد التموينية والمنظفات مباشرة.
                </p>
                <form action="{{ route('newsletter.subscribe') }}" method="POST" class="space-y-2">
                    @csrf
                    <div class="relative">
                        <input type="email" 
                               name="email" 
                               required 
                               placeholder="أدخل بريدك الإلكتروني" 
                               class="w-full bg-white/10 border border-white/20 rounded-xl px-3.5 py-2.5 text-xs text-white placeholder-gray-400 focus:outline-none focus:border-[#FBB404] text-left" 
                               dir="ltr">
                    </div>
                    <button type="submit" class="w-full bg-[#FBB404] hover:bg-[#E5A300] text-[#071E50] font-black text-xs py-2.5 px-4 rounded-xl transition duration-200 shadow-md">
                        اشترك في نشرة التوفير
                    </button>
                </form>

                <div class="mt-4 pt-4 border-t border-white/10 flex items-center gap-3 bg-white/5 p-3 rounded-xl border border-white/10">
                    <img src="{{ asset('images/zadnex-qr-code.png') }}" alt="باركود متجر زاد نيكس" class="w-14 h-14 bg-white p-1 rounded-lg shrink-0">
                    <div>
                        <span class="text-[11px] font-bold text-[#FBB404] block">باركود المتجر السريع</span>
                        <span class="text-[10px] text-gray-300">امسح للدخول الفوري للتسوق</span>
                    </div>
                </div>

                <div class="mt-3 pt-3 border-t border-white/10 text-xs">
                    <span class="text-gray-400 block mb-1">البريد الرسمي:</span>
                    <a href="mailto:{{ $email }}" class="text-[#FBB404] font-bold font-mono hover:underline" dir="ltr">{{ $email }}</a>
                </div>
            </div>
        </div>

        <!-- Bottom bar: Payment methods & Copyright -->
        <div class="pt-8 flex flex-col md:flex-row items-center justify-between text-xs text-gray-400 gap-4">
            <div class="flex flex-col sm:flex-row items-center gap-2 text-center sm:text-right flex-wrap">
                <span>&copy; {{ date('Y') }} متجر زاد نيكس (ZADNEX STORE). جميع الحقوق محفوظة.</span>
                <span class="hidden sm:inline text-gray-600">|</span>
                <span>سجل تجاري موثق: {{ $cr }}</span>
                <span class="hidden sm:inline text-gray-600">|</span>
                <span>صُمم بواسطة <a href="https://wa.me/966543907572" target="_blank" rel="noopener noreferrer" class="text-[#FBB404] hover:underline font-bold transition">رواد الرقمية</a></span>
            </div>

            <!-- Modern Payment Badges -->
            <x-payment-badges />
        </div>
    </div>
</footer>
