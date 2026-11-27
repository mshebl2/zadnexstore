<x-admin-layout>
    <x-slot:title>إعدادات المتجر والهوية والامتثال</x-slot:title>

    <div class="max-w-5xl mx-auto text-right space-y-8">
        
        <div class="bg-white rounded-2xl border border-[#E2E8F0] p-6 sm:p-8 shadow-xs">
            <div class="border-b border-[#E2E8F0] pb-4 mb-6 flex items-center justify-between">
                <div>
                    <h2 class="text-base font-bold text-[#242424]">بيانات المتجر والامتثال والتواصل</h2>
                    <p class="text-xs text-[#777777] mt-0.5">تحكم في كافة معلومات المتجر الرسمية وأرقام التواصل وشبكات التواصل</p>
                </div>
                <div class="flex flex-wrap items-center gap-2">
                    <a href="{{ route('admin.profile') }}" class="bg-[#242424] text-[#0B2E75] hover:bg-black hover:text-white px-3.5 py-1.5 rounded-xl text-xs font-bold transition flex items-center gap-1.5 shadow-xs">
                        <x-icon name="user" class="w-3.5 h-3.5" />
                        <span>بيانات الدخول وكلمة المرور &larr;</span>
                    </a>
                    <a href="{{ route('admin.popup.index') }}" class="bg-[#F8FAFC] border border-[#0B2E75] text-[#0B2E75] hover:bg-[#0B2E75] hover:text-white px-3.5 py-1.5 rounded-xl text-xs font-bold transition flex items-center gap-1.5">
                        <x-icon name="award" class="w-3.5 h-3.5" />
                        <span>إعدادات الإعلانات والعروض المفاجئة &larr;</span>
                    </a>
                </div>
            </div>

            <form action="{{ route('admin.settings.update') }}" method="POST" class="space-y-6">
                @csrf

                <!-- Section 0: Store Launch & Preparation Notice (إشعار تجهيز المتجر المبدئي) -->
                <div id="launch-notice-section" class="bg-gradient-to-br from-amber-50/60 via-white to-amber-50/30 rounded-2xl border-2 border-amber-200/90 p-5 sm:p-6 shadow-2xs">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-amber-200/60 pb-4 mb-5">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-amber-400 text-slate-950 flex items-center justify-center font-bold shadow-xs">
                                <x-icon name="bell" class="w-5 h-5 stroke-[2]" />
                            </div>
                            <div>
                                <h3 class="text-sm font-black text-slate-900 flex items-center gap-2">
                                    <span>إشعار تجهيز المتجر والإطلاق المبدئي للعملاء</span>
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-black {{ ($settings['store_launch_notice_active'] ?? '1') === '1' ? 'bg-emerald-100 text-emerald-800' : 'bg-gray-200 text-gray-700' }}">
                                        <span class="w-1.5 h-1.5 rounded-full {{ ($settings['store_launch_notice_active'] ?? '1') === '1' ? 'bg-emerald-500 animate-pulse' : 'bg-gray-400' }}"></span>
                                        {{ ($settings['store_launch_notice_active'] ?? '1') === '1' ? 'مفعل ويظهر للعملاء حالياً' : 'معطل ومخفي' }}
                                    </span>
                                </h3>
                                <p class="text-xs text-slate-500 mt-0.5">يظهر بمجرد دخول العملاء للمتجر لتنبيههم بأن المتجر قيد التجهيز لاستقبال طلباتهم</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-2 self-end sm:self-auto">
                            <a href="{{ route('home', ['preview_notice' => 1]) }}" target="_blank" class="bg-white hover:bg-amber-50 border border-amber-300 text-amber-900 px-3 py-1.5 rounded-xl text-xs font-bold transition flex items-center gap-1 shadow-2xs">
                                <span>معاينة حية في المتجر</span>
                                <x-icon name="arrow-up-right" class="w-3.5 h-3.5" />
                            </a>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <!-- Activation Checkbox -->
                        <div class="bg-white p-3.5 rounded-xl border border-amber-200 flex items-center justify-between">
                            <label for="store_launch_notice_active" class="flex items-center gap-3 cursor-pointer select-none">
                                <input type="checkbox" id="store_launch_notice_active" name="store_launch_notice_active" value="1" {{ ($settings['store_launch_notice_active'] ?? '1') === '1' ? 'checked' : '' }} class="w-4 h-4 text-[#0B2E75] rounded border-gray-300 focus:ring-[#0B2E75]">
                                <div>
                                    <span class="block text-xs font-black text-slate-900">تفعيل ظهور الإشعار المبدئي للعملاء بمجرد الدخول</span>
                                    <span class="block text-[11px] text-slate-500">عند تفعيله يظهر إشعار التجهيز تلقائياً لجميع زوار المتجر مع إمكانية التصفح</span>
                                </div>
                            </label>
                            <span class="text-xs font-bold {{ ($settings['store_launch_notice_active'] ?? '1') === '1' ? 'text-emerald-600' : 'text-gray-400' }}">
                                {{ ($settings['store_launch_notice_active'] ?? '1') === '1' ? 'نشط' : 'متوقف' }}
                            </span>
                        </div>

                        <!-- Core Notice Text (The exact requested text) -->
                        <div>
                            <label class="block text-xs font-bold text-slate-900 mb-1.5">
                                نص الإشعار الرئيسي البارز <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="store_launch_notice_text" value="{{ $settings['store_launch_notice_text'] ?? 'المتجر قيد التجهيز لاستقبال طلباتكم بما يليق بكم' }}" required class="w-full bg-white border-2 border-amber-300 rounded-xl p-2.5 text-xs sm:text-sm font-bold text-slate-900 focus:outline-none focus:border-[#0B2E75] shadow-2xs">
                            <span class="text-[10px] text-slate-500 block mt-1">هذا هو النص الأساسي الذي يظهر بخط بارز وواضح للعملاء.</span>
                        </div>

                        <!-- Title and Badge -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-900 mb-1.5">عنوان الإشعار الترحيبي</label>
                                <input type="text" name="store_launch_notice_title" value="{{ $settings['store_launch_notice_title'] ?? 'مرحباً بكم في متجر زاد نيكس' }}" class="w-full bg-white border border-[#E2E8F0] rounded-xl p-2.5 text-xs text-slate-900 focus:outline-none focus:border-[#0B2E75]">
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-900 mb-1.5">الشارة التوضيحية العلوية (Badge)</label>
                                <input type="text" name="store_launch_notice_badge" value="{{ $settings['store_launch_notice_badge'] ?? 'تنبيه رسمي • قيد التجهيز' }}" class="w-full bg-white border border-[#E2E8F0] rounded-xl p-2.5 text-xs text-slate-900 focus:outline-none focus:border-[#0B2E75]">
                            </div>
                        </div>

                        <!-- Subtitle Description -->
                        <div>
                            <label class="block text-xs font-bold text-slate-900 mb-1.5">النص التوضيحي الإضافي</label>
                            <textarea name="store_launch_notice_subtitle" rows="2" class="w-full bg-white border border-[#E2E8F0] rounded-xl p-2.5 text-xs text-slate-900 focus:outline-none focus:border-[#0B2E75]">{{ $settings['store_launch_notice_subtitle'] ?? 'يسعدنا زيارتكم! نعمل حالياً بكل شغف على إعداد وتجهيز أفضل تشكيلة من المواد الغذائية والمنظفات ومستلزماتكم بأعلى معايير الجودة والسلامة المعتمدة. ترقبوا انطلاقتنا الرسمية قريباً، ونسعد بتصفحكم لأقسام المتجر أو التواصل المباشر معنا.' }}</textarea>
                        </div>

                        <!-- Display Mode and Button Text -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-900 mb-1.5">طريقة العرض في واجهة المتجر</label>
                                <select name="store_launch_notice_display" class="w-full bg-white border border-[#E2E8F0] rounded-xl p-2.5 text-xs text-slate-900 focus:outline-none focus:border-[#0B2E75]">
                                    <option value="both" {{ ($settings['store_launch_notice_display'] ?? 'both') === 'both' ? 'selected' : '' }}>نافذة منبثقة واضحة فور الدخول + شريط تنبيه علوي ثابت (موصى به)</option>
                                    <option value="modal" {{ ($settings['store_launch_notice_display'] ?? 'both') === 'modal' ? 'selected' : '' }}>نافذة منبثقة فور الدخول فقط (Modal)</option>
                                    <option value="banner" {{ ($settings['store_launch_notice_display'] ?? 'both') === 'banner' ? 'selected' : '' }}>شريط تنبيه علوي ثابت فقط (Banner)</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-900 mb-1.5">نص زر المتابعة والتصفح</label>
                                <input type="text" name="store_launch_notice_button_text" value="{{ $settings['store_launch_notice_button_text'] ?? 'تصفح المتجر الآن' }}" class="w-full bg-white border border-[#E2E8F0] rounded-xl p-2.5 text-xs text-slate-900 focus:outline-none focus:border-[#0B2E75]">
                            </div>
                        </div>

                        <!-- WhatsApp Contact Option -->
                        <div class="pt-2">
                            <label class="flex items-center gap-2.5 text-xs font-bold text-slate-800 cursor-pointer select-none">
                                <input type="checkbox" name="store_launch_notice_show_whatsapp" value="1" {{ ($settings['store_launch_notice_show_whatsapp'] ?? '1') === '1' ? 'checked' : '' }} class="w-4 h-4 text-[#0B2E75] rounded border-gray-300 focus:ring-[#0B2E75]">
                                <span>إظهار زر التواصل السريع عبر واتساب داخل إشعار التجهيز</span>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Section 1: Official Store Information -->
                <div>
                    <h3 class="text-xs font-bold text-[#0B2E75] uppercase tracking-wider mb-3">المعلومات الأساسية</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-[#242424] mb-1.5">اسم المتجر الرسمي</label>
                            <input type="text" name="store_name" value="{{ $settings['store_name'] ?? 'متجر زاد نيكس | ZADNEX STORE' }}" required class="w-full bg-[#F8FAFC] border border-[#E2E8F0] rounded-xl p-2.5 text-xs text-[#242424] focus:outline-none focus:border-[#0B2E75]">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-[#242424] mb-1.5">مالك المنشأة / المتجر</label>
                            <input type="text" name="store_owner" value="{{ $settings['store_owner'] ?? 'منال سالم فرج' }}" class="w-full bg-[#F8FAFC] border border-[#E2E8F0] rounded-xl p-2.5 text-xs text-[#242424] focus:outline-none focus:border-[#0B2E75]">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-[#242424] mb-1.5">تاريخ التأسيس (تاريخ قيد السجل)</label>
                            <input type="text" name="establishment_date" value="{{ $settings['establishment_date'] ?? '20/10/2026' }}" class="w-full bg-[#F8FAFC] border border-[#E2E8F0] rounded-xl p-2.5 text-xs text-[#242424] focus:outline-none focus:border-[#0B2E75]">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-[#242424] mb-1.5">سلوجان المتجر (Tagline)</label>
                            <input type="text" name="store_tagline" value="{{ $settings['store_tagline'] ?? 'متجر المواد الغذائية والمنظفات والكماليات' }}" class="w-full bg-[#F8FAFC] border border-[#E2E8F0] rounded-xl p-2.5 text-xs text-[#242424] focus:outline-none focus:border-[#0B2E75]">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-[#242424] mb-1.5">رقم السجل التجاري (CR Number)</label>
                            <input type="text" name="commercial_registry" value="{{ $settings['commercial_registry'] ?? '7055435114' }}" class="w-full bg-[#F8FAFC] border border-[#E2E8F0] rounded-xl p-2.5 text-xs text-[#242424] focus:outline-none focus:border-[#0B2E75]">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-[#242424] mb-1.5">رقم رخصة النشاط التجاري (بلدي)</label>
                            <input type="text" name="municipal_license" value="{{ $settings['municipal_license'] ?? '480526670333' }}" class="w-full bg-[#F8FAFC] border border-[#E2E8F0] rounded-xl p-2.5 text-xs text-[#242424] focus:outline-none focus:border-[#0B2E75]">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-[#242424] mb-1.5">شهادة سلامة الغذاء (الدفاع المدني)</label>
                            <input type="text" name="safety_certificate" value="{{ $settings['safety_certificate'] ?? '101565890' }}" class="w-full bg-[#F8FAFC] border border-[#E2E8F0] rounded-xl p-2.5 text-xs text-[#242424] focus:outline-none focus:border-[#0B2E75]">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-[#242424] mb-1.5">الرقم الضريبي (إن وجد)</label>
                            <input type="text" name="tax_number" value="{{ $settings['tax_number'] ?? '7055435114' }}" class="w-full bg-[#F8FAFC] border border-[#E2E8F0] rounded-xl p-2.5 text-xs text-[#242424] focus:outline-none focus:border-[#0B2E75]">
                        </div>

                        <div class="sm:col-span-2">
                            <label class="block text-xs font-bold text-[#242424] mb-1.5">العنوان ومقر المتجر بالمملكة</label>
                            <input type="text" name="address" value="{{ $settings['address'] ?? 'المملكة العربية السعودية، مكة المكرمة (العاصمة المقدسة)، حي أم الجود، شارع عبد الله بن أبي بكر الصديق' }}" class="w-full bg-[#F8FAFC] border border-[#E2E8F0] rounded-xl p-2.5 text-xs text-[#242424] focus:outline-none focus:border-[#0B2E75]">
                        </div>
                    </div>
                </div>

                <!-- Section 2: Contact & Support -->
                <div class="pt-4 border-t border-[#E2E8F0]">
                    <h3 class="text-xs font-bold text-[#0B2E75] uppercase tracking-wider mb-3">بيانات خدمة العملاء والاتصال</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-[#242424] mb-1.5">رقم الواتساب الرسمي</label>
                            <input type="text" name="whatsapp_number" value="{{ $settings['whatsapp_number'] ?? '+966501234567' }}" class="w-full bg-[#F8FAFC] border border-[#E2E8F0] rounded-xl p-2.5 text-xs text-[#242424] focus:outline-none focus:border-[#0B2E75] text-left" dir="ltr">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-[#242424] mb-1.5">رقم الاتصال الهاتفي</label>
                            <input type="text" name="contact_phone" value="{{ $settings['contact_phone'] ?? '+966501234567' }}" class="w-full bg-[#F8FAFC] border border-[#E2E8F0] rounded-xl p-2.5 text-xs text-[#242424] focus:outline-none focus:border-[#0B2E75] text-left" dir="ltr">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-[#242424] mb-1.5">البريد الإلكتروني للشكاوى</label>
                            <input type="email" name="contact_email" value="{{ $settings['contact_email'] ?? 'info@zadnexstore.com' }}" class="w-full bg-[#F8FAFC] border border-[#E2E8F0] rounded-xl p-2.5 text-xs text-[#242424] focus:outline-none focus:border-[#0B2E75] text-left" dir="ltr">
                        </div>
                    </div>
                </div>

                <!-- Section 3: Social Media Channels -->
                <div class="pt-4 border-t border-[#E2E8F0]">
                    <h3 class="text-xs font-bold text-[#0B2E75] uppercase tracking-wider mb-3">حسابات وسائل التواصل الاجتماعي</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-[#242424] mb-1.5">رابط حساب انستغرام</label>
                            <input type="url" name="instagram" value="{{ $settings['instagram'] ?? 'https://www.instagram.com/zadnexstore' }}" class="w-full bg-[#F8FAFC] border border-[#E2E8F0] rounded-xl p-2.5 text-xs text-[#242424] focus:outline-none focus:border-[#0B2E75] text-left" dir="ltr">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-[#242424] mb-1.5">رابط حساب سناب شات</label>
                            <input type="url" name="snapchat" value="{{ $settings['snapchat'] ?? 'https://snapchat.com/add/zadnexstore' }}" class="w-full bg-[#F8FAFC] border border-[#E2E8F0] rounded-xl p-2.5 text-xs text-[#242424] focus:outline-none focus:border-[#0B2E75] text-left" dir="ltr">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-[#242424] mb-1.5">رابط حساب تيك توك</label>
                            <input type="url" name="tiktok" value="{{ $settings['tiktok'] ?? 'https://www.tiktok.com/@zadnexstore' }}" class="w-full bg-[#F8FAFC] border border-[#E2E8F0] rounded-xl p-2.5 text-xs text-[#242424] focus:outline-none focus:border-[#0B2E75] text-left" dir="ltr">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-[#242424] mb-1.5">رابط حساب منصة إكس (تويتر)</label>
                            <input type="url" name="twitter" value="{{ $settings['twitter'] ?? 'https://x.com/zadnexstore' }}" class="w-full bg-[#F8FAFC] border border-[#E2E8F0] rounded-xl p-2.5 text-xs text-[#242424] focus:outline-none focus:border-[#0B2E75] text-left" dir="ltr">
                        </div>
                    </div>
                </div>

                <!-- Section 4: Announcement Bar -->
                <div class="pt-4 border-t border-[#E2E8F0]">
                    <h3 class="text-xs font-bold text-[#0B2E75] uppercase tracking-wider mb-3">شريط الإعلانات الترويجي العلوي</h3>
                    <div class="space-y-3">
                        <div>
                            <label class="block text-xs font-bold text-[#242424] mb-1.5">نص الإعلان العلوي</label>
                            <input type="text" name="announcement_text" value="{{ $settings['announcement_text'] ?? 'شحن مجاني لكافة مدن المملكة للطلبات فوق 299 ر.س — كود خصم 10%: ZADNEX10' }}" class="w-full bg-[#F8FAFC] border border-[#E2E8F0] rounded-xl p-2.5 text-xs text-[#242424] focus:outline-none focus:border-[#0B2E75]">
                        </div>

                        <label class="flex items-center gap-2 text-xs font-semibold text-[#242424] cursor-pointer">
                            <input type="checkbox" name="announcement_active" value="1" {{ ($settings['announcement_active'] ?? '1') === '1' ? 'checked' : '' }} class="text-[#0B2E75] rounded">
                            <span>تفعيل ظهور شريط الإعلانات بالواجهة العلوية للمتجر</span>
                        </label>
                    </div>
                </div>

                <!-- Section 5: Paymob Electronic Payment Gateway -->
                <div class="pt-6 border-t border-[#E2E8F0]" x-data="{ showApiKey: false, showSecretKey: false, showHmac: false }">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center gap-3">
                            <div class="h-8 px-2 bg-white rounded-lg border border-[#E2E8F0] flex items-center justify-center">
                                <img src="{{ asset('images/payments/mada.png') }}" alt="مدى" class="h-4.5 w-auto object-contain">
                            </div>
                            <div>
                                <h3 class="text-sm font-bold text-[#242424] flex items-center gap-2">
                                    <span class="w-2.5 h-2.5 rounded-full {{ ($settings['paymob_enabled'] ?? '0') === '1' ? 'bg-emerald-500' : 'bg-gray-400' }}"></span>
                                    <span>بوابة الدفع الإلكتروني (Paymob KSA — مدى، فيزا، أبل باي)</span>
                                </h3>
                                <p class="text-[11px] text-[#777777] mt-0.5">تحكم في تشغيل وتفعيل بوابات الدفع الإلكتروني وربط مفاتيح الربط البرمجي المشفرة</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="text-[10px] font-bold px-2.5 py-1 rounded-full {{ ($settings['paymob_enabled'] ?? '0') === '1' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-gray-100 text-gray-600 border border-gray-200' }}">
                                {{ ($settings['paymob_enabled'] ?? '0') === '1' ? '● البوابة مُفعّلة حالياً' : '○ البوابة مُعطّلة (وضع الإطلاق التجريبي)' }}
                            </span>
                            <label class="flex items-center gap-2 cursor-pointer bg-white px-3 py-1.5 rounded-xl border border-[#E2E8F0] shadow-2xs">
                                <input type="checkbox" name="paymob_enabled" value="1" {{ ($settings['paymob_enabled'] ?? '0') === '1' ? 'checked' : '' }} class="rounded text-[#0B2E75] focus:ring-[#0B2E75]">
                                <span class="text-xs font-bold text-[#242424]">تفعيل بوابة Paymob</span>
                            </label>
                        </div>
                    </div>

                    <div class="bg-[#F8FAFC] border border-[#E2E8F0] rounded-xl p-4 mb-4">
                        <label class="block text-xs font-bold text-[#242424] mb-2">حالة وضع الدفع</label>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <label class="flex items-center gap-2.5 p-3 rounded-lg border cursor-pointer transition {{ ($settings['paymob_mode'] ?? 'mock') === 'live' ? 'bg-white border-[#0B2E75] shadow-xs' : 'bg-transparent border-[#E2E8F0]' }}">
                                <input type="radio" name="paymob_mode" value="live" {{ ($settings['paymob_mode'] ?? 'mock') === 'live' ? 'checked' : '' }} class="text-[#0B2E75]">
                                <div>
                                    <div class="text-xs font-bold text-[#242424]">الدفع الفعلي المباشر (Live Mode)</div>
                                    <div class="text-[10px] text-[#777777]">معالجة البطاقات البنكية وسحب المبالغ الحقيقية للعملاء</div>
                                </div>
                            </label>
                            <label class="flex items-center gap-2.5 p-3 rounded-lg border cursor-pointer transition {{ ($settings['paymob_mode'] ?? 'mock') === 'mock' ? 'bg-white border-[#0B2E75] shadow-xs' : 'bg-transparent border-[#E2E8F0]' }}">
                                <input type="radio" name="paymob_mode" value="mock" {{ ($settings['paymob_mode'] ?? 'mock') === 'mock' ? 'checked' : '' }} class="text-[#0B2E75]">
                                <div>
                                    <div class="text-xs font-bold text-[#242424]">المحاكي التجريبي الآمن (Mock Mode)</div>
                                    <div class="text-[10px] text-[#777777]">محاكاة إتمام الطلبات وتجربة دورة الشراء دون سحب مالي</div>
                                </div>
                            </label>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <!-- Secret Key (sau_sk_...) -->
                        <div class="sm:col-span-2">
                            <div class="flex items-center justify-between mb-1.5">
                                <label class="text-xs font-bold text-[#242424]">المفتاح السري (Secret Key - sau_sk_...)</label>
                                <button type="button" @click="showSecretKey = !showSecretKey" class="text-[11px] text-[#0B2E75] hover:underline font-semibold flex items-center gap-1">
                                    <span x-text="showSecretKey ? 'إخفاء' : 'عرض المفتاح'"></span>
                                </button>
                            </div>
                            <div class="relative">
                                <input :type="showSecretKey ? 'text' : 'password'" name="paymob_secret_key" value="{{ $settings['paymob_secret_key'] ?? '' }}" placeholder="sau_sk_live_..." class="w-full bg-[#F8FAFC] border border-[#E2E8F0] rounded-xl p-2.5 text-xs text-[#242424] focus:outline-none focus:border-[#0B2E75] font-mono text-left" dir="ltr">
                            </div>
                        </div>

                        <!-- Public Key (sau_pk_...) -->
                        <div>
                            <label class="block text-xs font-bold text-[#242424] mb-1.5">المفتاح العام (Public Key - sau_pk_...)</label>
                            <input type="text" name="paymob_public_key" value="{{ $settings['paymob_public_key'] ?? '' }}" placeholder="sau_pk_live_..." class="w-full bg-[#F8FAFC] border border-[#E2E8F0] rounded-xl p-2.5 text-xs text-[#242424] focus:outline-none focus:border-[#0B2E75] font-mono text-left" dir="ltr">
                        </div>

                        <!-- HMAC Secret -->
                        <div>
                            <div class="flex items-center justify-between mb-1.5">
                                <label class="text-xs font-bold text-[#242424]">مفتاح التوقيع والتشفير (HMAC)</label>
                                <button type="button" @click="showHmac = !showHmac" class="text-[11px] text-[#0B2E75] hover:underline font-semibold flex items-center gap-1">
                                    <span x-text="showHmac ? 'إخفاء' : 'عرض'"></span>
                                </button>
                            </div>
                            <div class="relative">
                                <input :type="showHmac ? 'text' : 'password'" name="paymob_hmac_secret" value="{{ $settings['paymob_hmac_secret'] ?? '' }}" placeholder="مفتاح HMAC..." class="w-full bg-[#F8FAFC] border border-[#E2E8F0] rounded-xl p-2.5 text-xs text-[#242424] focus:outline-none focus:border-[#0B2E75] font-mono text-left" dir="ltr">
                            </div>
                        </div>

                        <!-- Integration ID: Cards -->
                        <div>
                            <label class="block text-xs font-bold text-[#242424] mb-1.5">معرف البطاقات (Mada / Visa / MC Integration ID)</label>
                            <input type="text" name="paymob_integration_id" value="{{ $settings['paymob_integration_id'] ?? '' }}" placeholder="مثال: 24343" class="w-full bg-[#F8FAFC] border border-[#E2E8F0] rounded-xl p-2.5 text-xs text-[#242424] focus:outline-none focus:border-[#0B2E75] font-mono text-left" dir="ltr">
                        </div>

                        <!-- Integration ID: Apple Pay -->
                        <div>
                            <label class="block text-xs font-bold text-[#242424] mb-1.5">معرف أبل باي (Apple Pay Integration ID)</label>
                            <input type="text" name="paymob_apple_pay_integration_id" value="{{ $settings['paymob_apple_pay_integration_id'] ?? '' }}" placeholder="مثال: 24340" class="w-full bg-[#F8FAFC] border border-[#E2E8F0] rounded-xl p-2.5 text-xs text-[#242424] focus:outline-none focus:border-[#0B2E75] font-mono text-left" dir="ltr">
                        </div>

                        <!-- API Key (JWT) -->
                        <div class="sm:col-span-2">
                            <div class="flex items-center justify-between mb-1.5">
                                <label class="text-xs font-bold text-[#242424]">مفتاح API الاحتياطي (JWT API Key)</label>
                                <button type="button" @click="showApiKey = !showApiKey" class="text-[11px] text-[#0B2E75] hover:underline font-semibold flex items-center gap-1">
                                    <span x-text="showApiKey ? 'إخفاء' : 'عرض المفتاح'"></span>
                                </button>
                            </div>
                            <div class="relative">
                                <input :type="showApiKey ? 'text' : 'password'" name="paymob_api_key" value="{{ $settings['paymob_api_key'] ?? '' }}" placeholder="مفتاح API..." class="w-full bg-[#F8FAFC] border border-[#E2E8F0] rounded-xl p-2.5 text-xs text-[#242424] focus:outline-none focus:border-[#0B2E75] font-mono text-left" dir="ltr">
                            </div>
                        </div>
                    </div>

                    <!-- Webhook Callback Helper -->
                    <div class="mt-4 p-3.5 bg-amber-50/70 border border-amber-200/80 rounded-xl text-right">
                        <div class="text-xs font-bold text-amber-900 mb-1 flex items-center gap-1.5">
                            <x-icon name="check-circle" class="w-3.5 h-3.5 text-amber-700" />
                            <span>رابط Webhook المعتمد لإشعارات الدفع الفورية (ضعه في إعدادات كل طريقة دفع في Paymob):</span>
                        </div>
                        <div class="flex items-center gap-2 mt-2" x-data="{ copied: false }">
                            <input type="text" readonly value="{{ url('/webhook/paymob') }}" class="w-full bg-white border border-amber-200 rounded-lg px-2.5 py-1.5 text-[11px] font-mono text-left text-[#242424]" dir="ltr">
                            <button type="button" @click="navigator.clipboard.writeText('{{ url('/webhook/paymob') }}'); copied = true; setTimeout(() => copied = false, 2000)" class="px-3 py-1.5 bg-amber-800 hover:bg-amber-900 text-white text-[11px] font-bold rounded-lg shrink-0 transition">
                                <span x-text="copied ? 'تم النسخ!' : 'نسخ الرابط'"></span>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Section 5.5: Tamara Buy Now Pay Later (بوابة تمارا للتقسيط) -->
                <div class="pt-6 border-t border-[#E2E8F0]" x-data="{ showTamaraToken: false, showTamaraNotif: false }">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center gap-3">
                            <div class="h-8 px-2 bg-white rounded-lg border border-[#E2E8F0] flex items-center justify-center">
                                <img src="{{ asset('images/payments/tamara.png') }}" alt="تمارا" class="h-5 w-auto object-contain">
                            </div>
                            <div>
                                <h3 class="text-sm font-bold text-[#242424] flex items-center gap-2">
                                    <span class="w-2.5 h-2.5 rounded-full {{ ($settings['tamara_enabled'] ?? '0') === '1' ? 'bg-emerald-500' : 'bg-gray-400' }}"></span>
                                    <span>بوابة تمارا للدفع بالتقسيط (Tamara BNPL - 4 دفعات)</span>
                                </h3>
                                <p class="text-[11px] text-[#777777] mt-0.5">تقسيم مشتريات العملاء على 4 دفعات بدون أي فوائد إضافية</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="text-[10px] font-bold px-2.5 py-1 rounded-full {{ ($settings['tamara_enabled'] ?? '0') === '1' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-gray-100 text-gray-600 border border-gray-200' }}">
                                {{ ($settings['tamara_enabled'] ?? '0') === '1' ? '● البوابة مُفعّلة حالياً' : '○ البوابة مُعطّلة' }}
                            </span>
                            <label class="flex items-center gap-2 cursor-pointer bg-white px-3 py-1.5 rounded-xl border border-[#E2E8F0] shadow-2xs">
                                <input type="checkbox" name="tamara_enabled" value="1" {{ ($settings['tamara_enabled'] ?? '0') === '1' ? 'checked' : '' }} class="rounded text-[#0B2E75] focus:ring-[#0B2E75]">
                                <span class="text-xs font-bold text-[#242424]">تفعيل بوابة تمارا</span>
                            </label>
                        </div>
                    </div>

                    <div class="bg-[#F8FAFC] border border-[#E2E8F0] rounded-xl p-4 mb-4">
                        <label class="block text-xs font-bold text-[#242424] mb-2">بيئة التشغيل لبوابة تمارا</label>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <label class="flex items-center gap-2.5 p-3 rounded-lg border cursor-pointer transition {{ ($settings['tamara_mode'] ?? 'sandbox') === 'live' ? 'bg-white border-[#0B2E75] shadow-xs' : 'bg-transparent border-[#E2E8F0]' }}">
                                <input type="radio" name="tamara_mode" value="live" {{ ($settings['tamara_mode'] ?? 'sandbox') === 'live' ? 'checked' : '' }} class="text-[#0B2E75]">
                                <div>
                                    <div class="text-xs font-bold text-[#242424]">الدفع الفعلي المباشر (Live Mode)</div>
                                    <div class="text-[10px] text-[#777777]">الإنتاج الحقيقي واستقبال مدفوعات العملاء عبر حساب تمارا التجاري</div>
                                </div>
                            </label>
                            <label class="flex items-center gap-2.5 p-3 rounded-lg border cursor-pointer transition {{ ($settings['tamara_mode'] ?? 'sandbox') !== 'live' ? 'bg-white border-[#0B2E75] shadow-xs' : 'bg-transparent border-[#E2E8F0]' }}">
                                <input type="radio" name="tamara_mode" value="sandbox" {{ ($settings['tamara_mode'] ?? 'sandbox') !== 'live' ? 'checked' : '' }} class="text-[#0B2E75]">
                                <div>
                                    <div class="text-xs font-bold text-[#242424]">الوضع التجريبي الآمن (Sandbox / Mock)</div>
                                    <div class="text-[10px] text-[#777777]">تجربة المحاكاة أو بيئة الاختبار الخاصة بشركاء تمارا</div>
                                </div>
                            </label>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <!-- Tamara API Token -->
                        <div class="sm:col-span-2">
                            <div class="flex items-center justify-between mb-1.5">
                                <label class="text-xs font-bold text-[#242424]">مفتاح API الخاص بتمارا (Tamara API Token / Bearer)</label>
                                <button type="button" @click="showTamaraToken = !showTamaraToken" class="text-[11px] text-[#0B2E75] hover:underline font-semibold flex items-center gap-1">
                                    <span x-text="showTamaraToken ? 'إخفاء' : 'عرض المفتاح'"></span>
                                </button>
                            </div>
                            <input :type="showTamaraToken ? 'text' : 'password'" name="tamara_api_token" value="{{ $settings['tamara_api_token'] ?? '' }}" placeholder="ضع مفتاح API Token المقدم من تمارا..." class="w-full bg-[#F8FAFC] border border-[#E2E8F0] rounded-xl p-2.5 text-xs text-[#242424] focus:outline-none focus:border-[#0B2E75] font-mono text-left" dir="ltr">
                        </div>

                        <!-- Tamara Notification Webhook Token -->
                        <div>
                            <div class="flex items-center justify-between mb-1.5">
                                <label class="text-xs font-bold text-[#242424]">مفتاح الإشعارات (Notification Token)</label>
                                <button type="button" @click="showTamaraNotif = !showTamaraNotif" class="text-[11px] text-[#0B2E75] hover:underline font-semibold flex items-center gap-1">
                                    <span x-text="showTamaraNotif ? 'إخفاء' : 'عرض'"></span>
                                </button>
                            </div>
                            <input :type="showTamaraNotif ? 'text' : 'password'" name="tamara_notification_token" value="{{ $settings['tamara_notification_token'] ?? '' }}" placeholder="Notification Token..." class="w-full bg-[#F8FAFC] border border-[#E2E8F0] rounded-xl p-2.5 text-xs text-[#242424] focus:outline-none focus:border-[#0B2E75] font-mono text-left" dir="ltr">
                        </div>

                        <!-- Tamara Public Key -->
                        <div>
                            <label class="block text-xs font-bold text-[#242424] mb-1.5">المفتاح العام للودجت (Public Key - اختياري)</label>
                            <input type="text" name="tamara_public_key" value="{{ $settings['tamara_public_key'] ?? '' }}" placeholder="Public Key..." class="w-full bg-[#F8FAFC] border border-[#E2E8F0] rounded-xl p-2.5 text-xs text-[#242424] focus:outline-none focus:border-[#0B2E75] font-mono text-left" dir="ltr">
                        </div>
                    </div>

                    <!-- Tamara Webhook Helper -->
                    <div class="mt-4 p-3.5 bg-purple-50/70 border border-purple-200/80 rounded-xl text-right">
                        <div class="text-xs font-bold text-purple-900 mb-1 flex items-center gap-1.5">
                            <x-icon name="check-circle" class="w-3.5 h-3.5 text-purple-700" />
                            <span>رابط Webhook المعتمد لتمارا (Notification Webhook URL):</span>
                        </div>
                        <div class="flex items-center gap-2 mt-2" x-data="{ copied: false }">
                            <input type="text" readonly value="{{ url('/webhook/tamara') }}" class="w-full bg-white border border-purple-200 rounded-lg px-2.5 py-1.5 text-[11px] font-mono text-left text-[#242424]" dir="ltr">
                            <button type="button" @click="navigator.clipboard.writeText('{{ url('/webhook/tamara') }}'); copied = true; setTimeout(() => copied = false, 2000)" class="px-3 py-1.5 bg-purple-800 hover:bg-purple-900 text-white text-[11px] font-bold rounded-lg shrink-0 transition">
                                <span x-text="copied ? 'تم النسخ!' : 'نسخ الرابط'"></span>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Section 5.6: Tabby Buy Now Pay Later (بوابة تابي للتقسيط) -->
                <div class="pt-6 border-t border-[#E2E8F0]" x-data="{ showTabbySecret: false }">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center gap-3">
                            <div class="h-8 px-2 bg-white rounded-lg border border-[#E2E8F0] flex items-center justify-center">
                                <img src="{{ asset('images/payments/tabby.png') }}" alt="تابي" class="h-5 w-auto object-contain">
                            </div>
                            <div>
                                <h3 class="text-sm font-bold text-[#242424] flex items-center gap-2">
                                    <span class="w-2.5 h-2.5 rounded-full {{ ($settings['tabby_enabled'] ?? '0') === '1' ? 'bg-emerald-500' : 'bg-gray-400' }}"></span>
                                    <span>بوابة تابي للدفع بالتقسيط (Tabby BNPL - 4 دفعات)</span>
                                </h3>
                                <p class="text-[11px] text-[#777777] mt-0.5">تقسيم مشتريات العملاء على 4 دفعات شهرية بدون أي فوائد</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="text-[10px] font-bold px-2.5 py-1 rounded-full {{ ($settings['tabby_enabled'] ?? '0') === '1' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-gray-100 text-gray-600 border border-gray-200' }}">
                                {{ ($settings['tabby_enabled'] ?? '0') === '1' ? '● البوابة مُفعّلة حالياً' : '○ البوابة مُعطّلة' }}
                            </span>
                            <label class="flex items-center gap-2 cursor-pointer bg-white px-3 py-1.5 rounded-xl border border-[#E2E8F0] shadow-2xs">
                                <input type="checkbox" name="tabby_enabled" value="1" {{ ($settings['tabby_enabled'] ?? '0') === '1' ? 'checked' : '' }} class="rounded text-[#0B2E75] focus:ring-[#0B2E75]">
                                <span class="text-xs font-bold text-[#242424]">تفعيل بوابة تابي</span>
                            </label>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <!-- Tabby Public Key -->
                        <div>
                            <label class="block text-xs font-bold text-[#242424] mb-1.5">المفتاح العام (Tabby Public Key - pk_...)</label>
                            <input type="text" name="tabby_public_key" value="{{ $settings['tabby_public_key'] ?? '' }}" placeholder="pk_test_... أو pk_live_..." class="w-full bg-[#F8FAFC] border border-[#E2E8F0] rounded-xl p-2.5 text-xs text-[#242424] focus:outline-none focus:border-[#0B2E75] font-mono text-left" dir="ltr">
                        </div>

                        <!-- Tabby Secret Key -->
                        <div>
                            <div class="flex items-center justify-between mb-1.5">
                                <label class="text-xs font-bold text-[#242424]">المفتاح السري (Tabby Secret Key - sk_...)</label>
                                <button type="button" @click="showTabbySecret = !showTabbySecret" class="text-[11px] text-[#0B2E75] hover:underline font-semibold flex items-center gap-1">
                                    <span x-text="showTabbySecret ? 'إخفاء' : 'عرض'"></span>
                                </button>
                            </div>
                            <input :type="showTabbySecret ? 'text' : 'password'" name="tabby_secret_key" value="{{ $settings['tabby_secret_key'] ?? '' }}" placeholder="sk_test_... أو sk_live_..." class="w-full bg-[#F8FAFC] border border-[#E2E8F0] rounded-xl p-2.5 text-xs text-[#242424] focus:outline-none focus:border-[#0B2E75] font-mono text-left" dir="ltr">
                        </div>
                    </div>
                </div>

                <!-- Section 5.7: WhatsApp Direct Order & Cash on Delivery (الطلب عبر واتساب والدفع عند الاستلام) -->
                <div class="pt-6 border-t border-[#E2E8F0]">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-emerald-500 text-white flex items-center justify-center font-bold text-sm">
                                💬
                            </div>
                            <div>
                                <h3 class="text-sm font-bold text-[#242424] flex items-center gap-2">
                                    <span class="w-2.5 h-2.5 rounded-full {{ ($settings['whatsapp_order_enabled'] ?? '1') === '1' ? 'bg-emerald-500' : 'bg-gray-400' }}"></span>
                                    <span>نظام الطلب عبر واتساب والدفع عند الاستلام (وضع الإطلاق التجريبي)</span>
                                </h3>
                                <p class="text-[11px] text-[#777777] mt-0.5">عند تفعيل هذا الخيار، يتم إنشاء الطلب في النظام وتوجيه العميل مباشرة لإرسال الفاتورة عبر واتساب لتأكيد التوصيل في مكة</p>
                            </div>
                        </div>
                        <span class="text-[10px] font-bold px-2.5 py-1 rounded-full {{ ($settings['whatsapp_order_enabled'] ?? '1') === '1' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-gray-100 text-gray-600 border border-gray-200' }}">
                            {{ ($settings['whatsapp_order_enabled'] ?? '1') === '1' ? '● مُفعّل (الوضع النشط حالياً)' : '○ مُعطّل' }}
                        </span>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 bg-[#F8FAFC] border border-[#E2E8F0] rounded-xl p-4">
                        <label class="flex items-center gap-3 p-3 bg-white rounded-lg border border-[#E2E8F0] cursor-pointer">
                            <input type="checkbox" name="whatsapp_order_enabled" value="1" {{ ($settings['whatsapp_order_enabled'] ?? '1') === '1' ? 'checked' : '' }} class="rounded text-[#0B2E75] focus:ring-[#0B2E75]">
                            <div>
                                <div class="text-xs font-bold text-[#242424]">تفعيل إرسال تفاصيل الطلب عبر واتساب</div>
                                <div class="text-[10px] text-[#777777]">فتح محادثة واتساب الإدارة مع رسالة مفصلة بالمنتجات والعنوان فور إتمام الطلب</div>
                            </div>
                        </label>

                        <label class="flex items-center gap-3 p-3 bg-white rounded-lg border border-[#E2E8F0] cursor-pointer">
                            <input type="checkbox" name="cod_enabled" value="1" {{ ($settings['cod_enabled'] ?? '1') === '1' ? 'checked' : '' }} class="rounded text-[#0B2E75] focus:ring-[#0B2E75]">
                            <div>
                                <div class="text-xs font-bold text-[#242424]">تفعيل الدفع عند الاستلام (نقداً أو مدى لمندوب التوصيل)</div>
                                <div class="text-[10px] text-[#777777]">التحصيل المالي عند باب العميل داخل مكة المكرمة</div>
                            </div>
                        </label>
                    </div>

                    <div class="mt-3 flex items-center justify-between p-3 bg-emerald-50/70 border border-emerald-200/80 rounded-xl text-xs">
                        <div class="flex items-center gap-2 text-emerald-900 font-semibold">
                            <x-icon name="phone" class="w-4 h-4 text-emerald-600" />
                            <span>رقم الواتساب المعتمد لاستقبال الطلبات:</span>
                            <span class="font-mono font-bold text-emerald-800" dir="ltr">+966 58 076 0890</span>
                        </div>
                        <a href="https://wa.me/966580760890" target="_blank" class="text-[11px] text-emerald-700 hover:underline font-bold">
                            اختبار فتح محادثة الواتساب &larr;
                        </a>
                    </div>
                </div>

                <!-- Section 6: MapIt Logistics Integration (شركة الشحن مابت) -->
                <div class="pt-6 border-t border-[#E2E8F0]" x-data="{ showMapitKey: false }">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h3 class="text-sm font-bold text-[#242424] flex items-center gap-2">
                                <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                                <span>الربط اللوجستي مع شركة الشحن (منصة مابت MapIt)</span>
                            </h3>
                            <p class="text-[11px] text-[#777777] mt-0.5">توليد بوالص الشحن التلقائية وتتبع مسار الطرود مع أرامكس وسمسا وسبل عبر منصة مابت</p>
                        </div>
                        <span class="text-[10px] font-bold px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200">
                            ● مابت متصل وجاهز للربط
                        </span>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <div class="flex items-center justify-between mb-1.5">
                                <label class="text-xs font-bold text-[#242424]">رمز مرور API لمنصة مابت (API Key / Passcode)</label>
                                <button type="button" @click="showMapitKey = !showMapitKey" class="text-[11px] text-[#0B2E75] hover:underline font-semibold flex items-center gap-1">
                                    <span x-text="showMapitKey ? 'إخفاء' : 'عرض الرمز'"></span>
                                </button>
                            </div>
                            <div class="relative">
                                <input :type="showMapitKey ? 'text' : 'password'" name="mapit_api_key" value="{{ $settings['mapit_api_key'] ?? '1D9BCV1-1MEMN2R-PKXJTDY-PSHM2Q8' }}" placeholder="رمز مرور API الخاص بمابت..." class="w-full bg-[#F8FAFC] border border-[#E2E8F0] rounded-xl p-2.5 text-xs text-[#242424] focus:outline-none focus:border-[#0B2E75] font-mono text-left" dir="ltr">
                            </div>
                        </div>

                        <!-- MapIt Webhook Box -->
                        <div class="p-3.5 bg-blue-50/70 border border-blue-200/80 rounded-xl text-right">
                            <div class="text-xs font-bold text-blue-900 mb-1 flex items-center gap-1.5">
                                <x-icon name="truck" class="w-3.5 h-3.5 text-blue-700" />
                                <span>رابط Webhook الخاص بمابت لتحديث حالات الشحنات تلقائياً (ضعه في خانة Webhook في مابت):</span>
                            </div>
                            <div class="flex items-center gap-2 mt-2" x-data="{ copied: false }">
                                <input type="text" readonly value="{{ url('/webhook/mapit') }}" class="w-full bg-white border border-blue-200 rounded-lg px-2.5 py-1.5 text-[11px] font-mono text-left text-[#242424]" dir="ltr">
                                <button type="button" @click="navigator.clipboard.writeText('{{ url('/webhook/mapit') }}'); copied = true; setTimeout(() => copied = false, 2000)" class="px-3 py-1.5 bg-blue-800 hover:bg-blue-900 text-white text-[11px] font-bold rounded-lg shrink-0 transition">
                                    <span x-text="copied ? 'تم النسخ!' : 'نسخ الرابط'"></span>
                                </button>
                            </div>
                            <p class="text-[10px] text-blue-700 mt-1.5">* ملاحظة: استبدل الرابط القديم المكتوب في لوحة مابت بهذا الرابط ثم اضغط تأكيد.</p>
                        </div>
                    </div>
                </div>

                <!-- Section 7: SEO & Google Search Console (الأرشفة ومحركات البحث) -->
                <div class="pt-6 border-t border-[#E2E8F0]">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h3 class="text-sm font-bold text-[#242424] flex items-center gap-2">
                                <span class="w-2.5 h-2.5 rounded-full bg-indigo-500"></span>
                                <span>تحسين محركات البحث والأرشفة التلقائية (SEO & Google Search Console)</span>
                            </h3>
                            <p class="text-[11px] text-[#777777] mt-0.5">أرشفة فورية لجميع المنتجات والأقسام، ربط جوجل سيرش كونسول، وإدارة الكلمات المفتاحية والميتا</p>
                        </div>
                        <a href="{{ url('/sitemap.xml') }}" target="_blank" class="text-[11px] font-bold px-3 py-1 rounded-full bg-indigo-50 text-indigo-700 border border-indigo-200 hover:bg-indigo-100 transition flex items-center gap-1">
                            <span>عرض خريطة الموقع (Sitemap)</span>
                            <span>&nearr;</span>
                        </a>
                    </div>

                    <div class="space-y-4">
                        <!-- Instant Auto-Indexing & Ping Box -->
                        <div class="p-4 bg-emerald-50/80 border border-emerald-200 rounded-xl flex flex-col sm:flex-row items-center justify-between gap-3 text-right">
                            <div class="space-y-1">
                                <div class="flex items-center gap-2">
                                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                    <span class="text-xs font-bold text-emerald-950">نظام الأرشفة الفوري التلقائي (IndexNow + Search Engine Ping) مفعل 100%</span>
                                </div>
                                <p class="text-[11px] text-emerald-800">
                                    يقوم النظام تلقائياً وبشكل آلي بإشعار محركات البحث (Google و Bing و Yandex) فور نشر أو تعديل أي منتج أو تصنيف لتسريع ظهوره في نتائج البحث فورياً.
                                </p>
                            </div>
                            <button type="submit" formaction="{{ route('admin.settings.seo.ping') }}" formmethod="POST" class="px-4 py-2 bg-emerald-700 hover:bg-emerald-800 text-white rounded-xl text-xs font-bold shadow-xs whitespace-nowrap transition flex items-center gap-1.5 shrink-0">
                                <x-icon name="share-2" class="w-3.5 h-3.5" />
                                <span>إرسال إشعار فوري للأرشفة الآن</span>
                            </button>
                        </div>

                        <!-- Dynamic Sitemap Info Box -->
                        <div class="p-3.5 bg-indigo-50/70 border border-indigo-200/80 rounded-xl text-right">
                            <div class="text-xs font-bold text-indigo-900 mb-1 flex items-center gap-1.5">
                                <x-icon name="check-circle" class="w-3.5 h-3.5 text-indigo-700" />
                                <span>رابط خريطة الموقع المباشرة (Sitemap XML) — يتم تحديثه تلقائياً فور نشر أي منتج جديد:</span>
                            </div>
                            <div class="flex items-center gap-2 mt-2" x-data="{ copied: false }">
                                <input type="text" readonly value="{{ url('/sitemap.xml') }}" class="w-full bg-white border border-indigo-200 rounded-lg px-2.5 py-1.5 text-[11px] font-mono text-left text-[#242424]" dir="ltr">
                                <button type="button" @click="navigator.clipboard.writeText('{{ url('/sitemap.xml') }}'); copied = true; setTimeout(() => copied = false, 2000)" class="px-3 py-1.5 bg-indigo-800 hover:bg-indigo-900 text-white text-[11px] font-bold rounded-lg shrink-0 transition">
                                    <span x-text="copied ? 'تم النسخ!' : 'نسخ الرابط'"></span>
                                </button>
                            </div>
                            <p class="text-[10px] text-indigo-700 mt-1.5 font-medium">
                                * انسخ هذا الرابط وضعه في Google Search Console داخل قسم (خرائط المواقع / Sitemaps) لأرشفة متجرك فورياً في نتائج بحث جوجل.
                            </p>
                        </div>

                        <!-- Google Site Verification Code -->
                        <div>
                            <label class="block text-xs font-bold text-[#242424] mb-1">
                                كود إثبات ملكية الموقع في جوجل (Google Site Verification Tag / Code)
                            </label>
                            <input type="text" name="google_site_verification" value="{{ $settings['google_site_verification'] ?? '' }}" placeholder="ضع كود جوجل مثل: 4xK9... أو كود الميتا كامل &lt;meta name=&quot;google-site-verification&quot; content=&quot;...&quot; /&gt;" class="w-full bg-[#F8FAFC] border border-[#E2E8F0] rounded-xl p-2.5 text-xs text-[#242424] focus:outline-none focus:border-[#0B2E75] font-mono text-left" dir="ltr">
                            <span class="text-[10px] text-[#777777] block mt-1">يتم حقن هذا الكود تلقائياً في ترويسة المتجر &lt;head&gt; لإثبات ملكيتك في Google Search Console.</span>
                        </div>

                        <!-- Meta Title & Meta Description -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-[#242424] mb-1">عنوان الميتا الافتراضي (Meta Title)</label>
                                <input type="text" name="meta_title" value="{{ $settings['meta_title'] ?? 'متجر زاد نيكس | ZADNEX STORE — مواد غذائية، مشروبات، منظفات، كماليات' }}" placeholder="متجر زاد نيكس | ZADNEX STORE — مواد غذائية، مشروبات، منظفات، كماليات" class="w-full bg-[#F8FAFC] border border-[#E2E8F0] rounded-xl p-2.5 text-xs text-[#242424] focus:outline-none focus:border-[#0B2E75]">
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-[#242424] mb-1">الكلمات المفتاحية للمتجر (Meta Keywords)</label>
                                <input type="text" name="meta_keywords" value="{{ $settings['meta_keywords'] ?? 'مواد غذائية, تموين, مشروبات, منظفات, كماليات, زاد نيكس, مكة المكرمة, بقالة الكترونية, المملكة العربية السعودية' }}" placeholder="مواد غذائية، مشروبات، منظفات، كماليات، مكة المكرمة..." class="w-full bg-[#F8FAFC] border border-[#E2E8F0] rounded-xl p-2.5 text-xs text-[#242424] focus:outline-none focus:border-[#0B2E75]">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-[#242424] mb-1">وصف الميتا الافتراضي للمتجر (Meta Description)</label>
                            <textarea name="meta_description" rows="2" placeholder="اكتب وصفاً جذاباً يظهر في نتائج بحث جوجل لمحركات البحث..." class="w-full bg-[#F8FAFC] border border-[#E2E8F0] rounded-xl p-2.5 text-xs text-[#242424] focus:outline-none focus:border-[#0B2E75]">{{ $settings['meta_description'] ?? 'متجر زاد نيكس — وجهتك الأولى لتسوق المواد الغذائية والمشروبات والمنظفات والكماليات بأعلى جودة وتوصيل سريع في مكة المكرمة وكافة أنحاء المملكة.' }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Section 8: Pixels & Tracking Platforms (بكسلات التتبع ومنصات الإعلانات) -->
                <div class="pt-6 border-t border-[#E2E8F0]">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h3 class="text-sm font-bold text-[#242424] flex items-center gap-2">
                                <span class="w-2.5 h-2.5 rounded-full bg-rose-500"></span>
                                <span>بكسلات التتبع ومنصات الإعلانات (Marketing Pixels & Advertising)</span>
                            </h3>
                            <p class="text-[11px] text-[#777777] mt-0.5">اربط بكسل فيسبوك/ميتا، تيك توك، سناب شات، إعلانات جوجل وجوجل تاج مانجر لتتبع التحويلات والمبيعات</p>
                        </div>
                        <span class="text-[10px] font-bold px-2.5 py-1 rounded-full bg-rose-50 text-rose-700 border border-rose-200">
                            تتبع ذكي للمبيعات والزيارات
                        </span>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <!-- Meta / Facebook Pixel -->
                        <div>
                            <label class="block text-xs font-bold text-[#242424] mb-1">معرف بكسل ميتا / فيسبوك (Meta Pixel ID)</label>
                            <input type="text" name="meta_pixel_id" value="{{ $settings['meta_pixel_id'] ?? '' }}" placeholder="مثال: 123456789012345" class="w-full bg-[#F8FAFC] border border-[#E2E8F0] rounded-xl p-2.5 text-xs text-[#242424] focus:outline-none focus:border-[#0B2E75] font-mono text-left" dir="ltr">
                        </div>

                        <!-- TikTok Pixel -->
                        <div>
                            <label class="block text-xs font-bold text-[#242424] mb-1">معرف بكسل تيك توك (TikTok Pixel ID)</label>
                            <input type="text" name="tiktok_pixel_id" value="{{ $settings['tiktok_pixel_id'] ?? '' }}" placeholder="مثال: C1234567890ABCD" class="w-full bg-[#F8FAFC] border border-[#E2E8F0] rounded-xl p-2.5 text-xs text-[#242424] focus:outline-none focus:border-[#0B2E75] font-mono text-left" dir="ltr">
                        </div>

                        <!-- Snapchat Pixel -->
                        <div>
                            <label class="block text-xs font-bold text-[#242424] mb-1">معرف بكسل سناب شات (Snapchat Pixel ID)</label>
                            <input type="text" name="snapchat_pixel_id" value="{{ $settings['snapchat_pixel_id'] ?? '' }}" placeholder="مثال: xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx" class="w-full bg-[#F8FAFC] border border-[#E2E8F0] rounded-xl p-2.5 text-xs text-[#242424] focus:outline-none focus:border-[#0B2E75] font-mono text-left" dir="ltr">
                        </div>

                        <!-- Google Ads Conversion ID -->
                        <div>
                            <label class="block text-xs font-bold text-[#242424] mb-1">معرف إعلانات جوجل (Google Ads Conversion ID)</label>
                            <input type="text" name="google_ads_id" value="{{ $settings['google_ads_id'] ?? '' }}" placeholder="مثال: AW-123456789" class="w-full bg-[#F8FAFC] border border-[#E2E8F0] rounded-xl p-2.5 text-xs text-[#242424] focus:outline-none focus:border-[#0B2E75] font-mono text-left" dir="ltr">
                        </div>

                        <!-- Google Analytics 4 (GA4) -->
                        <div>
                            <label class="block text-xs font-bold text-[#242424] mb-1">معرف إحصائيات جوجل (Google Analytics 4 - GA4)</label>
                            <input type="text" name="google_analytics_id" value="{{ $settings['google_analytics_id'] ?? '' }}" placeholder="مثال: G-XXXXXXXXXX" class="w-full bg-[#F8FAFC] border border-[#E2E8F0] rounded-xl p-2.5 text-xs text-[#242424] focus:outline-none focus:border-[#0B2E75] font-mono text-left" dir="ltr">
                        </div>

                        <!-- Google Tag Manager (GTM) -->
                        <div>
                            <label class="block text-xs font-bold text-[#242424] mb-1">معرف مدير علامات جوجل (Google Tag Manager - GTM)</label>
                            <input type="text" name="google_tag_manager_id" value="{{ $settings['google_tag_manager_id'] ?? '' }}" placeholder="مثال: GTM-XXXXXXX" class="w-full bg-[#F8FAFC] border border-[#E2E8F0] rounded-xl p-2.5 text-xs text-[#242424] focus:outline-none focus:border-[#0B2E75] font-mono text-left" dir="ltr">
                        </div>
                    </div>

                    <!-- Custom Header & Body Scripts -->
                    <div class="space-y-4 mt-4">
                        <div>
                            <label class="block text-xs font-bold text-[#242424] mb-1">
                                أكواد برمجية وتتبع إضافية داخل الهيدر (&lt;head&gt; Scripts)
                            </label>
                            <textarea name="custom_head_scripts" rows="3" placeholder="يمكنك وضع أي سكريبت مخصص لتويتر، بينترست، هوتجار، أو أكواد تحقق إضافية..." class="w-full bg-[#F8FAFC] border border-[#E2E8F0] rounded-xl p-2.5 text-xs text-[#242424] focus:outline-none focus:border-[#0B2E75] font-mono text-left" dir="ltr">{{ $settings['custom_head_scripts'] ?? '' }}</textarea>
                            <span class="text-[10px] text-[#777777] block mt-0.5">يتم حقن هذه الأكواد في نهاية وسم &lt;head&gt; في جميع صفحات المتجر.</span>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-[#242424] mb-1">
                                أكواد برمجية وتتبع إضافية في بداية الصفحة (&lt;body&gt; Scripts)
                            </label>
                            <textarea name="custom_body_scripts" rows="3" placeholder="أكواد &lt;noscript&gt; أو سكريبتات إضافية تتطلب التواجد في بداية &lt;body&gt;..." class="w-full bg-[#F8FAFC] border border-[#E2E8F0] rounded-xl p-2.5 text-xs text-[#242424] focus:outline-none focus:border-[#0B2E75] font-mono text-left" dir="ltr">{{ $settings['custom_body_scripts'] ?? '' }}</textarea>
                            <span class="text-[10px] text-[#777777] block mt-0.5">يتم حقن هذه الأكواد فوراً بعد فتح وسم &lt;body&gt;.</span>
                        </div>
                    </div>
                </div>

                <div class="pt-6 border-t border-[#E2E8F0]">
                    <button type="submit" class="bg-[#0B2E75] hover:bg-[#071E50] text-white text-xs font-bold px-8 py-3.5 rounded-xl shadow-md transition duration-200">
                        حفظ جميع التعديلات
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-admin-layout>

