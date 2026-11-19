<x-app-layout>
    <x-slot:title>تسجيل الدخول | متجر زاد نيكس</x-slot:title>

    <div class="max-w-md mx-auto px-4 py-16 text-right">
        <div class="bg-white rounded-3xl border border-[#EDE6DA] p-8 sm:p-10 shadow-lg space-y-6">
            
            <div class="text-center space-y-3">
                <a href="{{ route('home') }}" class="inline-block">
                    <img src="{{ asset('images/logo-dark.png') }}" alt="متجر زاد نيكس" class="h-14 mx-auto object-contain">
                </a>
                <h1 class="text-lg font-bold text-[#242424]">تسجيل الدخول إلى حسابك</h1>
                <p class="text-xs text-[#777777]">أدخل بريدك الإلكتروني وكلمة المرور لمتابعة طلباتك</p>
            </div>

            @if(session('error'))
            <div class="p-3 bg-red-50 border border-red-200 text-red-700 text-xs rounded-xl">
                {{ session('error') }}
            </div>
            @endif

            <form action="{{ route('login.submit') }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-xs font-bold text-[#242424] mb-1.5">البريد الإلكتروني</label>
                    <input type="email" 
                           name="email" 
                           value="{{ old('email') }}" 
                           required 
                           placeholder="name@example.com" 
                           class="w-full bg-[#FAF8F4] border border-[#EDE6DA] rounded-xl px-3.5 py-2.5 text-xs text-[#242424] focus:outline-none focus:border-[#C5A059]">
                    @error('email') <span class="text-[11px] text-red-500 mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold text-[#242424] mb-1.5">كلمة المرور</label>
                    <input type="password" 
                           name="password" 
                           required 
                           placeholder="••••••••" 
                           class="w-full bg-[#FAF8F4] border border-[#EDE6DA] rounded-xl px-3.5 py-2.5 text-xs text-[#242424] focus:outline-none focus:border-[#C5A059]">
                    @error('password') <span class="text-[11px] text-red-500 mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div class="flex items-center justify-between text-xs text-[#777777]">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="remember" class="text-[#C5A059] rounded">
                        <span>تذكرني</span>
                    </label>
                </div>

                <button type="submit" class="w-full bg-[#C5A059] hover:bg-[#AF8B44] text-white font-bold text-xs py-3.5 px-4 rounded-xl shadow-md transition duration-200">
                    دخول
                </button>
            </form>

            <!-- Register New Account Link -->
            <div class="text-center pt-2 border-t border-[#EDE6DA] text-xs text-[#777777]">
                <span>ليس لديك حساب بعد؟</span>
                <a href="{{ route('register') }}" class="text-[#C5A059] font-bold hover:underline mr-1">
                    إنشاء حساب جديد
                </a>
            </div>


        </div>
    </div>
</x-app-layout>

