<x-admin-layout>
    <x-slot:title>إدارة المسوقين وبرنامج الإحالات والعمولات</x-slot:title>

    <div class="space-y-8" x-data="{ 
        activeTab: 'affiliates',
        editModalOpen: false,
        selectedAffiliate: null,
        balanceModalOpen: false,
        balanceAffiliate: null,
        copiedCode: null,
        copyToClipboard(text, id) {
            navigator.clipboard.writeText(text);
            this.copiedCode = id;
            setTimeout(() => this.copiedCode = null, 2000);
        }
    }">

        <!-- Summary KPI Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 text-right">
            
            <div class="bg-white p-5 rounded-2xl border border-gray-200 shadow-xs flex items-center justify-between">
                <div>
                    <span class="text-xs font-semibold text-gray-500 block">إجمالي العمولات المستحقة</span>
                    <span class="text-2xl font-black text-[#242424] mt-1 block">
                        {{ number_format($totalCommissionPaid, 2) }} <span class="text-xs font-normal text-gray-500">ر.س</span>
                    </span>
                    <span class="text-[11px] text-[#C5A059] font-medium mt-1 block">
                        الافتراضي: {{ $globalSettings['type'] === 'fixed' ? $globalSettings['rate'] . ' ر.س ثابت' : $globalSettings['rate'] . '%' }}
                    </span>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-amber-50 text-[#C5A059] flex items-center justify-center">
                    <x-icon name="award" class="w-6 h-6" />
                </div>
            </div>

            <div class="bg-white p-5 rounded-2xl border border-gray-200 shadow-xs flex items-center justify-between">
                <div>
                    <span class="text-xs font-semibold text-gray-500 block">إجمالي المسوقين</span>
                    <span class="text-2xl font-black text-[#242424] mt-1 block">
                        {{ $totalAffiliatesCount }}
                    </span>
                    <span class="text-[11px] text-emerald-600 font-medium mt-1 block">أصحاب أكواد الإحالة النشطة</span>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                    <x-icon name="users" class="w-6 h-6" />
                </div>
            </div>

            <div class="bg-white p-5 rounded-2xl border border-gray-200 shadow-xs flex items-center justify-between">
                <div>
                    <span class="text-xs font-semibold text-gray-500 block">العملاء المسجلين عبر الإحالات</span>
                    <span class="text-2xl font-black text-indigo-600 mt-1 block">
                        {{ $totalReferredUsersCount }}
                    </span>
                    <span class="text-[11px] text-indigo-500 font-medium mt-1 block">تسجيلات تمت عبر روابط دعوة</span>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center">
                    <x-icon name="user-check" class="w-6 h-6" />
                </div>
            </div>

            <div class="bg-white p-5 rounded-2xl border border-gray-200 shadow-xs flex items-center justify-between">
                <div>
                    <span class="text-xs font-semibold text-gray-500 block">طلبات السحب المعلقة</span>
                    <span class="text-2xl font-black text-amber-600 mt-1 block">
                        {{ $payoutRequests->where('status', 'pending')->count() }}
                    </span>
                    <span class="text-[11px] text-gray-500 mt-1 block">تحتاج مراجعة وتحويل بنكي</span>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center">
                    <x-icon name="credit-card" class="w-6 h-6" />
                </div>
            </div>

        </div>

        <!-- Tabbed Navigation Bar -->
        <div class="bg-white rounded-2xl border border-gray-200 shadow-xs p-1.5 flex flex-wrap gap-1">
            <button type="button" 
                    @click="activeTab = 'affiliates'" 
                    :class="activeTab === 'affiliates' ? 'bg-[#242424] text-white shadow-xs' : 'text-gray-600 hover:text-black hover:bg-gray-100'"
                    class="px-5 py-2.5 rounded-xl text-xs font-bold transition flex items-center gap-2">
                <x-icon name="users" class="w-4 h-4" />
                <span>قائمة المسوقين والتحكم بالعمولات</span>
                <span class="px-2 py-0.5 rounded-full text-[10px] font-black" :class="activeTab === 'affiliates' ? 'bg-[#C5A059] text-white' : 'bg-gray-200 text-gray-700'">{{ $totalAffiliatesCount }}</span>
            </button>

            <button type="button" 
                    @click="activeTab = 'referred_customers'" 
                    :class="activeTab === 'referred_customers' ? 'bg-[#242424] text-white shadow-xs' : 'text-gray-600 hover:text-black hover:bg-gray-100'"
                    class="px-5 py-2.5 rounded-xl text-xs font-bold transition flex items-center gap-2">
                <x-icon name="user-check" class="w-4 h-4" />
                <span>العملاء المسجلين عبر الإحالات</span>
                <span class="px-2 py-0.5 rounded-full text-[10px] font-black" :class="activeTab === 'referred_customers' ? 'bg-[#C5A059] text-white' : 'bg-gray-200 text-gray-700'">{{ $totalReferredUsersCount }}</span>
            </button>

            <button type="button" 
                    @click="activeTab = 'payouts'" 
                    :class="activeTab === 'payouts' ? 'bg-[#242424] text-white shadow-xs' : 'text-gray-600 hover:text-black hover:bg-gray-100'"
                    class="px-5 py-2.5 rounded-xl text-xs font-bold transition flex items-center gap-2">
                <x-icon name="credit-card" class="w-4 h-4" />
                <span>طلبات سحب الأرباح (IBAN)</span>
                @if($payoutRequests->where('status', 'pending')->count() > 0)
                    <span class="bg-amber-500 text-white px-2 py-0.5 rounded-full text-[10px] font-black animate-pulse">
                        {{ $payoutRequests->where('status', 'pending')->count() }} معلق
                    </span>
                @endif
            </button>

            <button type="button" 
                    @click="activeTab = 'commissions'" 
                    :class="activeTab === 'commissions' ? 'bg-[#242424] text-white shadow-xs' : 'text-gray-600 hover:text-black hover:bg-gray-100'"
                    class="px-5 py-2.5 rounded-xl text-xs font-bold transition flex items-center gap-2">
                <x-icon name="award" class="w-4 h-4" />
                <span>سجل العمولات الأخيرة</span>
            </button>

            <button type="button" 
                    @click="activeTab = 'settings'" 
                    :class="activeTab === 'settings' ? 'bg-[#242424] text-white shadow-xs' : 'text-gray-600 hover:text-black hover:bg-gray-100'"
                    class="px-5 py-2.5 rounded-xl text-xs font-bold transition flex items-center gap-2">
                <x-icon name="settings" class="w-4 h-4" />
                <span>إعدادات النظام العامة</span>
            </button>
        </div>

        <!-- ========================================== -->
        <!-- TAB 1: Affiliates List & Rate Management -->
        <!-- ========================================== -->
        <div x-show="activeTab === 'affiliates'" class="space-y-4">
            <div class="bg-white rounded-2xl border border-gray-200 shadow-xs overflow-hidden">
                <div class="p-5 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h3 class="text-base font-bold text-[#242424]">سجل المسوقين والتحكم في نسبة ومبلغ كل إحالة</h3>
                        <p class="text-xs text-gray-500 mt-0.5">يمكنك تخصيص عمولة خاصة لكل مسوق (نسبة % أو مبلغ ثابت ر.س) وتعديل رصيد محفظته مباشرة</p>
                    </div>

                    <!-- Search Filter Form -->
                    <form method="GET" action="{{ route('admin.affiliates.index') }}" class="flex items-center gap-2">
                        <input type="text" 
                               name="search" 
                               value="{{ request('search') }}" 
                               placeholder="بحث بالاسم، الإيميل، الجوال، الكود..." 
                               class="bg-gray-50 border border-gray-200 rounded-xl px-3.5 py-2 text-xs focus:ring-2 focus:ring-[#C5A059] focus:outline-hidden w-64 text-right">
                        <button type="submit" class="bg-[#242424] hover:bg-black text-white px-4 py-2 rounded-xl text-xs font-bold transition">
                            بحث
                        </button>
                        @if(request('search'))
                            <a href="{{ route('admin.affiliates.index') }}" class="text-xs text-red-600 hover:underline px-1">إلغاء</a>
                        @endif
                    </form>
                </div>

                @if($affiliates->isEmpty())
                    <div class="text-center py-12 text-gray-400 text-xs">
                        لا يوجد مسوقين مطابقين للبحث حالياً.
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-right text-xs">
                            <thead class="bg-gray-50 border-b border-gray-200 text-gray-600 font-bold">
                                <tr>
                                    <th class="p-4">المسوق (الاسم والبريد)</th>
                                    <th class="p-4">كود ورابط الإحالة</th>
                                    <th class="p-4 text-center">العملاء المحالين</th>
                                    <th class="p-4 text-center">الطلبات المنفذة</th>
                                    <th class="p-4">إجمالي العمولات</th>
                                    <th class="p-4">رصيد المحفظة الحالي</th>
                                    <th class="p-4">عمولة هذا المسوق</th>
                                    <th class="p-4 text-center">الحالة</th>
                                    <th class="p-4 text-center">إجراءات التحكم</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach($affiliates as $aff)
                                <tr class="hover:bg-gray-50/50 transition">
                                    <td class="p-4">
                                        <div class="font-bold text-[#242424] text-sm">{{ $aff->name }}</div>
                                        <div class="text-gray-500 font-mono text-[11px] mt-0.5" dir="ltr">{{ $aff->email }}</div>
                                        @if($aff->phone)
                                            <div class="text-gray-400 text-[10px] mt-0.5" dir="ltr">{{ $aff->phone }}</div>
                                        @endif
                                        <div class="text-gray-400 text-[10px] mt-1">انضم: {{ $aff->created_at->format('Y/m/d') }}</div>
                                    </td>

                                    <td class="p-4 whitespace-nowrap">
                                        <span class="inline-block bg-amber-50 text-[#C5A059] font-mono font-bold px-2.5 py-1 rounded-lg border border-amber-200">
                                            {{ $aff->referral_code }}
                                        </span>
                                        <div class="mt-1.5 flex items-center gap-1.5">
                                            <button type="button" 
                                                    @click="copyToClipboard('{{ $aff->referral_link }}', {{ $aff->id }})" 
                                                    class="text-[11px] text-gray-500 hover:text-[#C5A059] flex items-center gap-1 transition">
                                                <x-icon name="copy" class="w-3.5 h-3.5" />
                                                <span x-text="copiedCode === {{ $aff->id }} ? 'تم النسخ!' : 'نسخ الرابط'">نسخ الرابط</span>
                                            </button>
                                        </div>
                                    </td>

                                    <td class="p-4 text-center">
                                        <span class="bg-indigo-50 text-indigo-700 font-black px-2.5 py-1 rounded-full text-xs">
                                            {{ $aff->referred_users_count }} عميل
                                        </span>
                                    </td>

                                    <td class="p-4 text-center">
                                        <span class="bg-emerald-50 text-emerald-700 font-black px-2.5 py-1 rounded-full text-xs">
                                            {{ $aff->referral_commissions_count }} طلب
                                        </span>
                                    </td>

                                    <td class="p-4 font-mono font-bold text-gray-700">
                                        {{ number_format($aff->total_commission_earned ?? 0, 2) }} ر.س
                                    </td>

                                    <td class="p-4">
                                        <div class="font-mono font-black text-sm text-[#C5A059]">
                                            {{ number_format($aff->wallet_balance, 2) }} ر.س
                                        </div>
                                        <button type="button" 
                                                @click="balanceAffiliate = { id: {{ $aff->id }}, name: '{{ addslashes($aff->name) }}', balance: {{ (float)$aff->wallet_balance }} }; balanceModalOpen = true;"
                                                class="text-[10px] text-blue-600 hover:underline mt-1 font-semibold flex items-center gap-1">
                                            <x-icon name="plus-circle" class="w-3 h-3" />
                                            <span>تعديل الرصيد</span>
                                        </button>
                                    </td>

                                    <td class="p-4">
                                        @if($aff->custom_commission_type === 'percent' && $aff->custom_commission_rate !== null)
                                            <span class="inline-flex items-center gap-1 bg-purple-50 text-purple-700 font-bold px-2 py-0.5 rounded-md text-[11px] border border-purple-200">
                                                <x-icon name="percent" class="w-3 h-3" />
                                                <span>{{ $aff->custom_commission_rate }}% (خاص)</span>
                                            </span>
                                        @elseif($aff->custom_commission_type === 'fixed' && $aff->custom_commission_rate !== null)
                                            <span class="inline-flex items-center gap-1 bg-emerald-50 text-emerald-700 font-bold px-2 py-0.5 rounded-md text-[11px] border border-emerald-200">
                                                <span>{{ $aff->custom_commission_rate }} ر.س ثابت (خاص)</span>
                                            </span>
                                        @else
                                            <span class="text-gray-500 text-[11px]">
                                                افتراضي ({{ $globalSettings['type'] === 'fixed' ? $globalSettings['rate'] . ' ر.س' : $globalSettings['rate'] . '%' }})
                                            </span>
                                        @endif
                                    </td>

                                    <td class="p-4 text-center">
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold {{ $aff->is_affiliate_active ? 'bg-emerald-100 text-emerald-800' : 'bg-red-100 text-red-800' }}">
                                            {{ $aff->is_affiliate_active ? 'نشط' : 'موقوف' }}
                                        </span>
                                    </td>

                                    <td class="p-4 text-center whitespace-nowrap">
                                        <button type="button" 
                                                @click="selectedAffiliate = {
                                                    id: {{ $aff->id }},
                                                    name: '{{ addslashes($aff->name) }}',
                                                    email: '{{ addslashes($aff->email) }}',
                                                    type: '{{ $aff->custom_commission_type ?? 'default' }}',
                                                    rate: '{{ $aff->custom_commission_rate ?? '' }}',
                                                    active: {{ $aff->is_affiliate_active ? 'true' : 'false' }}
                                                }; editModalOpen = true;"
                                                class="bg-[#FAF8F4] hover:bg-[#EDE6DA] text-[#242424] hover:text-[#C5A059] px-3 py-1.5 rounded-lg text-xs font-bold border border-[#EDE6DA] transition flex items-center gap-1 mx-auto">
                                            <x-icon name="sliders" class="w-3.5 h-3.5" />
                                            <span>تخصيص العمولة</span>
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="p-4 border-t border-gray-100">
                        {{ $affiliates->appends(['search' => request('search')])->links() }}
                    </div>
                @endif
            </div>
        </div>

        <!-- ========================================== -->
        <!-- TAB 2: Registered Referred Customers List -->
        <!-- ========================================== -->
        <div x-show="activeTab === 'referred_customers'" class="space-y-4" style="display: none;">
            <div class="bg-white rounded-2xl border border-gray-200 shadow-xs overflow-hidden">
                <div class="p-5 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h3 class="text-base font-bold text-[#242424]">سجل إيميلات وبيانات العملاء المسجلين عبر الإحالات</h3>
                        <p class="text-xs text-gray-500 mt-0.5">يعرض كل عميل قام بإنشاء حساب عن طريق رابط إحالة خاص بأحد المسوقين وإجمالي مشترياته</p>
                    </div>

                    <!-- Search Filter Form -->
                    <form method="GET" action="{{ route('admin.affiliates.index') }}" class="flex items-center gap-2">
                        <input type="text" 
                               name="referral_search" 
                               value="{{ request('referral_search') }}" 
                               placeholder="بحث باسم العميل، الإيميل، كود المسوق..." 
                               class="bg-gray-50 border border-gray-200 rounded-xl px-3.5 py-2 text-xs focus:ring-2 focus:ring-[#C5A059] focus:outline-hidden w-64 text-right">
                        <button type="submit" class="bg-[#242424] hover:bg-black text-white px-4 py-2 rounded-xl text-xs font-bold transition">
                            بحث
                        </button>
                        @if(request('referral_search'))
                            <a href="{{ route('admin.affiliates.index') }}" class="text-xs text-red-600 hover:underline px-1">إلغاء</a>
                        @endif
                    </form>
                </div>

                @if($referredCustomers->isEmpty())
                    <div class="text-center py-12 text-gray-400 text-xs">
                        لا يوجد عملاء مسجلين عبر روابط إحالة حتى الآن.
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-right text-xs">
                            <thead class="bg-gray-50 border-b border-gray-200 text-gray-600 font-bold">
                                <tr>
                                    <th class="p-4">العميل المسجل (الاسم والإيميل)</th>
                                    <th class="p-4">كود الإحالة المستخدم</th>
                                    <th class="p-4">المسوق صاحب الدعوة</th>
                                    <th class="p-4">تاريخ التسجيل</th>
                                    <th class="p-4 text-center">الطلبات المكتملة</th>
                                    <th class="p-4">إجمالي المشتريات</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach($referredCustomers as $customer)
                                @php
                                    $referrerUser = \App\Models\User::where('referral_code', $customer->referred_by)->first();
                                @endphp
                                <tr class="hover:bg-gray-50/50 transition">
                                    <td class="p-4">
                                        <div class="font-bold text-[#242424] text-sm">{{ $customer->name }}</div>
                                        <div class="text-gray-500 font-mono text-[11px] mt-0.5" dir="ltr">{{ $customer->email }}</div>
                                        @if($customer->phone)
                                            <div class="text-gray-400 text-[10px] mt-0.5" dir="ltr">{{ $customer->phone }}</div>
                                        @endif
                                    </td>

                                    <td class="p-4">
                                        <span class="bg-amber-50 text-[#C5A059] font-mono font-bold px-2 py-0.5 rounded-md border border-amber-200">
                                            {{ $customer->referred_by }}
                                        </span>
                                    </td>

                                    <td class="p-4">
                                        @if($referrerUser)
                                            <div class="font-bold text-gray-800">{{ $referrerUser->name }}</div>
                                            <div class="text-gray-400 font-mono text-[10px]" dir="ltr">{{ $referrerUser->email }}</div>
                                        @else
                                            <span class="text-gray-400">—</span>
                                        @endif
                                    </td>

                                    <td class="p-4 text-gray-500 whitespace-nowrap">
                                        {{ $customer->created_at->format('Y-m-d H:i') }}
                                    </td>

                                    <td class="p-4 text-center">
                                        <span class="bg-gray-100 text-gray-800 font-bold px-2.5 py-1 rounded-full text-xs">
                                            {{ $customer->orders_count }} طلب
                                        </span>
                                    </td>

                                    <td class="p-4 font-mono font-bold text-emerald-700">
                                        {{ number_format($customer->total_spent ?? 0, 2) }} ر.س
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="p-4 border-t border-gray-100">
                        {{ $referredCustomers->appends(['referral_search' => request('referral_search')])->links() }}
                    </div>
                @endif
            </div>
        </div>

        <!-- ========================================== -->
        <!-- TAB 3: Payout Requests Management -->
        <!-- ========================================== -->
        <div x-show="activeTab === 'payouts'" class="space-y-4" style="display: none;">
            <div class="bg-white rounded-2xl border border-gray-200 shadow-xs overflow-hidden">
                <div class="p-6 border-b border-gray-100 flex items-center justify-between">
                    <div>
                        <h3 class="text-base font-bold text-[#242424]">طلبات سحب أرباح المسوقين (التحويلات البنكية)</h3>
                        <p class="text-xs text-gray-500 mt-0.5">مراجعة الآيبان البنكي والموافقة على التحويل</p>
                    </div>
                </div>

                @if($payoutRequests->isEmpty())
                    <div class="text-center py-12 text-gray-400 text-xs">
                        لا توجد طلبات سحب أرباح حالياً.
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-right text-xs">
                            <thead class="bg-gray-50 border-b border-gray-200 text-gray-600 font-bold">
                                <tr>
                                    <th class="p-4">المسوق</th>
                                    <th class="p-4">المبلغ</th>
                                    <th class="p-4">البنك والآيبان</th>
                                    <th class="p-4">اسم الحساب</th>
                                    <th class="p-4">التاريخ</th>
                                    <th class="p-4">الحالة</th>
                                    <th class="p-4 text-center">الإجراء</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach($payoutRequests as $req)
                                <tr class="hover:bg-gray-50/50">
                                    <td class="p-4 font-bold text-[#242424]">
                                        {{ $req->user->name }}
                                        <span class="text-[11px] text-gray-400 block font-normal font-mono" dir="ltr">{{ $req->user->email }}</span>
                                    </td>
                                    <td class="p-4 font-black text-sm text-[#C5A059] font-mono">
                                        {{ number_format($req->amount, 2) }} ر.س
                                    </td>
                                    <td class="p-4">
                                        <span class="font-bold block text-gray-700">{{ $req->bank_name }}</span>
                                        <span class="font-mono text-[11px] text-gray-500" dir="ltr">{{ $req->iban }}</span>
                                    </td>
                                    <td class="p-4 text-gray-700">
                                        {{ $req->account_name }}
                                    </td>
                                    <td class="p-4 text-gray-400 text-[11px] whitespace-nowrap">
                                        {{ $req->created_at->format('Y-m-d H:i') }}
                                    </td>
                                    <td class="p-4 whitespace-nowrap">
                                        <span class="px-2.5 py-1 rounded-full text-[10px] font-bold 
                                            {{ $req->status === 'completed' ? 'bg-emerald-100 text-emerald-800' : ($req->status === 'rejected' ? 'bg-red-100 text-red-800' : 'bg-amber-100 text-amber-800') }}">
                                            {{ $req->status === 'completed' ? 'تم التحويل' : ($req->status === 'rejected' ? 'مرفوض' : 'قيد المراجعة') }}
                                        </span>
                                    </td>
                                    <td class="p-4 text-center whitespace-nowrap">
                                        @if($req->status === 'pending')
                                        <div class="flex items-center justify-center gap-1.5">
                                            <!-- Approve -->
                                            <form method="POST" action="{{ route('admin.payouts.status', $req->id) }}">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="completed">
                                                <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white px-3 py-1 rounded-lg text-xs font-bold transition">
                                                    تأكيد التحويل
                                                </button>
                                            </form>

                                            <!-- Reject -->
                                            <form method="POST" action="{{ route('admin.payouts.status', $req->id) }}" onsubmit="return confirm('هل أنت متأكد من رفض الطلب وإعادة المبلغ لمحفظة المسوق؟');">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="rejected">
                                                <button type="submit" class="bg-red-100 hover:bg-red-200 text-red-700 px-2.5 py-1 rounded-lg text-xs font-semibold transition">
                                                    رفض
                                                </button>
                                            </form>
                                        </div>
                                        @else
                                            <span class="text-[11px] text-gray-400">—</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>

        <!-- ========================================== -->
        <!-- TAB 4: Recent Commissions Audit Log -->
        <!-- ========================================== -->
        <div x-show="activeTab === 'commissions'" class="space-y-4" style="display: none;">
            <div class="bg-white rounded-2xl border border-gray-200 shadow-xs overflow-hidden">
                <div class="p-6 border-b border-gray-100">
                    <h3 class="text-base font-bold text-[#242424]">سجل العمولات المكتسبة لجميع الطلبات</h3>
                    <p class="text-xs text-gray-500 mt-0.5">تفاصيل العمولات المحسوبة على كل عملية شراء ناجحة تمت عبر الإحالات</p>
                </div>

                @if($recentCommissions->isEmpty())
                    <div class="text-center py-8 text-gray-400 text-xs">
                        لم يتم تسجيل أي عمولات حتى الآن.
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-right text-xs">
                            <thead class="bg-gray-50 border-b border-gray-200 text-gray-600 font-bold">
                                <tr>
                                    <th class="p-4">المسوق (المستفيد)</th>
                                    <th class="p-4">العميل المشتري</th>
                                    <th class="p-4">رقم الطلب</th>
                                    <th class="p-4">إجمالي الطلب</th>
                                    <th class="p-4">العمولة المطبقة</th>
                                    <th class="p-4">مبلغ الربح المحسوب</th>
                                    <th class="p-4">التاريخ</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach($recentCommissions as $comm)
                                <tr class="hover:bg-gray-50/50">
                                    <td class="p-4 font-bold text-[#242424]">
                                        {{ $comm->referrer->name ?? 'مسوق' }}
                                        <span class="text-[11px] text-[#C5A059] block font-mono">كود: {{ $comm->referrer->referral_code ?? '—' }}</span>
                                    </td>
                                    <td class="p-4 text-gray-700">
                                        {{ $comm->buyer->name ?? 'عميل زائر' }}
                                        @if($comm->buyer)
                                            <span class="text-[10px] text-gray-400 block font-mono" dir="ltr">{{ $comm->buyer->email }}</span>
                                        @endif
                                    </td>
                                    <td class="p-4 font-mono font-bold text-gray-700">
                                        {{ $comm->order->order_number ?? 'طلب' }}
                                    </td>
                                    <td class="p-4 font-mono">
                                        {{ number_format($comm->order_total, 2) }} ر.س
                                    </td>
                                    <td class="p-4 font-bold text-purple-700">
                                        {{ $comm->commission_rate }}
                                    </td>
                                    <td class="p-4 font-mono font-black text-sm text-[#C5A059]">
                                        {{ number_format($comm->commission_amount, 2) }} ر.س
                                    </td>
                                    <td class="p-4 text-gray-400 text-[11px]">
                                        {{ $comm->created_at->format('Y-m-d H:i') }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>

        <!-- ========================================== -->
        <!-- TAB 5: Global Referral Settings Form -->
        <!-- ========================================== -->
        <div x-show="activeTab === 'settings'" class="space-y-4" style="display: none;">
            <div class="bg-white rounded-2xl border border-gray-200 shadow-xs p-6 max-w-2xl text-right">
                <div class="border-b border-gray-100 pb-4 mb-6">
                    <h3 class="text-base font-bold text-[#242424]">إعدادات برنامج الإحالة والتسويق بالعمولة العامة</h3>
                    <p class="text-xs text-gray-500 mt-1">تحديد السياسة الافتراضية للعمولات لكافة المسوقين ما لم يتم تخصيص عمولة خاصة للمسوق</p>
                </div>

                <form method="POST" action="{{ route('admin.affiliates.settings') }}" class="space-y-6">
                    @csrf

                    <!-- Enable/Disable Program -->
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl border border-gray-200">
                        <div>
                            <span class="text-xs font-bold text-[#242424] block">تفعيل برنامج الإحالة والتسويق بالعمولة</span>
                            <span class="text-[11px] text-gray-500 block mt-0.5">عند تعطيل هذا الخيار، لن يتم احتساب أي عمولات على الطلبات الجديدة</span>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="affiliate_enabled" value="1" {{ $globalSettings['enabled'] == '1' ? 'checked' : '' }} class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-hidden rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#C5A059]"></div>
                        </label>
                    </div>

                    <!-- Commission Type -->
                    <div>
                        <label class="block text-xs font-bold text-[#242424] mb-2">نوع العمولة الافتراضية</label>
                        <div class="grid grid-cols-2 gap-4">
                            <label class="border border-gray-200 rounded-xl p-3 flex items-center gap-3 cursor-pointer hover:bg-gray-50">
                                <input type="radio" name="affiliate_commission_type" value="percent" {{ $globalSettings['type'] === 'percent' ? 'checked' : '' }} class="text-[#C5A059] focus:ring-[#C5A059]">
                                <div>
                                    <span class="text-xs font-bold block text-gray-800">نسبة مئوية (%)</span>
                                    <span class="text-[10px] text-gray-500">نسبة مئوية من قيمة الطلب قبل الشحن</span>
                                </div>
                            </label>

                            <label class="border border-gray-200 rounded-xl p-3 flex items-center gap-3 cursor-pointer hover:bg-gray-50">
                                <input type="radio" name="affiliate_commission_type" value="fixed" {{ $globalSettings['type'] === 'fixed' ? 'checked' : '' }} class="text-[#C5A059] focus:ring-[#C5A059]">
                                <div>
                                    <span class="text-xs font-bold block text-gray-800">مبلغ ثابت (ر.س)</span>
                                    <span class="text-[10px] text-gray-500">مبلغ محدد بالريال السعودي لكل طلب ناجح</span>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- Commission Rate / Amount -->
                    <div>
                        <label class="block text-xs font-bold text-[#242424] mb-2">قيمة العمولة الافتراضية</label>
                        <div class="relative">
                            <input type="number" 
                                   step="0.01" 
                                   min="0" 
                                   max="1000"
                                   name="affiliate_commission_rate" 
                                   value="{{ $globalSettings['rate'] }}" 
                                   class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-xs text-right focus:ring-2 focus:ring-[#C5A059] focus:outline-hidden font-mono font-bold" 
                                   required>
                        </div>
                        <p class="text-[11px] text-gray-400 mt-1">إذا تم اختيار نسبة مئوية، اكتب مثلاً 10 (تعني 10%). وإذا تم اختيار مبلغ ثابت، اكتب مثلاً 20 (تعني 20 ر.س).</p>
                    </div>

                    <!-- Minimum Payout Threshold -->
                    <div>
                        <label class="block text-xs font-bold text-[#242424] mb-2">الحد الأدنى لطلب سحب الأرباح (ر.س)</label>
                        <input type="number" 
                               step="1" 
                               min="1" 
                               name="affiliate_min_payout" 
                               value="{{ $globalSettings['min_payout'] }}" 
                               class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-xs text-right focus:ring-2 focus:ring-[#C5A059] focus:outline-hidden font-mono font-bold" 
                               required>
                        <p class="text-[11px] text-gray-400 mt-1">أقل رصيد محفظة يسمح للمسوق بتقديم طلب تحويل بنكي له (الافتراضي: 100 ر.س).</p>
                    </div>

                    <button type="submit" class="w-full bg-[#242424] hover:bg-black text-white font-bold py-3 rounded-xl text-xs transition shadow-sm">
                        حفظ الإعدادات العامة
                    </button>
                </form>
            </div>
        </div>

        <!-- ========================================== -->
        <!-- MODAL: Custom Rate & Status Per Affiliate  -->
        <!-- ========================================== -->
        <div x-show="editModalOpen" 
             x-cloak 
             class="fixed inset-0 z-50 overflow-y-auto bg-black/60 backdrop-blur-xs flex items-center justify-center p-4" 
             @click.self="editModalOpen = false">
            <div class="bg-white rounded-3xl max-w-md w-full p-6 text-right shadow-2xl border border-gray-100">
                <div class="flex items-center justify-between pb-4 border-b border-gray-100">
                    <h3 class="text-sm font-bold text-[#242424]">تخصيص عمولة المسوق</h3>
                    <button type="button" @click="editModalOpen = false" class="text-gray-400 hover:text-gray-600">
                        <x-icon name="x" class="w-5 h-5" />
                    </button>
                </div>

                <template x-if="selectedAffiliate">
                    <form :action="'/admin/affiliates/' + selectedAffiliate.id + '/rate'" method="POST" class="mt-4 space-y-4">
                        @csrf
                        @method('PUT')

                        <div>
                            <span class="text-xs text-gray-500 block">المسوق:</span>
                            <span class="text-sm font-bold text-[#242424] block" x-text="selectedAffiliate.name"></span>
                            <span class="text-xs text-gray-400 font-mono block" x-text="selectedAffiliate.email"></span>
                        </div>

                        <!-- Commission Type Override -->
                        <div>
                            <label class="block text-xs font-bold text-[#242424] mb-1">نوع العمولة لهذا المسوق</label>
                            <select name="custom_commission_type" 
                                    x-model="selectedAffiliate.type" 
                                    class="w-full bg-gray-50 border border-gray-200 rounded-xl p-2.5 text-xs text-right focus:ring-2 focus:ring-[#C5A059]">
                                <option value="default">استخدام النسبة الافتراضية للمتجر ({{ $globalSettings['type'] === 'fixed' ? $globalSettings['rate'] . ' ر.س' : $globalSettings['rate'] . '%' }})</option>
                                <option value="percent">نسبة مئوية مخصصة (%)</option>
                                <option value="fixed">مبلغ ثابت مخصص (ر.س لكل طلب)</option>
                            </select>
                        </div>

                        <!-- Commission Value -->
                        <div x-show="selectedAffiliate.type !== 'default'">
                            <label class="block text-xs font-bold text-[#242424] mb-1">
                                <span x-show="selectedAffiliate.type === 'percent'">النسبة المئوية المخصصة (%)</span>
                                <span x-show="selectedAffiliate.type === 'fixed'">المبلغ الثابت المخصص (ر.س)</span>
                            </label>
                            <input type="number" 
                                   step="0.01" 
                                   min="0" 
                                   max="1000"
                                   name="custom_commission_rate" 
                                   x-model="selectedAffiliate.rate" 
                                   placeholder="مثلاً: 15" 
                                   class="w-full bg-gray-50 border border-gray-200 rounded-xl p-2.5 text-xs text-right focus:ring-2 focus:ring-[#C5A059] font-mono font-bold">
                        </div>

                        <!-- Status Active/Inactive -->
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl border border-gray-100">
                            <div>
                                <span class="text-xs font-bold text-[#242424] block">حالة حساب الإحالة</span>
                                <span class="text-[10px] text-gray-400">إيقاف احتساب العمولات مؤقتاً لهذا المسوق</span>
                            </div>
                            <input type="checkbox" 
                                   name="is_affiliate_active" 
                                   value="1" 
                                   x-model="selectedAffiliate.active" 
                                   class="w-4 h-4 rounded-sm text-[#C5A059] focus:ring-[#C5A059]">
                        </div>

                        <div class="flex items-center gap-2 pt-2">
                            <button type="submit" class="flex-1 bg-[#242424] hover:bg-black text-white font-bold py-2.5 rounded-xl text-xs transition">
                                حفظ التعديلات
                            </button>
                            <button type="button" @click="editModalOpen = false" class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold px-4 py-2.5 rounded-xl text-xs transition">
                                إلغاء
                            </button>
                        </div>
                    </form>
                </template>
            </div>
        </div>

        <!-- ========================================== -->
        <!-- MODAL: Adjust Wallet Balance Per Affiliate -->
        <!-- ========================================== -->
        <div x-show="balanceModalOpen" 
             x-cloak 
             class="fixed inset-0 z-50 overflow-y-auto bg-black/60 backdrop-blur-xs flex items-center justify-center p-4" 
             @click.self="balanceModalOpen = false">
            <div class="bg-white rounded-3xl max-w-md w-full p-6 text-right shadow-2xl border border-gray-100">
                <div class="flex items-center justify-between pb-4 border-b border-gray-100">
                    <h3 class="text-sm font-bold text-[#242424]">تعديل رصيد محفظة المسوق يدوياً</h3>
                    <button type="button" @click="balanceModalOpen = false" class="text-gray-400 hover:text-gray-600">
                        <x-icon name="x" class="w-5 h-5" />
                    </button>
                </div>

                <template x-if="balanceAffiliate">
                    <form :action="'/admin/affiliates/' + balanceAffiliate.id + '/adjust-balance'" method="POST" class="mt-4 space-y-4">
                        @csrf

                        <div>
                            <span class="text-xs text-gray-500 block">المسوق:</span>
                            <span class="text-sm font-bold text-[#242424] block" x-text="balanceAffiliate.name"></span>
                            <span class="text-xs text-[#C5A059] font-mono font-bold block mt-0.5">
                                الرصيد الحالي: <span x-text="balanceAffiliate.balance.toFixed(2)"></span> ر.س
                            </span>
                        </div>

                        <!-- Action Type -->
                        <div>
                            <label class="block text-xs font-bold text-[#242424] mb-1">نوع الإجراء</label>
                            <select name="action" class="w-full bg-gray-50 border border-gray-200 rounded-xl p-2.5 text-xs text-right focus:ring-2 focus:ring-[#C5A059]">
                                <option value="add">إضافة مكافأة / رصيد إضافي (+)</option>
                                <option value="deduct">خصم من الرصيد (-)</option>
                                <option value="set">تحديد الرصيد لمبلغ معين (=)</option>
                            </select>
                        </div>

                        <!-- Amount -->
                        <div>
                            <label class="block text-xs font-bold text-[#242424] mb-1">المبلغ (ر.س)</label>
                            <input type="number" 
                                   step="0.01" 
                                   min="0.01" 
                                   name="amount" 
                                   placeholder="مثلاً: 50" 
                                   class="w-full bg-gray-50 border border-gray-200 rounded-xl p-2.5 text-xs text-right focus:ring-2 focus:ring-[#C5A059] font-mono font-bold" 
                                   required>
                        </div>

                        <!-- Note -->
                        <div>
                            <label class="block text-xs font-bold text-[#242424] mb-1">ملاحظة / سبب التعديل (اختياري)</label>
                            <input type="text" 
                                   name="note" 
                                   placeholder="مثلاً: مكافأة تحقيق هدف مبيعات" 
                                   class="w-full bg-gray-50 border border-gray-200 rounded-xl p-2.5 text-xs text-right focus:ring-2 focus:ring-[#C5A059]">
                        </div>

                        <div class="flex items-center gap-2 pt-2">
                            <button type="submit" class="flex-1 bg-[#242424] hover:bg-black text-white font-bold py-2.5 rounded-xl text-xs transition">
                                تأكيد التعديل
                            </button>
                            <button type="button" @click="balanceModalOpen = false" class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold px-4 py-2.5 rounded-xl text-xs transition">
                                إلغاء
                            </button>
                        </div>
                    </form>
                </template>
            </div>
        </div>

    </div>
</x-admin-layout>
