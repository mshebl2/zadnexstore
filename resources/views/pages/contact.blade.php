<x-app-layout>
    <x-slot:title>اتصل بنا | متجر زاد نيكس (ZADNEX) — خدمة العملاء والمساندة بمكة المكرمة</x-slot:title>
    <x-slot:description>تواصل مع فريق متجر زاد نيكس للمواد الغذائية والمشروبات والمنظفات والكماليات بمكة المكرمة. هاتف وواتساب: 0580760890، بريد: info@zadnexstore.com.</x-slot:description>

    <!-- Header & Breadcrumbs -->
    <div class="bg-white border-b border-[#E2E8F0] py-8 sm:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-right">
            <nav class="text-xs text-[#64748B] mb-3 flex items-center gap-2">
                <a href="{{ route('home') }}" class="hover:text-[#0B2E75] transition">الرئيسية</a>
                <span>/</span>
                <span class="text-[#0F172A] font-medium">اتصل بنا</span>
            </nav>
            <div class="flex items-center gap-3">
                <span class="w-2.5 h-2.5 rounded-full bg-[#FBB404]"></span>
                <span class="text-xs font-bold text-[#0B2E75] uppercase tracking-wider">خدمة العملاء والمساندة</span>
            </div>
            <h1 class="text-2xl sm:text-4xl font-extrabold text-[#0F172A] mt-2">تواصل مع فريق متجر زاد نيكس</h1>
            <p class="text-xs sm:text-sm text-[#64748B] mt-2 max-w-2xl leading-relaxed">
                نسعد بخدمتكم وتلبية كافة طلباتكم من المواد الغذائية والمشروبات والمنظفات والكماليات. فريقنا متواجد على مدار الساعة لخدمتكم ومتابعة طلباتكم.
            </p>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 sm:py-14 text-right">

        <!-- Top 4 Direct Contact Channels -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-12">
            
            <!-- 1. Direct Call -->
            <div class="bg-white p-6 rounded-2xl border border-[#E2E8F0] shadow-xs hover:border-[#0B2E75] hover:shadow-md transition-all duration-300 flex flex-col justify-between">
                <div>
                    <div class="w-12 h-12 rounded-xl bg-[#EFF6FF] border border-[#BFDBFE] text-[#0B2E75] flex items-center justify-center mb-4">
                        <x-icon name="phone" class="w-6 h-6" />
                    </div>
                    <span class="text-xs font-semibold text-[#64748B] block">اتصال هاتفي مباشر</span>
                    <h3 class="text-base font-bold text-[#0F172A] mt-1" dir="ltr">+966 58 076 0890</h3>
                    <p class="text-xs text-[#64748B] mt-2 leading-relaxed">
                        يومياً من 8:00 صباحاً حتى 11:30 مساءً للرد الفوري على طلباتكم.
                    </p>
                </div>
                <div class="pt-5 mt-4 border-t border-[#F1F5F9]">
                    <a href="tel:+966580760890" 
                       class="inline-flex items-center gap-2 text-xs font-bold text-[#0B2E75] hover:text-[#071E50] transition">
                        <span>اتصل الآن</span>
                        <x-icon name="arrow-left" class="w-3.5 h-3.5" />
                    </a>
                </div>
            </div>

            <!-- 2. WhatsApp Customer Support -->
            <div class="bg-white p-6 rounded-2xl border border-[#E2E8F0] shadow-xs hover:border-[#25D366] hover:shadow-md transition-all duration-300 flex flex-col justify-between">
                <div>
                    <div class="w-12 h-12 rounded-xl bg-[#E8F8EE] border border-[#25D366]/20 text-[#25D366] flex items-center justify-center mb-4">
                        <x-icon name="whatsapp" class="w-6 h-6" />
                    </div>
                    <div class="flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full bg-[#25D366] animate-pulse"></span>
                        <span class="text-xs font-semibold text-[#25D366]">محادثة فورية مباشرة</span>
                    </div>
                    <h3 class="text-base font-bold text-[#0F172A] mt-1">واتساب متجر زاد نيكس</h3>
                    <p class="text-xs text-[#64748B] mt-2 leading-relaxed">
                        تواصل فورياً عبر واتساب لتأكيد الطلب، الاستفسار، أو إرسال قائمة التموين.
                    </p>
                </div>
                <div class="pt-5 mt-4 border-t border-[#F1F5F9]">
                    <a href="https://wa.me/966580760890?text={{ urlencode('مرحباً متجر زاد نيكس، أود الاستفسار عن المنتجات والطلبات.') }}" 
                       target="_blank" 
                       rel="noopener noreferrer" 
                       class="inline-flex items-center gap-2 text-xs font-bold text-[#25D366] hover:underline transition">
                        <span>فتح محادثة واتساب</span>
                        <x-icon name="arrow-left" class="w-3.5 h-3.5" />
                    </a>
                </div>
            </div>

            <!-- 3. Official Email -->
            <div class="bg-white p-6 rounded-2xl border border-[#E2E8F0] shadow-xs hover:border-[#0B2E75] hover:shadow-md transition-all duration-300 flex flex-col justify-between">
                <div>
                    <div class="w-12 h-12 rounded-xl bg-[#EFF6FF] border border-[#BFDBFE] text-[#0B2E75] flex items-center justify-center mb-4">
                        <x-icon name="mail" class="w-6 h-6" />
                    </div>
                    <span class="text-xs font-semibold text-[#64748B] block">البريد الإلكتروني الرسمي</span>
                    <h3 class="text-base font-bold text-[#0F172A] mt-1 font-mono text-sm" dir="ltr">info@zadnexstore.com</h3>
                    <p class="text-xs text-[#64748B] mt-2 leading-relaxed">
                        للمراسلات الرسمية وعروض التوريد والشراكات التجارية، نرد خلال ساعات العمل.
                    </p>
                </div>
                <div class="pt-5 mt-4 border-t border-[#F1F5F9]">
                    <a href="mailto:info@zadnexstore.com" 
                       class="inline-flex items-center gap-2 text-xs font-bold text-[#0B2E75] hover:text-[#071E50] transition">
                        <span>إرسال بريد إلكتروني</span>
                        <x-icon name="arrow-left" class="w-3.5 h-3.5" />
                    </a>
                </div>
            </div>

            <!-- 4. Official Address & Commercial Registry -->
            <div class="bg-white p-6 rounded-2xl border border-[#E2E8F0] shadow-xs hover:border-[#0B2E75] hover:shadow-md transition-all duration-300 flex flex-col justify-between">
                <div>
                    <div class="w-12 h-12 rounded-xl bg-[#EFF6FF] border border-[#BFDBFE] text-[#0B2E75] flex items-center justify-center mb-4">
                        <x-icon name="map-pin" class="w-6 h-6" />
                    </div>
                    <span class="text-xs font-semibold text-[#64748B] block">المقر والمعرض</span>
                    <h3 class="text-base font-bold text-[#0F172A] mt-1">مكة المكرمة - حي أم الجود</h3>
                    <p class="text-xs text-[#64748B] mt-2 leading-relaxed">
                        شارع عبد الله بن أبي بكر الصديق، العاصمة المقدسة.
                    </p>
                </div>
                <div class="pt-5 mt-4 border-t border-[#F1F5F9] flex items-center justify-between text-[11px] text-[#64748B]">
                    <span>سجل: 7055435114</span>
                    <span class="bg-[#FBB404]/20 text-[#071E50] font-bold px-2 py-0.5 rounded">مؤسسة نشطة</span>
                </div>
            </div>

        </div>

        <!-- Main Form & Interactive Google Map Section -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            
            <!-- Contact Form (6 cols) -->
            <div class="lg:col-span-6 bg-white rounded-3xl border border-[#E2E8F0] p-6 sm:p-10 shadow-xs">
                
                <div class="mb-6">
                    <span class="text-xs font-bold text-[#0B2E75] uppercase tracking-wider block">نموذج المراسلة المباشر</span>
                    <h2 class="text-xl sm:text-2xl font-extrabold text-[#0F172A] mt-1">أرسل لنا استفسارك أو طلبك</h2>
                    <p class="text-xs text-[#64748B] mt-1">
                        يرجى ملء النموذج وسيتواصل معكم فريق زاد نيكس هاتفياً أو عبر واتساب فورياً.
                    </p>
                </div>

                @if(session('success'))
                <div class="mb-6 p-4 rounded-2xl bg-[#E8F8EE] border border-[#25D366]/30 flex items-start gap-3">
                    <x-icon name="check-circle" class="w-5 h-5 text-[#25D366] shrink-0 mt-0.5" />
                    <div>
                        <h4 class="text-xs font-bold text-[#1e824c]">تم استلام الرسالة بنجاح</h4>
                        <p class="text-xs text-[#2c7744] mt-0.5">{{ session('success') }}</p>
                    </div>
                </div>
                @endif

                @if($errors->any())
                <div class="mb-6 p-4 rounded-2xl bg-red-50 border border-red-200">
                    <ul class="list-disc list-inside text-xs text-red-600 space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <form action="{{ route('contact.submit') }}" method="POST" class="space-y-4">
                    @csrf

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <!-- Name -->
                        <div>
                            <label for="name" class="block text-xs font-bold text-[#0F172A] mb-1.5">
                                الاسم الكريم <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   id="name" 
                                   name="name" 
                                   value="{{ old('name', auth()->user()?->name ?? '') }}" 
                                   required 
                                   placeholder="أدخل اسمك الكريم" 
                                   class="w-full bg-[#F8FAFC] border border-[#E2E8F0] focus:border-[#0B2E75] focus:bg-white rounded-xl px-3.5 py-2.5 text-xs text-[#0F172A] placeholder-[#94A3B8] focus:outline-none transition">
                        </div>

                        <!-- Phone -->
                        <div>
                            <label for="phone" class="block text-xs font-bold text-[#0F172A] mb-1.5">
                                رقم الجوال <span class="text-red-500">*</span>
                            </label>
                            <div class="relative" dir="ltr">
                                <input type="tel" 
                                       id="phone" 
                                       name="phone" 
                                       value="{{ old('phone', auth()->user()?->phone ?? '') }}" 
                                       required 
                                       placeholder="05XXXXXXXX" 
                                       class="w-full bg-[#F8FAFC] border border-[#E2E8F0] focus:border-[#0B2E75] focus:bg-white rounded-xl px-3.5 py-2.5 text-xs text-[#0F172A] placeholder-[#94A3B8] focus:outline-none transition text-left">
                            </div>
                        </div>
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-xs font-bold text-[#0F172A] mb-1.5">
                            البريد الإلكتروني <span class="text-gray-400 font-normal">(اختياري)</span>
                        </label>
                        <input type="email" 
                               id="email" 
                               name="email" 
                               value="{{ old('email', auth()->user()?->email ?? '') }}" 
                               placeholder="example@domain.com" 
                               class="w-full bg-[#F8FAFC] border border-[#E2E8F0] focus:border-[#0B2E75] focus:bg-white rounded-xl px-3.5 py-2.5 text-xs text-[#0F172A] placeholder-[#94A3B8] focus:outline-none transition text-left" 
                               dir="ltr">
                    </div>

                    <!-- Subject -->
                    <div>
                        <label for="subject" class="block text-xs font-bold text-[#0F172A] mb-1.5">
                            نوع الاستفسار أو الطلب
                        </label>
                        <select id="subject" 
                                name="subject" 
                                class="w-full bg-[#F8FAFC] border border-[#E2E8F0] focus:border-[#0B2E75] focus:bg-white rounded-xl px-3.5 py-2.5 text-xs text-[#0F172A] focus:outline-none transition">
                            <option value="طلب مواد غذائية وتموين">طلب مواد غذائية وتموين</option>
                            <option value="طلب مشروبات وكراتين مياه بالجملة">طلب مشروبات وكراتين مياه بالجملة</option>
                            <option value="استفسار عن منظفات وكماليات منزلية">استفسار عن منظفات وكماليات منزلية</option>
                            <option value="متابعة طلب وتوصيل في مكة أو خارجها">متابعة طلب وتوصيل في مكة أو خارجها</option>
                            <option value="اقتراح أو ملاحظة عامة">اقتراح أو ملاحظة عامة</option>
                        </select>
                    </div>

                    <!-- Message -->
                    <div>
                        <label for="message" class="block text-xs font-bold text-[#0F172A] mb-1.5">
                            نص الرسالة أو تفاصيل الطلب <span class="text-red-500">*</span>
                        </label>
                        <textarea id="message" 
                                  name="message" 
                                  rows="4" 
                                  required 
                                  placeholder="اكتب استفسارك أو قائمة المواد والمنتجات التي ترغب في طلبها..." 
                                  class="w-full bg-[#F8FAFC] border border-[#E2E8F0] focus:border-[#0B2E75] focus:bg-white rounded-xl p-3.5 text-xs text-[#0F172A] placeholder-[#94A3B8] focus:outline-none transition leading-relaxed"></textarea>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" 
                            class="w-full bg-[#0B2E75] hover:bg-[#071E50] text-white font-bold text-xs sm:text-sm py-3.5 px-6 rounded-xl shadow-md transition duration-200 flex items-center justify-center gap-2">
                        <x-icon name="send" class="w-4 h-4 text-[#FBB404]" />
                        <span>إرسال الرسالة إلى إدارة زاد نيكس</span>
                    </button>
                </form>

            </div>

            <!-- Right Column: Interactive Google Maps & Legal Cards (6 cols) -->
            <div class="lg:col-span-6 space-y-6">

                <!-- Google Maps Frame -->
                <div class="bg-white rounded-3xl border border-[#E2E8F0] p-4 shadow-xs overflow-hidden">
                    <div class="flex items-center justify-between mb-3 px-2">
                        <div class="flex items-center gap-2">
                            <x-icon name="map-pin" class="w-4 h-4 text-[#0B2E75]" />
                            <h3 class="text-sm font-bold text-[#0F172A]">موقعنا على خريطة جوجل</h3>
                        </div>
                        <span class="text-[11px] text-[#64748B]">مكة المكرمة - أم الجود</span>
                    </div>
                    
                    <div class="rounded-2xl overflow-hidden border border-[#E2E8F0] h-[340px] w-full">
                        <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m12!1m3!1d316.6133110631134!2d39.70255475138687!3d21.419342605780784!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!5e1!3m2!1sar!2ssa!4v1795073236256!5m2!1sar!2ssa" 
                                width="100%" 
                                height="100%" 
                                style="border:0;" 
                                allowfullscreen="" 
                                loading="lazy" 
                                referrerpolicy="strict-origin-when-cross-origin"
                                title="موقع متجر زاد نيكس على خريطة جوجل">
                        </iframe>
                    </div>

                    <div class="mt-3 px-2 flex items-center justify-between text-xs">
                        <span class="text-[#64748B]">شارع عبد الله بن أبي بكر الصديق</span>
                        <a href="https://maps.google.com/?q=21.4193426,39.7025547" 
                           target="_blank" 
                           class="text-[#0B2E75] font-bold hover:underline flex items-center gap-1">
                            <span>فتح في خرائط Google</span>
                            <x-icon name="arrow-up-right" class="w-3.5 h-3.5" />
                        </a>
                    </div>
                </div>

                <!-- Trust and Legal Box -->
                <div class="bg-gradient-to-br from-[#0B2E75] to-[#071E50] rounded-3xl p-6 text-white shadow-md space-y-4">
                    <div class="flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-[#FBB404]"></span>
                        <span class="text-xs font-bold text-[#FBB404] uppercase">ضمان الجودة وسلامة الغذاء</span>
                    </div>
                    <h3 class="text-lg font-bold text-white">تسوق آمن وموثق رسمياً 100%</h3>

                    <ul class="space-y-3 text-xs text-gray-200">
                        <li class="flex items-start gap-2.5">
                            <x-icon name="shield-check" class="w-4 h-4 text-[#FBB404] shrink-0 mt-0.5" />
                            <span>سجل تجاري نشط رقم <strong>7055435114</strong> صادر عن وزارة التجارة.</span>
                        </li>
                        <li class="flex items-start gap-2.5">
                            <x-icon name="shield-check" class="w-4 h-4 text-[#FBB404] shrink-0 mt-0.5" />
                            <span>رخصة نشاط تجاري سارية رقم <strong>480526670333</strong> (أمانة العاصمة المقدسة - بلدي).</span>
                        </li>
                        <li class="flex items-start gap-2.5">
                            <x-icon name="shield-check" class="w-4 h-4 text-[#FBB404] shrink-0 mt-0.5" />
                            <span>شهادة سلامة معتمدة رقم <strong>101565890</strong> لنشاط البقالات ومحلات التجزئة.</span>
                        </li>
                        <li class="flex items-start gap-2.5">
                            <x-icon name="truck" class="w-4 h-4 text-[#FBB404] shrink-0 mt-0.5" />
                            <span>توصيل سريع ومبرد في مكة المكرمة ولجميع مدن المملكة.</span>
                        </li>
                        <li class="flex items-start gap-2.5">
                            <x-icon name="credit-card" class="w-4 h-4 text-[#FBB404] shrink-0 mt-0.5" />
                            <span>دفع إلكتروني آمن ومشفر عبر مدى، فيزا، أبل باي، وتمارا.</span>
                        </li>
                    </ul>

                    <div class="pt-4 border-t border-white/15 flex items-center justify-between text-xs">
                        <span class="text-gray-300">هل تحتاج مساعدة فورية؟</span>
                        <a href="https://wa.me/966580760890" target="_blank" class="text-[#FBB404] font-bold hover:underline flex items-center gap-1">
                            <x-icon name="whatsapp" class="w-4 h-4" />
                            <span>تحدث مع خدمة العملاء</span>
                        </a>
                    </div>
                </div>

                <!-- Instant FAQ Accordion -->
                <div class="bg-white rounded-3xl border border-[#E2E8F0] p-6 shadow-xs" x-data="{ openFaq: 1 }">
                    <h3 class="text-sm font-bold text-[#0F172A] mb-4 flex items-center gap-2">
                        <x-icon name="help-circle" class="w-4 h-4 text-[#0B2E75]" />
                        <span>الأسئلة الأكثر شيوعاً</span>
                    </h3>

                    <div class="space-y-2">
                        
                        <!-- FAQ 1 -->
                        <div class="border border-[#E2E8F0] rounded-xl overflow-hidden">
                            <button type="button" @click="openFaq = (openFaq === 1 ? null : 1)" class="w-full p-3 text-right text-xs font-bold text-[#0F172A] flex items-center justify-between hover:bg-[#F8FAFC] transition">
                                <span>كم يستغرق توصيل طلبات المواد الغذائية داخل مكة؟</span>
                                <x-icon name="chevron-down" class="w-3.5 h-3.5 text-[#94A3B8] transition-transform duration-200" ::class="openFaq === 1 ? 'rotate-180 text-[#0B2E75]' : ''" />
                            </button>
                            <div x-show="openFaq === 1" x-collapse class="p-3 pt-0 text-xs text-[#64748B] leading-relaxed border-t border-[#F8FAFC] bg-[#F8FAFC]/50">
                                نوفر خدمة التوصيل السريع داخل أحياء مكة المكرمة خلال ساعات من تأكيد الطلب، ولجميع مناطق المملكة خلال 24 إلى 48 ساعة.
                            </div>
                        </div>

                        <!-- FAQ 2 -->
                        <div class="border border-[#E2E8F0] rounded-xl overflow-hidden">
                            <button type="button" @click="openFaq = (openFaq === 2 ? null : 2)" class="w-full p-3 text-right text-xs font-bold text-[#0F172A] flex items-center justify-between hover:bg-[#F8FAFC] transition">
                                <span>هل المنتجات الغذائية والمشروبات مطابقة لاشتراطات سلامة الغذاء؟</span>
                                <x-icon name="chevron-down" class="w-3.5 h-3.5 text-[#94A3B8] transition-transform duration-200" ::class="openFaq === 2 ? 'rotate-180 text-[#0B2E75]' : ''" />
                            </button>
                            <div x-show="openFaq === 2" x-collapse class="p-3 pt-0 text-xs text-[#64748B] leading-relaxed border-t border-[#F8FAFC] bg-[#F8FAFC]/50">
                                نعم 100%، متجر زاد نيكس حاصل على شهادة سلامة معتمدة رقم 101565890، وجميع المنتجات والسلع التموينية تخضع لرقابة صارمة وتخزين صحي ومكيف ومطابق لهيئة الغذاء والدواء.
                            </div>
                        </div>

                        <!-- FAQ 3 -->
                        <div class="border border-[#E2E8F0] rounded-xl overflow-hidden">
                            <button type="button" @click="openFaq = (openFaq === 3 ? null : 3)" class="w-full p-3 text-right text-xs font-bold text-[#0F172A] flex items-center justify-between hover:bg-[#F8FAFC] transition">
                                <span>هل تتوفر أسعار جملة لكراتين المياه والمشروبات والمنظفات؟</span>
                                <x-icon name="chevron-down" class="w-3.5 h-3.5 text-[#94A3B8] transition-transform duration-200" ::class="openFaq === 3 ? 'rotate-180 text-[#0B2E75]' : ''" />
                            </button>
                            <div x-show="openFaq === 3" x-collapse class="p-3 pt-0 text-xs text-[#64748B] leading-relaxed border-t border-[#F8FAFC] bg-[#F8FAFC]/50">
                                نعم، نوفر عروض جملة وباقات توفيرية خاصة للمنازل والمساجد والشركات والمناسبات بأسعار منافسة مع خدمة التوصيل المباشر.
                            </div>
                        </div>

                    </div>
                </div>

            </div>

        </div>

    </div>
</x-app-layout>
