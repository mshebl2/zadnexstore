<x-app-layout>
    <x-slot:title>تسجيل الدخول | متجر زاد نيكس</x-slot:title>

    <div class="max-w-md mx-auto px-4 py-16 text-right">
        <div class="bg-white rounded-3xl border border-slate-200 p-8 sm:p-10 shadow-xl space-y-6">
            
            <div class="text-center space-y-3">
                <a href="{{ route('home') }}" class="inline-block" title="متجر زاد نيكس">
                    <img src="{{ asset('images/logo.png') }}" alt="متجر زاد نيكس" class="h-16 mx-auto object-contain">
                </a>
                <h1 class="text-lg font-bold text-slate-900">تسجيل الدخول إلى حسابك</h1>
                <p class="text-xs text-slate-500">أدخل بريدك الإلكتروني وكلمة المرور لمتابعة طلباتك في متجر زاد نيكس</p>
            </div>

            @if(session('error'))
            <div class="p-3 bg-red-50 border border-red-200 text-red-700 text-xs rounded-xl flex items-center gap-2">
                <x-icon name="alert-circle" class="w-4 h-4 text-red-500 flex-shrink-0" />
                <span>{{ session('error') }}</span>
            </div>
            @endif

            <form action="{{ route('login.submit') }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-xs font-bold text-slate-800 mb-1.5">البريد الإلكتروني أو اسم المستخدم أو الجوال</label>
                    <input type="text" 
                           name="login" 
                           value="{{ old('login', old('email')) }}" 
                           required 
                           placeholder="info@zadnexstore.com أو admin أو 0580760890" 
                           class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs text-slate-900 focus:outline-none focus:border-[#0B2E75] focus:ring-2 focus:ring-[#0B2E75]/10">
                    @error('login') <span class="text-[11px] text-red-500 mt-1 block">{{ $message }}</span> @enderror
                    @error('email') <span class="text-[11px] text-red-500 mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-800 mb-1.5">كلمة المرور</label>
                    <input type="password" 
                           name="password" 
                           required 
                           placeholder="••••••••" 
                           class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs text-slate-900 focus:outline-none focus:border-[#0B2E75] focus:ring-2 focus:ring-[#0B2E75]/10">
                    @error('password') <span class="text-[11px] text-red-500 mt-1 block">{{ $message }}</span> @enderror
                </div>

                <!-- Admin Quick Hint -->
                <div class="bg-blue-50/70 border border-blue-200/60 rounded-xl p-3 text-[11px] text-blue-900 flex items-start gap-2">
                    <x-icon name="info" class="w-4 h-4 text-blue-600 shrink-0 mt-0.5" />
                    <div>
                        <span class="font-bold">بيانات حساب الإدارة الافتراضية:</span>
                        <div class="mt-0.5 text-[10px] text-blue-800 font-mono" dir="ltr">
                            البريد: info@zadnexstore.com | كلمة المرور: zadnex2026@admin
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-between text-xs text-slate-500">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="remember" class="text-[#0B2E75] focus:ring-[#0B2E75] rounded border-slate-300">
                        <span>تذكرني</span>
                    </label>
                </div>

                <button type="submit" class="w-full bg-[#0B2E75] hover:bg-[#071E50] text-white font-bold text-xs py-3.5 px-4 rounded-xl shadow-md transition duration-200 flex items-center justify-center gap-2">
                    <span>دخول</span>
                    <x-icon name="arrow-left" class="w-4 h-4" />
                </button>
            </form>

            <!-- Register New Account Link -->
            <div class="text-center pt-3 border-t border-slate-100 text-xs text-slate-500">
                <span>ليس لديك حساب بعد؟</span>
                <a href="{{ route('register') }}" class="text-[#0B2E75] hover:text-[#FBB404] font-bold underline decoration-2 decoration-[#FBB404] underline-offset-4 mr-1 transition">
                    إنشاء حساب جديد
                </a>
            </div>

        </div>
    </div>
</x-app-layout>
