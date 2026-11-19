<x-admin-layout>
    <x-slot:title>إعدادات حساب المدير وبيانات تسجيل الدخول</x-slot:title>

    <div class="max-w-4xl mx-auto text-right space-y-8">

        <!-- Header Banner -->
        <div class="bg-gradient-to-r from-[#1C1C1C] via-[#2A2A2A] to-[#1C1C1C] text-white p-6 sm:p-8 rounded-3xl border border-[#3A3A3A] shadow-md flex flex-col sm:flex-row items-center justify-between gap-6">
            <div class="flex items-center gap-4">
                <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-[#C5A059] to-[#8C6D37] text-white flex items-center justify-center shadow-lg font-black text-2xl">
                    {{ mb_substr($admin->name, 0, 1) }}
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <h2 class="text-lg sm:text-xl font-bold">{{ $admin->name }}</h2>
                        <span class="bg-[#C5A059] text-black text-[10px] font-black px-2.5 py-0.5 rounded-full">مدير النظام (Admin)</span>
                    </div>
                    <p class="text-xs text-[#A59D92] mt-1 font-mono" dir="ltr">{{ $admin->email }}</p>
                    <p class="text-[11px] text-gray-400 mt-0.5">تاريخ إنشاء الحساب: {{ $admin->created_at->format('Y-m-d') }}</p>
                </div>
            </div>

            <div class="flex items-center gap-2">
                <span class="inline-flex items-center gap-1.5 bg-emerald-950/80 text-emerald-400 border border-emerald-700/60 px-3 py-1.5 rounded-xl text-xs font-semibold">
                    <x-icon name="shield-check" class="w-4 h-4 text-emerald-400" />
                    <span>حساب نشط ومحمي</span>
                </span>
            </div>
        </div>

        @if ($errors->any())
            <div class="p-4 bg-red-50 border border-red-200 text-red-700 text-xs rounded-2xl">
                <div class="font-bold mb-1 flex items-center gap-1.5">
                    <x-icon name="alert-circle" class="w-4 h-4 text-red-600" />
                    <span>يرجى تصحيح الأخطاء التالية:</span>
                </div>
                <ul class="list-disc list-inside space-y-0.5 pr-2">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.profile.update') }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Section 1: Account Information -->
            <div class="bg-white rounded-3xl border border-[#EDE6DA] p-6 sm:p-8 shadow-xs space-y-6">
                <div class="border-b border-[#EDE6DA] pb-4">
                    <h3 class="text-sm font-bold text-[#242424] flex items-center gap-2">
                        <x-icon name="user" class="w-4 h-4 text-[#C5A059]" />
                        <span>البيانات الأساسية للمدير</span>
                    </h3>
                    <p class="text-xs text-gray-500 mt-0.5">اسمك الذي يظهر في النظام والبريد الإلكتروني المستخدم كاسم مستخدم لتسجيل الدخول</p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-xs font-bold text-[#242424] mb-1.5">اسم المدير</label>
                        <input type="text" 
                               name="name" 
                               value="{{ old('name', $admin->name) }}" 
                               required 
                               class="w-full bg-[#FAF8F4] border border-[#EDE6DA] rounded-xl p-3 text-xs text-[#242424] focus:outline-none focus:border-[#C5A059] focus:ring-1 focus:ring-[#C5A059] transition">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-[#242424] mb-1.5">
                            اسم المستخدم / البريد الإلكتروني لتسجيل الدخول
                        </label>
                        <input type="email" 
                               name="email" 
                               value="{{ old('email', $admin->email) }}" 
                               dir="ltr" 
                               required 
                               class="w-full bg-[#FAF8F4] border border-[#EDE6DA] rounded-xl p-3 text-xs text-left text-[#242424] font-mono focus:outline-none focus:border-[#C5A059] focus:ring-1 focus:ring-[#C5A059] transition">
                        <span class="text-[10px] text-gray-400 mt-1 block">هذا هو البريد الذي ستستخدمه في شاشة الدخول (Login).</span>
                    </div>

                    <div class="sm:col-span-2">
                        <label class="block text-xs font-bold text-[#242424] mb-1.5">رقم الجوال (اختياري)</label>
                        <input type="text" 
                               name="phone" 
                               value="{{ old('phone', $admin->phone) }}" 
                               placeholder="05XXXXXXXX" 
                               dir="ltr" 
                               class="w-full sm:w-1/2 bg-[#FAF8F4] border border-[#EDE6DA] rounded-xl p-3 text-xs text-left text-[#242424] font-mono focus:outline-none focus:border-[#C5A059] focus:ring-1 focus:ring-[#C5A059] transition">
                    </div>
                </div>
            </div>

            <!-- Section 2: Password Change -->
            <div class="bg-white rounded-3xl border border-[#EDE6DA] p-6 sm:p-8 shadow-xs space-y-6">
                <div class="border-b border-[#EDE6DA] pb-4">
                    <h3 class="text-sm font-bold text-[#242424] flex items-center gap-2">
                        <x-icon name="lock" class="w-4 h-4 text-[#C5A059]" />
                        <span>تغيير كلمة المرور للوحة التحكم</span>
                    </h3>
                    <p class="text-xs text-gray-500 mt-0.5">اترك حقول كلمة المرور فارغة إذا كنت تريد فقط تحديث الاسم أو البريد الإلكتروني دون تغيير كلمة المرور</p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                    <div>
                        <label class="block text-xs font-bold text-[#242424] mb-1.5">كلمة المرور الحالية</label>
                        <input type="password" 
                               name="current_password" 
                               placeholder="••••••••" 
                               dir="ltr" 
                               class="w-full bg-[#FAF8F4] border border-[#EDE6DA] rounded-xl p-3 text-xs text-left text-[#242424] focus:outline-none focus:border-[#C5A059] focus:ring-1 focus:ring-[#C5A059] transition">
                        <span class="text-[10px] text-gray-400 mt-1 block">مطلوبة فقط عند تعيين كلمة مرور جديدة.</span>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-[#242424] mb-1.5">كلمة المرور الجديدة</label>
                        <input type="password" 
                               name="new_password" 
                               placeholder="••••••••" 
                               dir="ltr" 
                               class="w-full bg-[#FAF8F4] border border-[#EDE6DA] rounded-xl p-3 text-xs text-left text-[#242424] focus:outline-none focus:border-[#C5A059] focus:ring-1 focus:ring-[#C5A059] transition">
                        <span class="text-[10px] text-gray-400 mt-1 block">6 أحرف على الأقل.</span>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-[#242424] mb-1.5">تأكيد كلمة المرور الجديدة</label>
                        <input type="password" 
                               name="new_password_confirmation" 
                               placeholder="••••••••" 
                               dir="ltr" 
                               class="w-full bg-[#FAF8F4] border border-[#EDE6DA] rounded-xl p-3 text-xs text-left text-[#242424] focus:outline-none focus:border-[#C5A059] focus:ring-1 focus:ring-[#C5A059] transition">
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="flex items-center justify-end gap-3 pt-2">
                <button type="submit" class="bg-[#242424] hover:bg-black text-[#C5A059] hover:text-[#FAF8F4] font-bold px-8 py-3.5 rounded-2xl text-xs transition shadow-md flex items-center gap-2">
                    <x-icon name="check-circle" class="w-4 h-4" />
                    <span>حفظ بيانات الحساب وكلمة المرور</span>
                </button>
            </div>
        </form>

    </div>
</x-admin-layout>
