<x-app-layout>
    <x-slot:title>إنشاء حساب جديد | متجر زاد نيكس</x-slot:title>
    <x-slot:description>انضم إلى عملاء متجر زاد نيكس واستمتع بمتابعة طلباتك وبرنامج المكافآت والخصومات الحصرية للمواد الغذائية والتموينية.</x-slot:description>

    <div class="max-w-md mx-auto px-4 py-16 text-right">
        <div class="bg-white rounded-3xl border border-slate-200 p-8 sm:p-10 shadow-xl space-y-6">
            
            <div class="text-center space-y-3">
                <a href="{{ route('home') }}" class="inline-block" title="متجر زاد نيكس">
                    <img src="{{ asset('images/logo.png') }}" alt="متجر زاد نيكس" class="h-16 mx-auto object-contain">
                </a>
                <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-[#0B2E75]/5 text-[#0B2E75] border border-[#0B2E75]/15 text-xs font-bold">
                    <x-icon name="check-circle" class="w-3.5 h-3.5 text-[#FBB404]" />
                    <span>حساب عملاء متجر زاد نيكس</span>
                </div>
                <h1 class="text-xl font-bold text-slate-900">إنشاء حساب جديد</h1>
                <p class="text-xs text-slate-500">سجّل الآن لتتمكن من متابعة طلباتك ومشترياتك اليومية</p>
            </div>

            @if($errors->any())
            <div class="p-3 bg-red-50 border border-red-200 text-red-700 text-xs rounded-xl space-y-1">
                @foreach($errors->all() as $err)
                    <p class="flex items-center gap-1.5">
                        <x-icon name="alert-circle" class="w-3.5 h-3.5 text-red-500 flex-shrink-0" />
                        <span>{{ $err }}</span>
                    </p>
                @endforeach
            </div>
            @endif

            <form action="{{ route('register.submit') }}" method="POST" class="space-y-4">
                @csrf

                <!-- Name -->
                <div>
                    <label class="block text-xs font-bold text-slate-800 mb-1.5">الاسم الكريم <span class="text-red-500">*</span></label>
                    <input type="text" 
                           name="name" 
                           value="{{ old('name') }}" 
                           required 
                           placeholder="مثال: محمد العمري" 
                           class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs text-slate-900 focus:outline-none focus:border-[#0B2E75] focus:ring-2 focus:ring-[#0B2E75]/10">
                </div>

                <!-- Email -->
                <div>
                    <label class="block text-xs font-bold text-slate-800 mb-1.5">البريد الإلكتروني <span class="text-red-500">*</span></label>
                    <input type="email" 
                           name="email" 
                           value="{{ old('email') }}" 
                           required 
                           placeholder="name@example.com" 
                           class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs text-slate-900 focus:outline-none focus:border-[#0B2E75] focus:ring-2 focus:ring-[#0B2E75]/10 text-left" 
                           dir="ltr">
                </div>

                <!-- Phone -->
                <div>
                    <label class="block text-xs font-bold text-slate-800 mb-1.5">رقم الجوال <span class="text-[10px] text-slate-400 font-normal">(اختياري)</span></label>
                    <input type="tel" 
                           name="phone" 
                           value="{{ old('phone') }}" 
                           placeholder="05XXXXXXXX" 
                           class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs text-slate-900 focus:outline-none focus:border-[#0B2E75] focus:ring-2 focus:ring-[#0B2E75]/10 text-left" 
                           dir="ltr">
                </div>

                <!-- Password -->
                <div>
                    <label class="block text-xs font-bold text-slate-800 mb-1.5">كلمة المرور <span class="text-red-500">*</span></label>
                    <input type="password" 
                           name="password" 
                           required 
                           placeholder="لا تقل عن 8 أحرف" 
                           class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs text-slate-900 focus:outline-none focus:border-[#0B2E75] focus:ring-2 focus:ring-[#0B2E75]/10">
                </div>

                <!-- Password Confirmation -->
                <div>
                    <label class="block text-xs font-bold text-slate-800 mb-1.5">تأكيد كلمة المرور <span class="text-red-500">*</span></label>
                    <input type="password" 
                           name="password_confirmation" 
                           required 
                           placeholder="أعد كتابة كلمة المرور" 
                           class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs text-slate-900 focus:outline-none focus:border-[#0B2E75] focus:ring-2 focus:ring-[#0B2E75]/10">
                </div>

                <!-- Referral Code (Optional) -->
                <div>
                    <label class="block text-xs font-bold text-slate-800 mb-1.5">
                        كود الإحالة / الدعوة 
                        <span class="text-[10px] text-[#0B2E75] font-normal">(اختياري — إذا تمت دعوتك من صديق)</span>
                    </label>
                    <input type="text" 
                           name="referred_by" 
                           value="{{ old('referred_by', $referralCode ?? '') }}" 
                           placeholder="كود المسوّق (إن وجد)" 
                           class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs text-slate-900 font-mono focus:outline-none focus:border-[#0B2E75] focus:ring-2 focus:ring-[#0B2E75]/10 uppercase tracking-wider text-left" 
                           dir="ltr">
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full bg-[#0B2E75] hover:bg-[#071E50] text-white font-bold text-xs py-3.5 px-4 rounded-xl shadow-md transition duration-200 flex items-center justify-center gap-2 cursor-pointer">
                        <span>إنشاء الحساب الآن</span>
                        <x-icon name="arrow-left" class="w-4 h-4" />
                    </button>
                </div>
            </form>

            <div class="text-center pt-3 border-t border-slate-100 text-xs text-slate-500">
                <span>لديك حساب بالفعل؟</span>
                <a href="{{ route('login') }}" class="text-[#0B2E75] hover:text-[#FBB404] font-bold underline decoration-2 decoration-[#FBB404] underline-offset-4 mr-1 transition">
                    تسجيل الدخول هنا
                </a>
            </div>

        </div>
    </div>
</x-app-layout>
