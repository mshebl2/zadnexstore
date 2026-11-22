<x-app-layout>
    <x-slot:title>من نحن | متجر زاد نيكس (ZADNEX) — وجهتكم للمواد الغذائية والتموينية والمنظفات بمكة</x-slot:title>
    <x-slot:description>تعرف على متجر زاد نيكس لتجارة التجزئة للمواد الغذائية، المشروبات، المنظفات والكماليات بمكة المكرمة. سجل تجاري نشط 7055435114 وشهادة سلامة معتمدة 101565890.</x-slot:description>

    <!-- Hero Header -->
    <div class="relative bg-gradient-to-b from-[#0B2E75] to-[#071E50] text-white py-14 sm:py-20 overflow-hidden">
        <div class="absolute inset-0 opacity-10 bg-[radial-gradient(#FBB404_1px,transparent_1px)] [background-size:24px_24px]"></div>
        
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-right">
            <nav class="text-xs text-white/70 mb-4 flex items-center gap-2">
                <a href="{{ route('home') }}" class="hover:text-[#FBB404] transition">الرئيسية</a>
                <span>/</span>
                <span class="text-white font-bold">من نحن</span>
            </nav>

            <div class="inline-flex items-center gap-2 bg-white/10 backdrop-blur-md px-3 py-1 rounded-full border border-white/15 text-xs text-[#FBB404] font-bold mb-4">
                <span class="w-2 h-2 rounded-full bg-[#FBB404] animate-pulse"></span>
                <span>متجر زاد نيكس | ZADNEX STORE</span>
            </div>

            <h1 class="text-3xl sm:text-5xl font-extrabold text-white tracking-tight leading-tight">
                زاد نيكس.. جودة تثق بها وتوفير يليق ببيتك
            </h1>
            <p class="text-sm sm:text-base text-gray-200 mt-4 max-w-3xl leading-relaxed">
                مؤسسة سعودية متخصصة في تجارة التجزئة وتوفير أرقى المنتجات الغذائية، المشروبات، المنظفات والكماليات المنزلية، انطلاقاً من العاصمة المقدسة مكة المكرمة لكافة مدن المملكة.
            </p>
        </div>
    </div>

    <!-- Main Content Container -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 sm:py-16 space-y-16 text-right">

        <!-- Section 1: About Zad Nex Brand Story -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 sm:gap-12 items-center">
            <div class="lg:col-span-7 space-y-5">
                <div class="flex items-center gap-2 text-xs font-bold text-[#0B2E75] uppercase tracking-wider">
                    <span class="w-2.5 h-2.5 rounded-full bg-[#FBB404]"></span>
                    <span>قصتنا ورؤيتنا</span>
                </div>
                <h2 class="text-2xl sm:text-4xl font-extrabold text-[#0F172A] leading-snug">
                    وجهتك اليومية المتكاملة لتسوق المواد الغذائية ومستلزمات الأسرة
                </h2>
                <div class="prose prose-sm text-gray-600 leading-relaxed space-y-3.5 text-xs sm:text-sm">
                    <p>
                        تأسس <strong class="text-[#0B2E75]">متجر زاد نيكس (ZADNEX STORE)</strong> برؤية واضحة تهدف إلى إعادة تعريف تجربة تسوق المواد الغذائية والمستلزمات المنزلية اليومية في المملكة العربية السعودية، من خلال تقديم تشكيلة منتقاة من أجود السلع التموينية والمشروبات والمنظفات التي تلبي احتياجات الأسرة والمستهلك بأعلى معايير النقاء والجودة وبأنسب الأسعار.
                    </p>
                    <p>
                        نحن نؤمن بأن الغذاء السليم والصحي هو أساس كل بيت، ولذلك نحرص في زاد نيكس على تطبيق أشد إجراءات ضبط الجودة وسلامة الغذاء والتخزين المبرد والمكيف، بما يضمن وصول كل منتج إلى مائدتكم بحالته الطازجة ومواصفاته القياسية.
                    </p>
                    <p>
                        يقع مقرنا ومعرضنا الرئيسي في <strong>مكة المكرمة - حي أم الجود - شارع عبد الله بن أبي بكر الصديق</strong>، ومنه ننطلق بخدمات التوصيل السريع والآمن إلى كافة أحياء مكة المكرمة وجميع مناطق ومدن المملكة العربية السعودية.
                    </p>
                </div>

                <!-- Quick Highlights Counters -->
                <div class="grid grid-cols-3 gap-4 pt-4 border-t border-[#E2E8F0]">
                    <div class="bg-white p-4 rounded-2xl border border-[#E2E8F0] text-center shadow-xs">
                        <span class="text-2xl sm:text-3xl font-black text-[#0B2E75] block">100%</span>
                        <span class="text-xs text-gray-500 font-medium">سلامة غذائية معتمدة</span>
                    </div>
                    <div class="bg-white p-4 rounded-2xl border border-[#E2E8F0] text-center shadow-xs">
                        <span class="text-2xl sm:text-3xl font-black text-[#FBB404] block">+500</span>
                        <span class="text-xs text-gray-500 font-medium">منتج غذائي وتمويني</span>
                    </div>
                    <div class="bg-white p-4 rounded-2xl border border-[#E2E8F0] text-center shadow-xs">
                        <span class="text-2xl sm:text-3xl font-black text-[#0B2E75] block">24-48h</span>
                        <span class="text-xs text-gray-500 font-medium">سرعة توصيل الطلبات</span>
                    </div>
                </div>
            </div>

            <!-- Brand Logo Visual Card -->
            <div class="lg:col-span-5">
                <div class="relative bg-gradient-to-br from-[#0B2E75] to-[#071E50] p-8 sm:p-10 rounded-3xl shadow-xl text-center border-4 border-white/20 overflow-hidden group">
                    <div class="absolute -top-12 -right-12 w-40 h-40 bg-[#FBB404]/20 rounded-full blur-2xl"></div>
                    <div class="relative z-10 flex flex-col items-center">
                        <div class="w-48 sm:w-56 h-auto p-4 bg-white/10 backdrop-blur-md rounded-2xl border border-white/20 shadow-lg mb-6 group-hover:scale-105 transition-transform duration-500">
                            <img src="{{ asset('images/logo.png') }}" alt="شعار متجر زاد نيكس - ZADNEX" class="w-full h-auto object-contain">
                        </div>
                        <h3 class="text-xl font-bold text-white">متجر زاد نيكس | ZADNEX</h3>
                        <p class="text-xs text-gray-200 mt-1">مواد غذائية • مشروبات • منظفات • كماليات</p>
                        
                        <div class="mt-6 pt-5 border-t border-white/15 w-full text-xs text-white/90 space-y-2 text-right">
                            <div class="flex items-center justify-between">
                                <span class="text-white/70">مالك المتجر:</span>
                                <span class="font-bold text-[#FBB404]">منال سالم فرج</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-white/70">تاريخ التأسيس (قيد السجل):</span>
                                <span class="font-mono font-bold text-white">20/10/2026م</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-white/70">السجل التجاري:</span>
                                <span class="font-mono font-bold text-[#FBB404]">7055435114</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-white/70">شهادة سلامة:</span>
                                <span class="font-mono font-bold text-[#FBB404]">101565890</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-white/70">النشاط (الأيزك):</span>
                                <span class="font-bold">471131 (البقالات)</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <!-- Section 2: Official Government Registrations & Safety Certificates -->
        <div class="bg-white rounded-3xl border border-[#E2E8F0] p-6 sm:p-10 shadow-xs">
            <div class="max-w-2xl text-right mb-8">
                <div class="flex items-center gap-2 text-xs font-bold text-[#0B2E75] uppercase tracking-wider">
                    <span class="w-2.5 h-2.5 rounded-full bg-[#FBB404]"></span>
                    <span>الاعتمادات والتراخيص الرسمية</span>
                </div>
                <h2 class="text-xl sm:text-3xl font-extrabold text-[#0F172A] mt-2">
                    منشأة سعودية مرخصة وموثقة رسمياً 100%
                </h2>
                <p class="text-xs sm:text-sm text-gray-600 mt-1.5 leading-relaxed">
                    نعمل بموجب التراخيص النظامية والأنظمة المعمول بها في المملكة العربية السعودية، ومسجلون لدى كافة الجهات المختصة لضمان أمان وسلامة تعاملاتكم.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                
                <!-- Card 1: Commercial Registration (وزارة التجارة) -->
                <div class="bg-[#F8FAFC] rounded-2xl p-6 border border-[#E2E8F0] hover:border-[#0B2E75] transition-all duration-300 flex flex-col justify-between space-y-5">
                    <div>
                        <div class="flex items-center justify-between mb-4">
                            <span class="bg-[#0B2E75] text-white text-[11px] font-bold px-3 py-1 rounded-full">
                                وزارة التجارة السعودية
                            </span>
                            <span class="bg-emerald-100 text-emerald-800 text-[10px] font-bold px-2.5 py-0.5 rounded-full flex items-center gap-1">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                سجل نشط
                            </span>
                        </div>

                        <h3 class="text-lg font-bold text-[#0F172A]">شهادة السجل التجاري</h3>
                        <p class="text-xs text-gray-600 mt-1">
                            سجل تجاري إلكتروني موثق صادر عن وزارة التجارة لـ <strong>متجر زاد نيكس</strong>.
                        </p>

                        <div class="mt-4 space-y-2.5 bg-white p-4 rounded-xl border border-[#E2E8F0] text-xs">
                            <div class="flex items-center justify-between">
                                <span class="text-gray-500">رقم السجل الموحد:</span>
                                <span class="font-mono font-bold text-[#0B2E75] text-sm" dir="ltr">7055435114</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-gray-500">اسم المنشأة:</span>
                                <span class="font-bold text-[#0F172A]">متجر زاد نيكس</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-gray-500">مالك المنشأة:</span>
                                <span class="font-bold text-[#0B2E75]">منال سالم فرج</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-gray-500">نوع الكيان:</span>
                                <span class="font-medium text-gray-800">مؤسسة فردية</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-gray-500">تاريخ التأسيس (قيد السجل):</span>
                                <span class="font-mono font-bold text-gray-800">20/10/2026م (1448/04/09هـ)</span>
                            </div>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-[#E2E8F0] flex items-center justify-between">
                        <a href="{{ asset('docs/cr-zadnex.pdf') }}" 
                           target="_blank" 
                           class="inline-flex items-center gap-2 bg-[#0B2E75] hover:bg-[#071E50] text-white text-xs font-bold px-4 py-2.5 rounded-xl transition">
                            <x-icon name="file-text" class="w-4 h-4" />
                            <span>معاينة السجل (PDF)</span>
                        </a>
                        <span class="text-[11px] text-gray-400 font-mono">CR: 7055435114</span>
                    </div>
                </div>

                <!-- Card 2: Commercial Activity License (أمانة العاصمة المقدسة - بلدي) -->
                <div class="bg-[#F8FAFC] rounded-2xl p-6 border border-[#E2E8F0] hover:border-emerald-600 transition-all duration-300 flex flex-col justify-between space-y-5">
                    <div>
                        <div class="flex items-center justify-between mb-4">
                            <span class="bg-emerald-700 text-white text-[11px] font-bold px-3 py-1 rounded-full">
                                أمانة العاصمة المقدسة (بلدي)
                            </span>
                            <span class="bg-emerald-100 text-emerald-800 text-[10px] font-bold px-2.5 py-0.5 rounded-full flex items-center gap-1">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                رخصة نشاط سارية
                            </span>
                        </div>

                        <h3 class="text-lg font-bold text-[#0F172A]">رخصة نشاط تجاري</h3>
                        <p class="text-xs text-gray-600 mt-1">
                            رخصة بلدية معتمدة من منصة بلدي (بلدية الحديبية) لنشاط التموينات والبقالات.
                        </p>

                        <div class="mt-4 space-y-2.5 bg-white p-4 rounded-xl border border-[#E2E8F0] text-xs">
                            <div class="flex items-center justify-between">
                                <span class="text-gray-500">رقم الرخصة الموحد:</span>
                                <span class="font-mono font-bold text-emerald-800 text-sm" dir="ltr">480526670333</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-gray-500">النشاط المعتمد:</span>
                                <span class="font-bold text-[#0F172A]">تموينات ضمن محطة وقود</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-gray-500">البلدية والحي:</span>
                                <span class="font-medium text-gray-800">بلدية الحديبية - حي أم الجود</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-gray-500">صلاحية الترخيص:</span>
                                <span class="font-mono font-bold text-gray-800" dir="ltr">حتى 1449/06/08هـ</span>
                            </div>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-[#E2E8F0] flex items-center justify-between">
                        <a href="{{ asset('docs/license-zadnex.pdf') }}" 
                           target="_blank" 
                           class="inline-flex items-center gap-2 bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-bold px-4 py-2.5 rounded-xl transition">
                            <x-icon name="file-text" class="w-4 h-4" />
                            <span>معاينة الرخصة (PDF)</span>
                        </a>
                        <span class="text-[11px] text-gray-400 font-mono">Lic: 480526670333</span>
                    </div>
                </div>

                <!-- Card 3: Food Safety & Civil Defense Certificate (سلامة) -->
                <div class="bg-[#F8FAFC] rounded-2xl p-6 border border-[#E2E8F0] hover:border-[#FBB404] transition-all duration-300 flex flex-col justify-between space-y-5">
                    <div>
                        <div class="flex items-center justify-between mb-4">
                            <span class="bg-[#FBB404] text-[#071E50] text-[11px] font-bold px-3 py-1 rounded-full">
                                الدفاع المدني (سلامة)
                            </span>
                            <span class="bg-emerald-100 text-emerald-800 text-[10px] font-bold px-2.5 py-0.5 rounded-full flex items-center gap-1">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                معتمد ومطابق
                            </span>
                        </div>

                        <h3 class="text-lg font-bold text-[#0F172A]">شهادة سلامة المنشأة والغذاء</h3>
                        <p class="text-xs text-gray-600 mt-1">
                            شهادة سلامة رسمية لنشاط تجزئة البقالات صادرة عن بوابة سلامة للدفاع المدني.
                        </p>

                        <div class="mt-4 space-y-2.5 bg-white p-4 rounded-xl border border-[#E2E8F0] text-xs">
                            <div class="flex items-center justify-between">
                                <span class="text-gray-500">رقم شهادة سلامة:</span>
                                <span class="font-mono font-bold text-[#0B2E75] text-sm" dir="ltr">101565890</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-gray-500">تصنيف الايزك:</span>
                                <span class="font-bold text-[#0F172A]">البقالات ومحلات التجزئة</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-gray-500">كود الايزك:</span>
                                <span class="font-mono font-medium text-gray-800">471131</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-gray-500">المدينة:</span>
                                <span class="font-medium text-gray-800">مكة المكرمة (حي أم الجود)</span>
                            </div>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-[#E2E8F0] flex items-center justify-between">
                        <a href="{{ asset('docs/safety-zadnex.pdf') }}" 
                           target="_blank" 
                           class="inline-flex items-center gap-2 bg-[#0F172A] hover:bg-black text-white text-xs font-bold px-4 py-2.5 rounded-xl transition">
                            <x-icon name="shield-check" class="w-4 h-4 text-[#FBB404]" />
                            <span>معاينة شهادة السلامة (PDF)</span>
                        </a>
                        <span class="text-[11px] text-gray-400 font-mono">Cert: 101565890</span>
                    </div>
                </div>

            </div>
        </div>


        <!-- Section 3: Our 4 Product Pillars -->
        <div class="space-y-8">
            <div class="text-center max-w-xl mx-auto">
                <span class="text-xs font-bold text-[#0B2E75] tracking-widest uppercase">أقسامنا ومنتجاتنا</span>
                <h2 class="text-2xl sm:text-3xl font-extrabold text-[#0F172A] mt-1">تنوع متكامل يلبي كافة الاحتياجات</h2>
                <div class="w-12 h-1 bg-[#FBB404] mx-auto mt-2 rounded-full"></div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                
                <!-- Pillar 1: مواد غذائية -->
                <div class="bg-white rounded-2xl p-6 border border-[#E2E8F0] hover:border-[#0B2E75] hover:shadow-md transition-all duration-300 group">
                    <div class="w-14 h-14 rounded-2xl bg-[#EFF6FF] text-[#0B2E75] flex items-center justify-center mb-5 group-hover:scale-110 transition-transform">
                        <x-icon name="shopping-bag" class="w-7 h-7" />
                    </div>
                    <h3 class="text-lg font-bold text-[#0F172A]">مواد غذائية تموينية</h3>
                    <p class="text-xs text-gray-600 mt-2 leading-relaxed">
                        أرز فاخر، زيوت نقية، بقوليات، مكرونة، صلصات، بهارات طازجة، ومعلبات منتقاة بعناية لضمان سلامة مائدة أسرتك.
                    </p>
                    <a href="{{ route('category.show', 'food-products') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-[#0B2E75] hover:text-[#FBB404] mt-4 transition">
                        <span>تصفح المواد الغذائية</span>
                        <x-icon name="arrow-left" class="w-3.5 h-3.5" />
                    </a>
                </div>

                <!-- Pillar 2: مشروبات -->
                <div class="bg-white rounded-2xl p-6 border border-[#E2E8F0] hover:border-[#0B2E75] hover:shadow-md transition-all duration-300 group">
                    <div class="w-14 h-14 rounded-2xl bg-[#FEF8E7] text-[#D97706] flex items-center justify-center mb-5 group-hover:scale-110 transition-transform">
                        <x-icon name="coffee" class="w-7 h-7" />
                    </div>
                    <h3 class="text-lg font-bold text-[#0F172A]">مشروبات وعصائر ومياه</h3>
                    <p class="text-xs text-gray-600 mt-2 leading-relaxed">
                        كراتين مياه شرب نقية، عصائر طبيعية طازجة، مشروبات غازية، قهوة عربية بالهيل، وشاي سيلاني فاخر بأسعار توفيرية.
                    </p>
                    <a href="{{ route('category.show', 'beverages') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-[#0B2E75] hover:text-[#FBB404] mt-4 transition">
                        <span>تصفح المشروبات</span>
                        <x-icon name="arrow-left" class="w-3.5 h-3.5" />
                    </a>
                </div>

                <!-- Pillar 3: منظفات -->
                <div class="bg-white rounded-2xl p-6 border border-[#E2E8F0] hover:border-[#0B2E75] hover:shadow-md transition-all duration-300 group">
                    <div class="w-14 h-14 rounded-2xl bg-[#EFF6FF] text-[#0B2E75] flex items-center justify-center mb-5 group-hover:scale-110 transition-transform">
                        <x-icon name="sparkles" class="w-7 h-7" />
                    </div>
                    <h3 class="text-lg font-bold text-[#0F172A]">منظفات ومطهرات</h3>
                    <p class="text-xs text-gray-600 mt-2 leading-relaxed">
                        مساحيق غسيل الملابس، سوائل الأطباق، مطهرات ومعقمات الأرضيات والأسطح ذات الفعالية العالية لبيئة منزلية صحية.
                    </p>
                    <a href="{{ route('category.show', 'detergents-cleaning') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-[#0B2E75] hover:text-[#FBB404] mt-4 transition">
                        <span>تصفح المنظفات</span>
                        <x-icon name="arrow-left" class="w-3.5 h-3.5" />
                    </a>
                </div>

                <!-- Pillar 4: كماليات -->
                <div class="bg-white rounded-2xl p-6 border border-[#E2E8F0] hover:border-[#0B2E75] hover:shadow-md transition-all duration-300 group">
                    <div class="w-14 h-14 rounded-2xl bg-[#FEF8E7] text-[#D97706] flex items-center justify-center mb-5 group-hover:scale-110 transition-transform">
                        <x-icon name="package" class="w-7 h-7" />
                    </div>
                    <h3 class="text-lg font-bold text-[#0F172A]">كماليات ومستلزمات البيت</h3>
                    <p class="text-xs text-gray-600 mt-2 leading-relaxed">
                        مناديل ورقية ناعمة، رول قصدير وأكياس حفظ الأطعمة، أكياس نفايات متينة، وسفريات وبلاستيكيات عملية للاستخدام اليومي.
                    </p>
                    <a href="{{ route('category.show', 'household-essentials') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-[#0B2E75] hover:text-[#FBB404] mt-4 transition">
                        <span>تصفح الكماليات</span>
                        <x-icon name="arrow-left" class="w-3.5 h-3.5" />
                    </a>
                </div>

            </div>
        </div>


        <!-- Section 4: Interactive Google Map Location & Physical Address -->
        <div class="bg-white rounded-3xl border border-[#E2E8F0] p-6 sm:p-10 shadow-xs">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                
                <div class="lg:col-span-5 space-y-5">
                    <div class="flex items-center gap-2 text-xs font-bold text-[#0B2E75] uppercase tracking-wider">
                        <span class="w-2.5 h-2.5 rounded-full bg-[#FBB404]"></span>
                        <span>مقرنا الجغرافي</span>
                    </div>
                    <h2 class="text-xl sm:text-3xl font-extrabold text-[#0F172A]">
                        زيارتكم تسعدنا بمكة المكرمة
                    </h2>
                    <p class="text-xs sm:text-sm text-gray-600 leading-relaxed">
                        يسعدنا استقبالكم في فرعنا بالعاصمة المقدسة أو طلب توصيل فوري لكافة احتياجاتكم مباشرة إلى باب بيتك.
                    </p>

                    <div class="space-y-3 pt-2 text-xs">
                        <div class="flex items-start gap-3 p-3.5 rounded-xl bg-[#F8FAFC] border border-[#E2E8F0]">
                            <x-icon name="map-pin" class="w-5 h-5 text-[#0B2E75] shrink-0 mt-0.5" />
                            <div>
                                <strong class="text-[#0F172A] block mb-0.5">العنوان الوطني المعتمد:</strong>
                                <span class="text-gray-600">المملكة العربية السعودية، مكة المكرمة، حي أم الجود، شارع عبد الله بن أبي بكر الصديق</span>
                            </div>
                        </div>

                        <div class="flex items-center gap-3 p-3.5 rounded-xl bg-[#F8FAFC] border border-[#E2E8F0]">
                            <x-icon name="phone" class="w-5 h-5 text-[#0B2E75] shrink-0" />
                            <div>
                                <strong class="text-[#0F172A] block mb-0.5">الاتصال والواتساب المباشر:</strong>
                                <a href="tel:+966580760890" class="text-[#0B2E75] font-bold hover:underline font-mono" dir="ltr">+966 58 076 0890</a>
                            </div>
                        </div>

                        <div class="flex items-center gap-3 p-3.5 rounded-xl bg-[#F8FAFC] border border-[#E2E8F0]">
                            <x-icon name="mail" class="w-5 h-5 text-[#0B2E75] shrink-0" />
                            <div>
                                <strong class="text-[#0F172A] block mb-0.5">البريد الإلكتروني الرسمي:</strong>
                                <a href="mailto:info@zadnexstore.com" class="text-[#0B2E75] font-bold hover:underline font-mono" dir="ltr">info@zadnexstore.com</a>
                            </div>
                        </div>
                    </div>

                    <div class="pt-3 flex items-center gap-3">
                        <a href="https://wa.me/966580760890?text={{ urlencode('مرحباً متجر زاد نيكس، أود الاستفسار عن المنتجات وخدمات التوصيل.') }}" 
                           target="_blank" 
                           class="flex-1 bg-[#25D366] hover:bg-[#1eb855] text-white text-xs font-bold py-3 px-4 rounded-xl flex items-center justify-center gap-2 shadow-sm transition">
                            <x-icon name="whatsapp" class="w-4 h-4" />
                            <span>محادثة واتساب فورية</span>
                        </a>
                        <a href="{{ route('contact') }}" 
                           class="bg-[#0B2E75] hover:bg-[#071E50] text-white text-xs font-bold py-3 px-5 rounded-xl transition">
                            <span>صفحة التواصل</span>
                        </a>
                    </div>
                </div>

                <!-- Google Maps Embed Frame -->
                <div class="lg:col-span-7 rounded-2xl overflow-hidden border border-[#E2E8F0] shadow-sm bg-gray-100 min-h-[350px]">
                    <div class="w-full h-full aspect-[4/3] sm:aspect-video lg:aspect-auto lg:h-[450px]">
                        <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m12!1m3!1d316.6133110631134!2d39.70255475138687!3d21.419342605780784!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!5e1!3m2!1sar!2ssa!4v1795073236256!5m2!1sar!2ssa" 
                                width="100%" 
                                height="100%" 
                                style="border:0;" 
                                allowfullscreen="" 
                                loading="lazy" 
                                referrerpolicy="strict-origin-when-cross-origin"
                                title="موقع متجر زاد نيكس على خريطة جوجل بمكة المكرمة">
                        </iframe>
                    </div>
                </div>

            </div>
        </div>

    </div>
</x-app-layout>
