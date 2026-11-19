<x-app-layout>
    <x-slot:title>إنشاء حساب جديد | متجر زاد نيكس</x-slot:title>
    <x-slot:description>انضم إلى نادي صفوة متجر زاد نيكس الفاخرة واستمتع بمتابعة طلباتك وبرنامج الإحالات والخصومات الحصرية.</x-slot:description>

    <div class="max-w-md mx-auto px-4 py-16 text-right">
        <div class="bg-white rounded-3xl border border-[#EDE6DA] p-8 sm:p-10 shadow-lg space-y-6">
            
            <div class="text-center space-y-2">
                <a href="{{ route('home') }}" class="inline-block">
                    <img src="{{ asset('images/logo-dark.png') }}" alt="متجر زاد نيكس" class="h-14 mx-auto object-contain">
                </a>
                <span class="inline-block px-3 py-1 rounded-full bg-[#FAF8F4] text-[#C5A059] border border-[#EDE6DA] text-[11px] font-bold">
                    نادي صفوة زاد نيكس
                </span>
                <h1 class="text-xl font-bold text-[#242424]">إنشاء حساب جديد</h1>
                <p class="text-xs text-[#777777]">سجّل الآن لتتمكن من متابعة طلباتك والانضمام لبرنامج الأرباح</p>
            </div>

            @if($errors->any())
            <div class="p-3 bg-red-50 border border-red-200 text-red-700 text-xs rounded-xl space-y-1">
                @foreach($errors->all() as $err)
                    <p>• {{ $err }}</p>
                @endforeach
            </div>
            @endif

            <form action="{{ route('register.submit') }}" method="POST" class="space-y-4">
                @csrf

                <!-- Name -->
                <div>
                    <label class="block text-xs font-bold text-[#242424] mb-1.5">الاسم الكريم <span class="text-red-500">*</span></label>
                    <input type="text" 
                           name="name" 
                           value="{{ old('name') }}" 
                           required 
                           placeholder="مثال: محمد بن فهد" 
                           class="w-full bg-[#FAF8F4] border border-[#EDE6DA] rounded-xl px-3.5 py-2.5 text-xs text-[#242424] focus:outline-none focus:border-[#C5A059]">
                </div>

                <!-- Email -->
                <div>
                    <label class="block text-xs font-bold text-[#242424] mb-1.5">البريد الإلكتروني <span class="text-red-500">*</span></label>
                    <input type="email" 
                           name="email" 
                           value="{{ old('email') }}" 
                           required 
                           placeholder="name@example.com" 
                           class="w-full bg-[#FAF8F4] border border-[#EDE6DA] rounded-xl px-3.5 py-2.5 text-xs text-[#242424] focus:outline-none focus:border-[#C5A059] text-left" 
                           dir="ltr">
                </div>

                <!-- Phone -->
                <div>
                    <label class="block text-xs font-bold text-[#242424] mb-1.5">رقم الجوال <span class="text-[10px] text-[#A59D92] font-normal">(اختياري)</span></label>
                    <input type="tel" 
                           name="phone" 
                           value="{{ old('phone') }}" 
                           placeholder="05XXXXXXXX" 
                           class="w-full bg-[#FAF8F4] border border-[#EDE6DA] rounded-xl px-3.5 py-2.5 text-xs text-[#242424] focus:outline-none focus:border-[#C5A059] text-left" 
                           dir="ltr">
                </div>

                <!-- Password -->
                <div>
                    <label class="block text-xs font-bold text-[#242424] mb-1.5">كلمة المرور <span class="text-red-500">*</span></label>
                    <input type="password" 
                           name="password" 
                           required 
                           placeholder="لا تقل عن 8 أحرف" 
                           class="w-full bg-[#FAF8F4] border border-[#EDE6DA] rounded-xl px-3.5 py-2.5 text-xs text-[#242424] focus:outline-none focus:border-[#C5A059]">
                </div>

                <!-- Password Confirmation -->
                <div>
                    <label class="block text-xs font-bold text-[#242424] mb-1.5">تأكيد كلمة المرور <span class="text-red-500">*</span></label>
                    <input type="password" 
                           name="password_confirmation" 
                           required 
                           placeholder="أعد كتابة كلمة المرور" 
                           class="w-full bg-[#FAF8F4] border border-[#EDE6DA] rounded-xl px-3.5 py-2.5 text-xs text-[#242424] focus:outline-none focus:border-[#C5A059]">
                </div>

                <!-- Referral Code (Optional) -->
                <div>
                    <label class="block text-xs font-bold text-[#242424] mb-1.5">
                        كود الإحالة / الدعوة 
                        <span class="text-[10px] text-[#C5A059] font-normal">(اختياري — إذا تمت دعوتك من صديق)</span>
                    </label>
                    <input type="text" 
                           name="referred_by" 
                           value="{{ old('referred_by', $referralCode ?? '') }}" 
                           placeholder="كود المسوّق (إن وجد)" 
                           class="w-full bg-[#FAF8F4] border border-[#EDE6DA] rounded-xl px-3.5 py-2.5 text-xs text-[#242424] font-mono focus:outline-none focus:border-[#C5A059] uppercase tracking-wider text-left" 
                           dir="ltr">
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full bg-[#C5A059] hover:bg-[#AF8B44] text-white font-bold text-xs py-3.5 px-4 rounded-xl shadow-md transition duration-200 flex items-center justify-center gap-2 cursor-pointer">
                        <span>إنشاء الحساب الآن</span>
                        <x-icon name="arrow-left" class="w-4 h-4" />
                    </button>
                </div>
            </form>

            <div class="text-center pt-2 border-t border-[#EDE6DA] text-xs text-[#777777]">
                <span>لديك حساب بالفعل؟</span>
                <a href="{{ route('login') }}" class="text-[#C5A059] font-bold hover:underline mr-1">
                    تسجيل الدخول هنا
                </a>
            </div>

        </div>
    </div>
</x-app-layout>

