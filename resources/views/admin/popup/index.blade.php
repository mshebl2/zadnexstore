<x-admin-layout>
    <x-slot:title>إدارة عروض الإعلانات المفاجئة المتعددة والأكواد</x-slot:title>

    <div class="space-y-8 text-right" x-data="{ createModalOpen: false }">
        
        <!-- Header description & Add Button -->
        <div class="bg-white p-6 sm:p-8 rounded-2xl border border-[#E2E8F0] shadow-xs flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div>
                <h2 class="text-lg sm:text-xl font-bold text-[#242424] flex items-center gap-2">
                    <x-icon name="award" class="w-5 h-5 text-[#0B2E75]" />
                    <span>إعلانات العروض المفاجئة (Flash Offer Popups)</span>
                </h2>
                <p class="text-xs text-[#777777] mt-1 leading-relaxed">
                    يمكنك الآن إضافة وتفعيل عدة إعلانات وعروض مفاجئة مختلفة، تظهر للعملاء أثناء التصفح وتغلق تلقائياً بعد 5 ثوانٍ مع شريط عد تنازلي وزر نسخ كود الخصم.
                </p>
            </div>

            <button @click="createModalOpen = true" 
                    type="button" 
                    class="bg-[#0B2E75] hover:bg-[#071E50] text-white text-xs sm:text-sm font-bold px-5 py-2.5 rounded-xl shadow-sm transition flex items-center gap-2 flex-shrink-0">
                <x-icon name="plus" class="w-4 h-4" />
                <span>إضافة إعلان مفاجئ جديد</span>
            </button>
        </div>

        <!-- Create Popup Modal -->
        <div x-show="createModalOpen" 
             x-cloak 
             class="fixed inset-0 z-50 overflow-y-auto bg-black/60 backdrop-blur-xs flex items-center justify-center p-4">
            <div @click.away="createModalOpen = false" 
                 class="bg-white rounded-2xl max-w-2xl w-full p-6 sm:p-8 shadow-2xl border border-[#E2E8F0] text-right space-y-5 animate-fade-in">
                <div class="flex items-center justify-between pb-3 border-b border-[#E2E8F0]">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-lg bg-[#F8FAFC] text-[#0B2E75] flex items-center justify-center">
                            <x-icon name="award" class="w-4 h-4" />
                        </div>
                        <h3 class="font-bold text-base text-[#242424]">إنشاء إعلان عرض مفاجئ جديد</h3>
                    </div>
                    <button @click="createModalOpen = false" type="button" class="text-gray-400 hover:text-gray-600 p-1.5 rounded-lg">
                        <x-icon name="x" class="w-5 h-5" />
                    </button>
                </div>

                <form action="{{ route('admin.popup.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4" x-data="{ newPreview: null }">
                    @csrf

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-[#242424] mb-1.5">العنوان الرئيسي للإعلان: <span class="text-red-500">*</span></label>
                            <input type="text" name="title_ar" required placeholder="مثال: مفاجأة زاد نيكس الحصرية لك اليوم!" class="w-full bg-[#F8FAFC] border border-[#E2E8F0] focus:border-[#0B2E75] rounded-xl p-3 text-xs outline-none">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-[#242424] mb-1.5">شارة الإعلان العلوية (Badge):</label>
                            <input type="text" name="badge_ar" value="عرض استثنائي لفترة محدودة" placeholder="مثال: خصم خاص للزوار الجدد" class="w-full bg-[#F8FAFC] border border-[#E2E8F0] focus:border-[#0B2E75] rounded-xl p-3 text-xs outline-none">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-[#242424] mb-1.5">تفاصيل العرض الترويجي:</label>
                        <textarea name="subtitle_ar" rows="2" placeholder="احصل على خصم 10% إضافي على سلال المواد الغذائية والتموينية والمنظفات مع توصيل سريع..." class="w-full bg-[#F8FAFC] border border-[#E2E8F0] focus:border-[#0B2E75] rounded-xl p-3 text-xs outline-none"></textarea>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-[#242424] mb-1.5">كود الخصم المرفق:</label>
                            <input type="text" name="coupon_code" placeholder="مثل: ZADNEX10" class="w-full bg-[#F8FAFC] border border-[#E2E8F0] focus:border-[#0B2E75] rounded-xl p-3 text-xs font-mono font-bold text-[#0B2E75] outline-none">
                            @if($coupons->count() > 0)
                            <div class="mt-1 text-[10px] text-[#777777] flex flex-wrap gap-1">
                                <span>اختر كود:</span>
                                @foreach($coupons as $c)
                                <span class="cursor-pointer font-bold text-[#0B2E75] hover:underline" @click="$el.closest('form').querySelector('input[name=coupon_code]').value = '{{ $c->code }}'">
                                    {{ $c->code }}
                                </span>
                                @endforeach
                            </div>
                            @endif
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-[#242424] mb-1.5">ثواني التأخير قبل الظهور:</label>
                            <input type="number" name="delay_seconds" value="3" min="0" max="60" class="w-full bg-[#F8FAFC] border border-[#E2E8F0] focus:border-[#0B2E75] rounded-xl p-3 text-xs outline-none">
                            <span class="text-[10px] text-[#777777]">يظهر بعد 3 ثواني من فتح الموقع</span>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-[#242424] mb-1.5">مدة العرض التنازلي (ثواني):</label>
                            <input type="number" name="duration_seconds" value="5" min="2" max="60" class="w-full bg-[#F8FAFC] border border-[#E2E8F0] focus:border-[#0B2E75] rounded-xl p-3 text-xs outline-none">
                            <span class="text-[10px] text-[#777777]">يغلق تلقائياً بعد 5 ثواني</span>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-[#242424] mb-1.5">نص زر الإجراء:</label>
                            <input type="text" name="button_text_ar" value="استخدم الخصم وتسوّق الآن" class="w-full bg-[#F8FAFC] border border-[#E2E8F0] focus:border-[#0B2E75] rounded-xl p-3 text-xs outline-none">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-[#242424] mb-1.5">رابط التوجيه:</label>
                            <input type="text" name="button_url" value="{{ route('products.index') }}" class="w-full bg-[#F8FAFC] border border-[#E2E8F0] focus:border-[#0B2E75] rounded-xl p-3 text-xs outline-none dir-ltr text-left">
                        </div>
                    </div>

                    <!-- Image Upload from Mobile Camera / Gallery -->
                    <div class="space-y-2 pt-2 border-t border-[#E2E8F0]">
                        <label class="block text-xs font-bold text-[#242424]">صورة الإعلان (من الجوال أو الكاميرا أو رابط):</label>
                        
                        <div class="flex flex-col sm:flex-row items-center gap-3">
                            <label class="w-full sm:w-auto cursor-pointer flex items-center justify-center gap-2 bg-[#F8FAFC] hover:bg-[#F3ECE1] border border-dashed border-[#0B2E75] px-4 py-3 rounded-xl transition text-xs font-bold text-[#242424]">
                                <x-icon name="camera" class="w-4 h-4 text-[#0B2E75]" />
                                <span>التقاط صورة / اختيار من الاستديو</span>
                                <input type="file" 
                                       name="image_file" 
                                       accept="image/*" 
                                       capture="environment"
                                       @change="const file = $event.target.files[0]; if(file) { newPreview = URL.createObjectURL(file); }"
                                       class="hidden">
                            </label>

                            <span class="text-[11px] text-[#777777]">أو الصق رابط صورة:</span>
                        </div>

                        <input type="url" name="image_url" placeholder="https://images.unsplash.com/..." class="w-full bg-[#F8FAFC] border border-[#E2E8F0] focus:border-[#0B2E75] rounded-xl p-2.5 text-xs outline-none dir-ltr text-left">

                        <!-- Live Preview -->
                        <div x-show="newPreview" x-cloak class="mt-2 relative rounded-xl overflow-hidden border border-[#E2E8F0] h-28 bg-gray-900 w-44">
                            <img :src="newPreview" alt="معاينة الإعلان" class="w-full h-full object-cover">
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-4 border-t border-[#E2E8F0]">
                        <button @click="createModalOpen = false" type="button" class="px-5 py-2.5 rounded-xl border border-gray-300 text-xs font-semibold text-gray-700 hover:bg-gray-50 transition">
                            إلغاء
                        </button>
                        <button type="submit" class="bg-[#0B2E75] hover:bg-[#071E50] text-white text-xs font-bold px-6 py-2.5 rounded-xl shadow-md transition">
                            إنشاء وتفعيل الإعلان
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- List of Flash Popups -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @forelse($popups as $popup)
            <div class="bg-white rounded-2xl border {{ $popup->is_active ? 'border-[#0B2E75] shadow-md ring-1 ring-[#0B2E75]/20' : 'border-[#E2E8F0] opacity-75' }} overflow-hidden flex flex-col justify-between" x-data="{ editPreview: null }">
                
                <!-- Preview Top Card -->
                <div class="relative bg-gradient-to-br from-[#1C1C1C] to-[#2D2D2D] text-white p-5">
                    <div class="flex items-center justify-between pb-3 border-b border-white/10">
                        <span class="bg-[#0B2E75] text-black text-[10px] font-bold px-2.5 py-0.5 rounded-full">
                            {{ $popup->badge_ar ?? 'عرض مفاجئ' }}
                        </span>

                        <div class="flex items-center gap-2">
                            <!-- Toggle Active Button -->
                            <form action="{{ route('admin.popup.toggle', $popup->id) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="text-xs px-2.5 py-1 rounded-full font-bold transition {{ $popup->is_active ? 'bg-emerald-500/20 text-emerald-300 border border-emerald-500/40' : 'bg-gray-600/40 text-gray-300 border border-gray-600' }}">
                                    {{ $popup->is_active ? '● مفعل حالياً' : '○ متوقف' }}
                                </button>
                            </form>
                        </div>
                    </div>

                    <div class="flex items-center gap-4 mt-3">
                        <img :src="editPreview || '{{ $popup->image_url }}'" alt="" class="w-16 h-16 rounded-xl object-cover border border-white/20 bg-black flex-shrink-0">
                        <div class="flex-1 min-w-0">
                            <h3 class="font-bold text-sm text-white truncate">{{ $popup->title_ar }}</h3>
                            <p class="text-[11px] text-gray-300 line-clamp-2 mt-0.5">{{ $popup->subtitle_ar }}</p>
                            @if($popup->coupon_code)
                            <div class="mt-2 inline-flex items-center gap-1.5 bg-[#F8FAFC]/10 border border-[#0B2E75]/40 px-2 py-0.5 rounded-md font-mono text-xs font-bold text-[#0B2E75]">
                                <span>كود الخصم:</span>
                                <span>{{ $popup->coupon_code }}</span>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Progress bar simulation -->
                    <div class="mt-4 pt-3 border-t border-white/10 flex items-center justify-between text-[11px] text-gray-400">
                        <span>يظهر بعد: {{ $popup->delay_seconds }} ثانية</span>
                        <span>يغلق تلقائياً بعد: {{ $popup->duration_seconds }} ثواني</span>
                    </div>
                </div>

                <!-- Edit Form -->
                <form action="{{ route('admin.popup.update', $popup->id) }}" method="POST" enctype="multipart/form-data" class="p-5 space-y-3 flex-1 flex flex-col justify-between">
                    @csrf
                    @method('PUT')

                    <div class="space-y-3">
                        <div>
                            <label class="block text-[11px] font-bold text-gray-700 mb-1">العنوان الرئيسي:</label>
                            <input type="text" name="title_ar" value="{{ $popup->title_ar }}" required class="w-full bg-[#F8FAFC] border border-[#E2E8F0] rounded-lg p-2 text-xs text-[#242424]">
                        </div>

                        <div>
                            <label class="block text-[11px] font-bold text-gray-700 mb-1">الشارة الترويجية:</label>
                            <input type="text" name="badge_ar" value="{{ $popup->badge_ar }}" class="w-full bg-[#F8FAFC] border border-[#E2E8F0] rounded-lg p-2 text-xs text-[#242424]">
                        </div>

                        <div>
                            <label class="block text-[11px] font-bold text-gray-700 mb-1">تفاصيل العرض الترويجي:</label>
                            <textarea name="subtitle_ar" rows="2" class="w-full bg-[#F8FAFC] border border-[#E2E8F0] rounded-lg p-2 text-xs text-[#242424]">{{ $popup->subtitle_ar }}</textarea>
                        </div>

                        <div class="grid grid-cols-3 gap-2">
                            <div>
                                <label class="block text-[10px] font-bold text-gray-700 mb-1">كود الخصم:</label>
                                <input type="text" name="coupon_code" value="{{ $popup->coupon_code }}" class="w-full bg-[#F8FAFC] border border-[#E2E8F0] rounded-lg p-1.5 text-xs font-mono font-bold text-[#0B2E75]">
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-gray-700 mb-1">تأخير الظهور:</label>
                                <input type="number" name="delay_seconds" value="{{ $popup->delay_seconds }}" class="w-full bg-[#F8FAFC] border border-[#E2E8F0] rounded-lg p-1.5 text-xs">
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-gray-700 mb-1">مدة الإغلاق:</label>
                                <input type="number" name="duration_seconds" value="{{ $popup->duration_seconds }}" class="w-full bg-[#F8FAFC] border border-[#E2E8F0] rounded-lg p-1.5 text-xs">
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-2">
                            <div>
                                <label class="block text-[10px] font-bold text-gray-700 mb-1">نص الزر:</label>
                                <input type="text" name="button_text_ar" value="{{ $popup->button_text_ar }}" class="w-full bg-[#F8FAFC] border border-[#E2E8F0] rounded-lg p-1.5 text-xs">
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-gray-700 mb-1">رابط الزر:</label>
                                <input type="text" name="button_url" value="{{ $popup->button_url }}" class="w-full bg-[#F8FAFC] border border-[#E2E8F0] rounded-lg p-1.5 text-xs dir-ltr text-left">
                            </div>
                        </div>

                        <!-- Change photo from mobile -->
                        <div>
                            <label class="block text-[11px] font-bold text-gray-700 mb-1">تغيير الصورة من الجوال / الكاميرا:</label>
                            <label class="cursor-pointer flex items-center justify-center gap-2 bg-[#F8FAFC] hover:bg-[#F3ECE1] border border-[#E2E8F0] p-2 rounded-lg text-[11px] font-bold text-[#242424] transition">
                                <x-icon name="camera" class="w-3.5 h-3.5 text-[#0B2E75]" />
                                <span>رفع صورة جديدة من الجوال</span>
                                <input type="file" 
                                       name="image_file" 
                                       accept="image/*" 
                                       capture="environment" 
                                       @change="const f = $event.target.files[0]; if(f) editPreview = URL.createObjectURL(f);" 
                                       class="hidden">
                            </label>
                        </div>
                    </div>

                    <!-- Footer Actions -->
                    <div class="pt-3 border-t border-[#E2E8F0] flex items-center justify-between">
                        <label class="flex items-center gap-2 text-xs font-semibold text-[#242424] cursor-pointer">
                            <input type="checkbox" name="is_active" value="1" {{ $popup->is_active ? 'checked' : '' }} class="text-[#0B2E75] rounded">
                            <span>مفعل</span>
                        </label>

                        <button type="submit" class="bg-[#0B2E75] hover:bg-[#071E50] text-white text-xs font-bold px-4 py-2 rounded-lg transition shadow-xs">
                            حفظ التعديل
                        </button>
                    </div>
                </form>

                <!-- Delete Action -->
                <div class="px-5 pb-4 pt-1 flex justify-end bg-gray-50/50 border-t border-gray-100">
                    <form action="{{ route('admin.popup.destroy', $popup->id) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من رغبتك في حذف هذا الإعلان المفاجئ؟');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-xs text-red-500 hover:text-red-700 hover:underline flex items-center gap-1">
                            <x-icon name="trash" class="w-3.5 h-3.5" />
                            <span>حذف الإعلان</span>
                        </button>
                    </form>
                </div>
            </div>
            @empty
            <div class="col-span-full py-12 text-center bg-white rounded-2xl border border-[#E2E8F0]">
                <x-icon name="award" class="w-12 h-12 text-gray-300 mx-auto mb-3" />
                <h4 class="font-bold text-gray-700 text-sm">لا توجد إعلانات عروض مفاجئة حالياً</h4>
                <p class="text-xs text-gray-500 mt-1">اضغط على زر "إضافة إعلان مفاجئ جديد" بالأعلى لإنشاء أول إعلان يظهر للمتسوقين</p>
            </div>
            @endforelse
        </div>
    </div>
</x-admin-layout>
