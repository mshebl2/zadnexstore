<x-app-layout>
    <x-slot:title>لوحة تحكم العميل | متجر زاد نيكس</x-slot:title>
    <x-slot:description>إدارة حسابك، متابعة طلباتك وتتبع الشحنات، برنامج الإحالات والأرباح، والعناوين المحفوظة في متجر زاد نيكس.</x-slot:description>

    <!-- Top Luxury Profile Banner -->
    <div class="bg-gradient-to-r from-[#1C1C1C] via-[#2A2A2A] to-[#1C1C1C] text-white py-8 sm:py-12 border-b border-[#3A3A3A]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-right">
            <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
                
                <!-- User Welcome & VIP Badge -->
                <div class="flex items-center gap-4">
                    <div class="w-16 h-16 rounded-2xl bg-gradient-to-tr from-[#C5A059] to-[#E3C588] text-[#1C1C1C] font-black text-2xl flex items-center justify-center shadow-md">
                        {{ mb_substr($user->name, 0, 1) }}
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <h1 class="text-xl sm:text-2xl font-bold">{{ $user->name }}</h1>
                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-[#0B2E75]/20 text-[#FBB404] border border-[#FBB404]/40 text-[11px] font-bold">
                                <x-icon name="award" class="w-3.5 h-3.5 text-[#FBB404]" />
                                <span>عميل معتمد في متجر زاد نيكس</span>
                            </span>
                        </div>
                        <p class="text-xs text-[#A59D92] mt-1 font-mono" dir="ltr">{{ $user->email }}</p>
                    </div>
                </div>

                <!-- Wallet & Referral Balance Pill -->
                <div class="bg-white/10 backdrop-blur-md rounded-2xl p-4 border border-white/15 flex items-center gap-6 self-stretch md:self-auto justify-between md:justify-start">
                    <div class="text-right">
                        <span class="text-[11px] text-[#C5A059] font-bold block">رصيد أرباح الإحالات المتاح</span>
                        <div class="text-xl font-extrabold text-white mt-0.5">
                            {{ number_format($user->wallet_balance, 2) }} <span class="text-xs font-normal text-[#A59D92]">ر.س</span>
                        </div>
                    </div>
                    <a href="{{ route('customer.dashboard', ['tab' => 'affiliate']) }}" 
                       class="bg-[#C5A059] hover:bg-[#AF8B44] text-[#1C1C1C] font-bold text-xs py-2 px-3.5 rounded-xl transition shadow-xs flex items-center gap-1.5 shrink-0">
                        <x-icon name="wallet" class="w-4 h-4" />
                        <span>سحب الأرباح</span>
                    </a>
                </div>

            </div>
        </div>
    </div>

    <!-- Main Customer Dashboard Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12 text-right" 
         x-data="{ 
             activeTab: '{{ $activeTab }}',
             copied: false,
             copyReferralLink() {
                 navigator.clipboard.writeText('{{ $user->referral_link }}');
                 this.copied = true;
                 setTimeout(() => this.copied = false, 2500);
             }
         }">

        <!-- Global Alerts -->
        @if(session('success'))
        <div class="mb-6 p-4 rounded-2xl bg-[#E8F8EE] border border-[#25D366]/30 flex items-start gap-3">
            <x-icon name="check-circle" class="w-5 h-5 text-[#25D366] shrink-0 mt-0.5" />
            <div>
                <h4 class="text-xs font-bold text-[#1e824c]">تمت العملية بنجاح</h4>
                <p class="text-xs text-[#2c7744] mt-0.5">{{ session('success') }}</p>
            </div>
        </div>
        @endif

        @if($errors->any())
        <div class="mb-6 p-4 rounded-2xl bg-red-50 border border-red-200 text-red-700 text-xs space-y-1">
            @foreach($errors->all() as $err)
                <p>• {{ $err }}</p>
            @endforeach
        </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            
            <!-- Sidebar Navigation (3 cols) -->
            <div class="lg:col-span-3 bg-white rounded-3xl border border-[#EDE6DA] p-3 sm:p-4 shadow-xs space-y-1">
                
                <!-- Tab: Overview -->
                <button type="button" 
                        @click="activeTab = 'overview'" 
                        class="w-full text-right flex items-center justify-between px-4 py-3 rounded-2xl text-xs font-bold transition cursor-pointer"
                        :class="activeTab === 'overview' ? 'bg-[#1C1C1C] text-[#C5A059] shadow-xs' : 'text-[#242424] hover:bg-[#FAF8F4]'">
                    <span class="flex items-center gap-3">
                        <x-icon name="layout-dashboard" class="w-4 h-4 text-[#C5A059]" />
                        <span>نظرة عامة</span>
                    </span>
                    <x-icon name="chevron-left" class="w-3.5 h-3.5 text-[#A59D92]" />
                </button>

                <!-- Tab: Orders -->
                <button type="button" 
                        @click="activeTab = 'orders'" 
                        class="w-full text-right flex items-center justify-between px-4 py-3 rounded-2xl text-xs font-bold transition cursor-pointer"
                        :class="activeTab === 'orders' ? 'bg-[#1C1C1C] text-[#C5A059] shadow-xs' : 'text-[#242424] hover:bg-[#FAF8F4]'">
                    <span class="flex items-center gap-3">
                        <x-icon name="shopping-bag" class="w-4 h-4 text-[#C5A059]" />
                        <span>طلباتي وتتبع الشحنات</span>
                    </span>
                    <span class="text-[11px] px-2 py-0.5 rounded-full" :class="activeTab === 'orders' ? 'bg-[#C5A059] text-[#1C1C1C]' : 'bg-[#FAF8F4] text-[#A59D92]'">
                        {{ $totalOrdersCount }}
                    </span>
                </button>

                <!-- Tab: Affiliate & Referrals -->
                <button type="button" 
                        @click="activeTab = 'affiliate'" 
                        class="w-full text-right flex items-center justify-between px-4 py-3 rounded-2xl text-xs font-bold transition cursor-pointer"
                        :class="activeTab === 'affiliate' ? 'bg-[#1C1C1C] text-[#C5A059] shadow-xs' : 'text-[#242424] hover:bg-[#FAF8F4]'">
                    <span class="flex items-center gap-3">
                        <x-icon name="share-2" class="w-4 h-4 text-[#C5A059]" />
                        <span>برنامج الإحالات والأرباح</span>
                    </span>
                    <span class="text-[10px] px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-800 font-bold">10% عمولة</span>
                </button>

                <!-- Tab: Saved Addresses -->
                <button type="button" 
                        @click="activeTab = 'addresses'" 
                        class="w-full text-right flex items-center justify-between px-4 py-3 rounded-2xl text-xs font-bold transition cursor-pointer"
                        :class="activeTab === 'addresses' ? 'bg-[#1C1C1C] text-[#C5A059] shadow-xs' : 'text-[#242424] hover:bg-[#FAF8F4]'">
                    <span class="flex items-center gap-3">
                        <x-icon name="map-pin" class="w-4 h-4 text-[#C5A059]" />
                        <span>دفتر العناوين المحفوظة</span>
                    </span>
                    <span class="text-[11px] px-2 py-0.5 rounded-full bg-[#FAF8F4] text-[#A59D92]">
                        {{ $addresses->count() }}
                    </span>
                </button>

                <!-- Tab: Profile & Security -->
                <button type="button" 
                        @click="activeTab = 'profile'" 
                        class="w-full text-right flex items-center justify-between px-4 py-3 rounded-2xl text-xs font-bold transition cursor-pointer"
                        :class="activeTab === 'profile' ? 'bg-[#1C1C1C] text-[#C5A059] shadow-xs' : 'text-[#242424] hover:bg-[#FAF8F4]'">
                    <span class="flex items-center gap-3">
                        <x-icon name="user" class="w-4 h-4 text-[#C5A059]" />
                        <span>البيانات الشخصية والأمان</span>
                    </span>
                    <x-icon name="chevron-left" class="w-3.5 h-3.5 text-[#A59D92]" />
                </button>

                <!-- Divider & Logout -->
                <div class="pt-3 border-t border-[#FAF8F4]">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full text-right flex items-center gap-3 px-4 py-2.5 rounded-2xl text-xs font-bold text-red-500 hover:bg-red-50 transition cursor-pointer">
                            <x-icon name="log-out" class="w-4 h-4" />
                            <span>تسجيل الخروج</span>
                        </button>
                    </form>
                </div>

            </div>

            <!-- Main Dynamic Panels (9 cols) -->
            <div class="lg:col-span-9 space-y-8">
                
                <!-- ==================== TAB 1: OVERVIEW ==================== -->
                <div x-show="activeTab === 'overview'" class="space-y-8">
                    
                    <!-- 4 Summary Metrics Cards -->
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                        
                        <!-- Metric 1: Total Orders -->
                        <div class="bg-white p-5 rounded-2xl border border-[#EDE6DA] shadow-xs">
                            <span class="text-[11px] font-bold text-[#A59D92] block">إجمالي الطلبات</span>
                            <div class="text-2xl font-black text-[#242424] mt-1">{{ $totalOrdersCount }}</div>
                            <span class="text-[10px] text-[#777777] mt-1 block">طلبات سابقة وحالية</span>
                        </div>

                        <!-- Metric 2: Active Orders -->
                        <div class="bg-white p-5 rounded-2xl border border-[#EDE6DA] shadow-xs">
                            <span class="text-[11px] font-bold text-[#A59D92] block">طلبات جارية للتسليم</span>
                            <div class="text-2xl font-black text-[#C5A059] mt-1">{{ $activeOrdersCount }}</div>
                            <span class="text-[10px] text-emerald-600 mt-1 block">قيد الشحن والتجهيز</span>
                        </div>

                        <!-- Metric 3: Wallet Balance -->
                        <div class="bg-white p-5 rounded-2xl border border-[#EDE6DA] shadow-xs">
                            <span class="text-[11px] font-bold text-[#A59D92] block">رصيد أرباح الإحالات</span>
                            <div class="text-2xl font-black text-[#242424] mt-1">
                                {{ number_format($user->wallet_balance, 2) }}
                            </div>
                            <span class="text-[10px] text-[#A59D92] mt-1 block">ريال سعودي متاح</span>
                        </div>

                        <!-- Metric 4: Referral Rate -->
                        <div class="bg-white p-5 rounded-2xl border border-[#EDE6DA] shadow-xs">
                            <span class="text-[11px] font-bold text-[#A59D92] block">نسبة عمولتك للإحالات</span>
                            <div class="text-2xl font-black text-emerald-600 mt-1">10%</div>
                            <span class="text-[10px] text-[#777777] mt-1 block">على كل طلب مكتمل</span>
                        </div>

                    </div>

                    <!-- Instant Share Referral Box -->
                    <div class="bg-gradient-to-br from-[#1C1C1C] via-[#2A2A2A] to-[#1C1C1C] text-white rounded-3xl p-6 sm:p-8 shadow-md">
                        <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
                            <div class="space-y-2">
                                <span class="text-xs font-bold text-[#FBB404] uppercase tracking-wider block">برنامج أرباح ومكافآت زاد نيكس</span>
                                <h3 class="text-lg font-bold">شارك رابطك الخاص واكسب 10% عمولة نقدية</h3>
                                <p class="text-xs text-[#A59D92] max-w-xl leading-relaxed">
                                    أي عميل يشتري من المتجر عبر رابطك الفريد، تُضاف 10% من قيمة مشترياته فوراً إلى محفظتك كأرباح يمكنك سحبها لحسابك البنكي!
                                </p>
                            </div>
                            <div class="flex items-center gap-2 shrink-0">
                                <span class="text-xs text-[#A59D92]">كودك:</span>
                                <span class="font-mono text-sm font-black text-[#C5A059] bg-white/10 px-3 py-1.5 rounded-xl border border-white/15 tracking-wider">
                                    {{ $user->referral_code }}
                                </span>
                            </div>
                        </div>

                        <!-- Link & Action Buttons -->
                        <div class="mt-6 pt-5 border-t border-white/10 flex flex-col sm:flex-row items-stretch sm:items-center gap-3">
                            <div class="flex-1 bg-white/5 border border-white/15 rounded-xl px-3.5 py-2.5 text-xs font-mono text-[#E3CD98] truncate" dir="ltr">
                                {{ $user->referral_link }}
                            </div>
                            
                            <!-- Copy Button -->
                            <button type="button" 
                                    @click="copyReferralLink()" 
                                    class="bg-[#C5A059] hover:bg-[#AF8B44] text-[#1C1C1C] font-bold text-xs py-2.5 px-5 rounded-xl transition flex items-center justify-center gap-2 cursor-pointer shrink-0">
                                <x-icon name="copy" class="w-4 h-4" />
                                <span x-text="copied ? 'تم النسخ بنجاح!' : 'نسخ الرابط'">نسخ الرابط</span>
                            </button>

                            <!-- WhatsApp Share -->
                            <a href="https://wa.me/?text={{ urlencode('تسوق أجود المواد الغذائية والمستلزمات التموينية من متجر زاد نيكس عبر الرابط: ' . $user->referral_link) }}" 
                               target="_blank" 
                               class="bg-[#25D366] hover:bg-[#20ba5a] text-white font-bold text-xs py-2.5 px-4 rounded-xl transition flex items-center justify-center gap-2 cursor-pointer shrink-0">
                                <x-icon name="whatsapp" class="w-4 h-4" />
                                <span>واتساب</span>
                            </a>
                        </div>
                    </div>

                    <!-- Latest Orders Quick Preview -->
                    <div class="bg-white rounded-3xl border border-[#EDE6DA] p-6 shadow-xs">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-sm font-bold text-[#242424]">آخر الطلبات</h3>
                            <button type="button" @click="activeTab = 'orders'" class="text-xs font-bold text-[#C5A059] hover:underline">
                                عرض الكل ({{ $totalOrdersCount }}) ←
                            </button>
                        </div>

                        @if($orders->count() > 0)
                        <div class="divide-y divide-[#FAF8F4]">
                            @foreach($orders->take(3) as $order)
                            <div class="py-3 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
                                <div>
                                    <div class="flex items-center gap-2">
                                        <span class="font-mono text-xs font-bold text-[#242424]">{{ $order->order_number }}</span>
                                        <span class="text-[11px] text-[#A59D92]">{{ $order->created_at->format('Y-m-d') }}</span>
                                    </div>
                                    <div class="text-xs text-[#777777] mt-1">
                                        <span>{{ $order->items->count() }} منتجات</span>
                                        <span>•</span>
                                        <span class="font-bold text-[#242424]">{{ number_format($order->total, 2) }} ر.س</span>
                                    </div>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="px-2.5 py-1 rounded-md text-[11px] font-bold {{ $order->payment_status === 'paid' ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">
                                        {{ $order->payment_status_label_ar }}
                                    </span>
                                    <a href="{{ route('customer.orders.show', $order->order_number) }}" 
                                       class="text-xs font-bold text-[#C5A059] bg-[#FAF8F4] border border-[#EDE6DA] hover:bg-[#C5A059] hover:text-white px-3 py-1.5 rounded-lg transition">
                                        التفاصيل
                                    </a>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @else
                        <div class="text-center py-8 text-[#A59D92] text-xs">
                            لا توجد طلبات سابقة حتى الآن. 
                            <a href="{{ route('products.index') }}" class="text-[#C5A059] font-bold hover:underline mr-1">تصفح المنتجات الآن</a>
                        </div>
                        @endif
                    </div>

                </div>

                <!-- ==================== TAB 2: ORDERS & TRACKING ==================== -->
                <div x-show="activeTab === 'orders'" class="space-y-6">
                    <div class="flex items-center justify-between bg-white p-5 rounded-2xl border border-[#EDE6DA] shadow-xs">
                        <div>
                            <h2 class="text-base font-bold text-[#242424]">سجل طلباتي وتتبع الشحنات</h2>
                            <p class="text-xs text-[#777777] mt-0.5">يمكنك متابعة حالة كل طلب، معرفة الشحنات، وطباعة الفاتورة الضريبية.</p>
                        </div>
                        <span class="text-xs font-bold text-[#C5A059] bg-[#FAF8F4] border border-[#EDE6DA] px-3 py-1.5 rounded-xl">
                            إجمالي الطلبات: {{ $totalOrdersCount }}
                        </span>
                    </div>

                    @if($orders->count() > 0)
                    <div class="space-y-4">
                        @foreach($orders as $order)
                        <div class="bg-white rounded-3xl border border-[#EDE6DA] p-6 shadow-xs hover:border-[#C5A059] transition space-y-4">
                            
                            <!-- Order Header -->
                            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 pb-3 border-b border-[#FAF8F4]">
                                <div class="flex items-center gap-3">
                                    <span class="font-mono text-xs font-bold text-[#242424] bg-[#FAF8F4] border border-[#EDE6DA] px-2.5 py-1 rounded-lg">
                                        {{ $order->order_number }}
                                    </span>
                                    <span class="text-xs text-[#A59D92]">بتاريخ {{ $order->created_at->format('Y-m-d — h:i A') }}</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="px-2.5 py-1 rounded-md text-[11px] font-bold {{ $order->payment_status === 'paid' ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">
                                        {{ $order->payment_status_label_ar }}
                                    </span>
                                    <span class="px-2.5 py-1 rounded-md text-[11px] font-bold bg-blue-50 text-blue-800">
                                        {{ $order->status_label_ar }}
                                    </span>
                                </div>
                            </div>

                            <!-- Items List -->
                            <div class="divide-y divide-[#FAF8F4]">
                                @foreach($order->items as $item)
                                <div class="py-2.5 flex items-center justify-between text-xs">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-lg bg-[#FAF8F4] border border-[#EDE6DA] overflow-hidden shrink-0 flex items-center justify-center">
                                            @if($item->image_url)
                                                <img src="{{ $item->image_url }}" alt="{{ $item->product_name }}" class="w-full h-full object-cover">
                                            @else
                                                <x-icon name="package" class="w-4 h-4 text-[#C5A059]" />
                                            @endif
                                        </div>
                                        <div>
                                            <span class="font-bold text-[#242424] block">{{ $item->product_name }}</span>
                                            <span class="text-[11px] text-[#A59D92]">الكمية: {{ $item->quantity }} × {{ number_format($item->unit_price, 2) }} ر.س</span>
                                        </div>
                                    </div>
                                    <span class="font-bold text-[#242424]">{{ number_format($item->total_price, 2) }} ر.س</span>
                                </div>
                                @endforeach
                            </div>

                            <!-- Order Footer -->
                            <div class="pt-3 border-t border-[#FAF8F4] flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
                                <div class="text-xs text-[#777777]">
                                    <span>عنوان التوصيل: <strong>{{ $order->city }}، {{ $order->street_address }}</strong></span>
                                </div>
                                <div class="flex items-center gap-3">
                                    <span class="text-xs text-[#777777]">المجموع الكلي: <strong class="text-sm font-black text-[#242424]">{{ number_format($order->total, 2) }} ر.س</strong></span>
                                    <a href="{{ route('customer.orders.show', $order->order_number) }}" 
                                       class="bg-[#C5A059] hover:bg-[#AF8B44] text-white text-xs font-bold px-4 py-2 rounded-xl transition flex items-center gap-1.5">
                                        <span>تفاصيل الفاتورة والتتبع</span>
                                        <x-icon name="arrow-left" class="w-3.5 h-3.5" />
                                    </a>
                                </div>
                            </div>

                        </div>
                        @endforeach

                        <div class="pt-4">
                            {{ $orders->appends(['tab' => 'orders'])->links() }}
                        </div>
                    </div>
                    @else
                    <div class="bg-white rounded-3xl border border-[#EDE6DA] p-12 text-center space-y-4">
                        <div class="w-16 h-16 mx-auto rounded-full bg-[#FAF8F4] flex items-center justify-center text-[#A59D92]">
                            <x-icon name="shopping-bag" class="w-8 h-8" />
                        </div>
                        <h3 class="text-base font-bold text-[#242424]">لم تقم بإنشاء أي طلبات حتى الآن</h3>
                        <p class="text-xs text-[#777777]">تصفح تشكيلة المواد الغذائية والمنظفات المنزلية الآن.</p>
                        <a href="{{ route('products.index') }}" class="inline-block bg-[#C5A059] text-white text-xs font-bold px-6 py-2.5 rounded-xl hover:bg-[#AF8B44] transition">
                            تسوق الآن
                        </a>
                    </div>
                    @endif
                </div>

                <!-- ==================== TAB 3: AFFILIATE & REFERRALS ==================== -->
                <div x-show="activeTab === 'affiliate'" class="space-y-8">
                    
                    <!-- Affiliate Banner -->
                    <div class="bg-gradient-to-br from-[#1C1C1C] via-[#2A2A2A] to-[#1C1C1C] text-white rounded-3xl p-6 sm:p-8 shadow-md space-y-6">
                        <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
                            <div>
                                <span class="text-xs font-bold text-[#C5A059] uppercase tracking-wider block">برنامج المسوقين وشركاء النجاح</span>
                                <h2 class="text-xl font-bold mt-1">اكسب 10% عمولة نقدية على كل طلب</h2>
                                <p class="text-xs text-[#A59D92] mt-1 max-w-xl leading-relaxed">
                                    انسخ رابطك الخاص وشاركه عبر مواقع التواصل الاجتماعي أو مع أصدقائك. أي عملية شراء تتم من طرفك ستكسب منها عمولة 10% فورية في محفظتك!
                                </p>
                            </div>
                            <div class="text-left bg-white/10 p-4 rounded-2xl border border-white/15 shrink-0" dir="ltr">
                                <span class="text-[10px] text-[#A59D92] uppercase block">YOUR REFERRAL CODE</span>
                                <span class="text-lg font-black text-[#C5A059] font-mono tracking-wider">{{ $user->referral_code }}</span>
                            </div>
                        </div>

                        <!-- Link Copy Box -->
                        <div class="bg-white/5 border border-white/15 rounded-2xl p-4 flex flex-col sm:flex-row items-stretch sm:items-center gap-3">
                            <div class="flex-1 text-xs font-mono text-[#E3CD98] truncate px-2" dir="ltr">
                                {{ $user->referral_link }}
                            </div>
                            <button type="button" 
                                    @click="copyReferralLink()" 
                                    class="bg-[#C5A059] hover:bg-[#AF8B44] text-[#1C1C1C] font-bold text-xs py-2.5 px-6 rounded-xl transition flex items-center justify-center gap-2 cursor-pointer shrink-0">
                                <x-icon name="copy" class="w-4 h-4" />
                                <span x-text="copied ? 'تم النسخ!' : 'نسخ الرابط'">نسخ الرابط</span>
                            </button>
                        </div>
                    </div>

                    <!-- Financial Summary & Payout Request -->
                    <div class="grid grid-cols-1 md:grid-cols-12 gap-6 items-start">
                        
                        <!-- Left: Payout Request Form (7 cols) -->
                        <div class="md:col-span-7 bg-white rounded-3xl border border-[#EDE6DA] p-6 sm:p-8 shadow-xs">
                            <h3 class="text-sm font-bold text-[#242424] mb-1">طلب سحب الأرباح لحسابك البنكي</h3>
                            <p class="text-xs text-[#777777] mb-6">
                                الحد الأدنى للتحويل هو 50 ر.س. يتم تحويل المبالغ لحسابك البنكي السعودي خلال 24-48 ساعة.
                            </p>

                            <form action="{{ route('customer.payout.request') }}" method="POST" class="space-y-4">
                                @csrf

                                <!-- Amount -->
                                <div>
                                    <label class="block text-xs font-bold text-[#242424] mb-1.5">
                                        المبلغ المطلوب سحبه (ر.س) <span class="text-red-500">*</span>
                                    </label>
                                    <input type="number" 
                                           step="0.01" 
                                           name="amount" 
                                           min="50" 
                                           max="{{ $user->wallet_balance }}" 
                                           value="{{ old('amount', $user->wallet_balance > 50 ? $user->wallet_balance : 50) }}" 
                                           required 
                                           class="w-full bg-[#FAF8F4] border border-[#EDE6DA] rounded-xl px-3.5 py-2.5 text-xs text-[#242424] focus:outline-none focus:border-[#C5A059]">
                                    <span class="text-[11px] text-[#A59D92] mt-1 block">رصيدك المتاح حالياً: {{ number_format($user->wallet_balance, 2) }} ر.س</span>
                                </div>

                                <!-- Bank Name -->
                                <div>
                                    <label class="block text-xs font-bold text-[#242424] mb-1.5">
                                        اسم البنك <span class="text-red-500">*</span>
                                    </label>
                                    <select name="bank_name" required class="w-full bg-[#FAF8F4] border border-[#EDE6DA] rounded-xl px-3.5 py-2.5 text-xs text-[#242424] focus:outline-none focus:border-[#C5A059]">
                                        <option value="مصرف الراجحي">مصرف الراجحي</option>
                                        <option value="البنك الأهلي السعودي (SNB)">البنك الأهلي السعودي (SNB)</option>
                                        <option value="مصرف الإنماء">مصرف الإنماء</option>
                                        <option value="بنك الرياض">بنك الرياض</option>
                                        <option value="بنك البلاد">بنك البلاد</option>
                                        <option value="البنك السعودي الأول (SAB)">البنك السعودي الأول (SAB)</option>
                                        <option value="بنك الجزيرة">بنك الجزيرة</option>
                                        <option value="STC Bank">STC Bank</option>
                                        <option value="بنك آخر">بنك سعودي آخر</option>
                                    </select>
                                </div>

                                <!-- Account Name -->
                                <div>
                                    <label class="block text-xs font-bold text-[#242424] mb-1.5">
                                        اسم صاحب الحساب الثلاثي <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" 
                                           name="account_name" 
                                           value="{{ old('account_name', $user->name) }}" 
                                           required 
                                           placeholder="مطابق للاسم المسجل في البنك" 
                                           class="w-full bg-[#FAF8F4] border border-[#EDE6DA] rounded-xl px-3.5 py-2.5 text-xs text-[#242424] focus:outline-none focus:border-[#C5A059]">
                                </div>

                                <!-- IBAN -->
                                <div>
                                    <label class="block text-xs font-bold text-[#242424] mb-1.5">
                                        رقم الآيبان البنكي (IBAN) <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" 
                                           name="iban" 
                                           value="{{ old('iban') }}" 
                                           required 
                                           placeholder="SA0000000000000000000000" 
                                           maxlength="24" 
                                           class="w-full bg-[#FAF8F4] border border-[#EDE6DA] rounded-xl px-3.5 py-2.5 text-xs text-[#242424] font-mono uppercase focus:outline-none focus:border-[#C5A059] text-left" 
                                           dir="ltr">
                                </div>

                                <button type="submit" 
                                        @if($user->wallet_balance < 50) disabled @endif 
                                        class="w-full bg-[#C5A059] hover:bg-[#AF8B44] disabled:opacity-50 disabled:cursor-not-allowed text-white font-bold text-xs py-3 px-4 rounded-xl shadow-xs transition duration-200">
                                    تأكيد طلب التحويل البنكي
                                </button>
                            </form>
                        </div>

                        <!-- Right: Payout History & Earnings (5 cols) -->
                        <div class="md:col-span-5 space-y-6">
                            
                            <!-- Earnings Card -->
                            <div class="bg-white rounded-3xl border border-[#EDE6DA] p-6 shadow-xs">
                                <h4 class="text-xs font-bold text-[#A59D92] uppercase">إجمالي العمولات المكتسبة</h4>
                                <div class="text-3xl font-black text-[#242424] mt-1">
                                    {{ number_format($totalEarnings, 2) }} <span class="text-xs font-normal text-[#A59D92]">ر.س</span>
                                </div>
                                <div class="mt-4 pt-4 border-t border-[#FAF8F4] flex items-center justify-between text-xs">
                                    <span class="text-[#777777]">الطلبات المكتملة عبرك:</span>
                                    <strong class="text-[#242424]">{{ $totalReferralsCount }} طلب</strong>
                                </div>
                            </div>

                            <!-- Payout Requests History -->
                            <div class="bg-white rounded-3xl border border-[#EDE6DA] p-6 shadow-xs">
                                <h4 class="text-xs font-bold text-[#242424] mb-3">سجل طلبات السحب</h4>
                                
                                @if($payoutRequests->count() > 0)
                                <div class="divide-y divide-[#FAF8F4] text-xs">
                                    @foreach($payoutRequests as $req)
                                    <div class="py-2.5 flex items-center justify-between">
                                        <div>
                                            <span class="font-bold text-[#242424] block">{{ number_format($req->amount, 2) }} ر.س</span>
                                            <span class="text-[10px] text-[#A59D92]">{{ $req->created_at->format('Y-m-d') }} • {{ $req->bank_name }}</span>
                                        </div>
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold 
                                            {{ $req->status === 'completed' ? 'bg-emerald-100 text-emerald-800' : ($req->status === 'rejected' ? 'bg-red-100 text-red-800' : 'bg-amber-100 text-amber-800') }}">
                                            {{ $req->status === 'completed' ? 'تم التحويل' : ($req->status === 'rejected' ? 'مرفوض' : 'قيد المراجعة') }}
                                        </span>
                                    </div>
                                    @endforeach
                                </div>
                                @else
                                <p class="text-xs text-[#A59D92] text-center py-4">لم تقم بطلب سحب أرباح بعد.</p>
                                @endif
                            </div>

                        </div>

                    </div>

                </div>

                <!-- ==================== TAB 4: ADDRESSES ==================== -->
                <div x-show="activeTab === 'addresses'" class="space-y-6" x-data="{ showAddressModal: false }">
                    <div class="flex items-center justify-between bg-white p-5 rounded-2xl border border-[#EDE6DA] shadow-xs">
                        <div>
                            <h2 class="text-base font-bold text-[#242424]">دفتر العناوين المحفوظة</h2>
                            <p class="text-xs text-[#777777] mt-0.5">احفظ عناوين التوصيل المتكررة لإنهاء طلباتك القادمة بنقرة واحدة.</p>
                        </div>
                        <button type="button" 
                                @click="showAddressModal = !showAddressModal" 
                                class="bg-[#C5A059] hover:bg-[#AF8B44] text-white text-xs font-bold px-4 py-2 rounded-xl transition flex items-center gap-1.5 cursor-pointer">
                            <x-icon name="plus" class="w-4 h-4" />
                            <span>إضافة عنوان جديد</span>
                        </button>
                    </div>

                    <!-- Add Address Form (Collapsible) -->
                    <div x-show="showAddressModal" x-collapse class="bg-white rounded-3xl border border-[#C5A059] p-6 sm:p-8 shadow-md">
                        <div class="flex items-center justify-between pb-3 border-b border-[#FAF8F4] mb-4">
                            <h3 class="text-sm font-bold text-[#242424]">إضافة عنوان توصيل جديد</h3>
                            <button type="button" @click="showAddressModal = false" class="text-xs text-[#A59D92] hover:text-[#242424]">إلغاء</button>
                        </div>

                        <form action="{{ route('customer.addresses.store') }}" method="POST" class="space-y-4">
                            @csrf

                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-[#242424] mb-1.5">تسمية العنوان (مثال: المنزل / العمل)</label>
                                    <input type="text" name="title" value="المنزل" required class="w-full bg-[#FAF8F4] border border-[#EDE6DA] rounded-xl px-3 py-2 text-xs">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-[#242424] mb-1.5">اسم المستلم</label>
                                    <input type="text" name="recipient_name" value="{{ $user->name }}" required class="w-full bg-[#FAF8F4] border border-[#EDE6DA] rounded-xl px-3 py-2 text-xs">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-[#242424] mb-1.5">رقم جوال المستلم</label>
                                    <input type="tel" name="recipient_phone" value="{{ $user->phone }}" required placeholder="05XXXXXXXX" class="w-full bg-[#FAF8F4] border border-[#EDE6DA] rounded-xl px-3 py-2 text-xs" dir="ltr">
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-[#242424] mb-1.5">المدينة</label>
                                    <select name="city" required class="w-full bg-[#FAF8F4] border border-[#EDE6DA] rounded-xl px-3 py-2 text-xs">
                                        <option value="الرياض">الرياض</option>
                                        <option value="جدة">جدة</option>
                                        <option value="مكة المكرمة">مكة المكرمة</option>
                                        <option value="المدينة المنورة">المدينة المنورة</option>
                                        <option value="الدمام">الدمام</option>
                                        <option value="الخبر">الخبر</option>
                                        <option value="الطائف">الطائف</option>
                                        <option value="تبوك">تبوك</option>
                                        <option value="أبها">أبها</option>
                                        <option value="بريدة">بريدة</option>
                                        <option value="خميس مشيط">خميس مشيط</option>
                                        <option value="حائل">حائل</option>
                                        <option value="نجران">نجران</option>
                                        <option value="جازان">جازان</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-[#242424] mb-1.5">الحي</label>
                                    <input type="text" name="district" placeholder="مثال: النرجس" class="w-full bg-[#FAF8F4] border border-[#EDE6DA] rounded-xl px-3 py-2 text-xs">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-[#242424] mb-1.5">اسم الشارع وتفاصيل العنوان</label>
                                    <input type="text" name="street_address" required placeholder="مثال: طريق الملك فهد، بجوار..." class="w-full bg-[#FAF8F4] border border-[#EDE6DA] rounded-xl px-3 py-2 text-xs">
                                </div>
                            </div>

                            <div class="flex items-center justify-between pt-2">
                                <label class="flex items-center gap-2 text-xs text-[#242424] cursor-pointer">
                                    <input type="checkbox" name="is_default" value="1" class="text-[#C5A059] rounded">
                                    <span>تعيين كعنوان رئيسي للتوصيل</span>
                                </label>
                                <button type="submit" class="bg-[#C5A059] hover:bg-[#AF8B44] text-white text-xs font-bold px-6 py-2.5 rounded-xl transition">
                                    حفظ العنوان
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Addresses Grid -->
                    @if($addresses->count() > 0)
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        @foreach($addresses as $addr)
                        <div class="bg-white rounded-3xl border {{ $addr->is_default ? 'border-[#C5A059] shadow-sm' : 'border-[#EDE6DA]' }} p-6 space-y-3 relative">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <span class="w-2 h-2 rounded-full {{ $addr->is_default ? 'bg-[#C5A059]' : 'bg-[#A59D92]' }}"></span>
                                    <h4 class="text-xs font-bold text-[#242424]">{{ $addr->title }}</h4>
                                    @if($addr->is_default)
                                        <span class="text-[10px] bg-[#FAF8F4] text-[#C5A059] border border-[#EDE6DA] px-2 py-0.5 rounded-md font-bold">العنوان الرئيسي</span>
                                    @endif
                                </div>
                                <form method="POST" action="{{ route('customer.addresses.destroy', $addr->id) }}" onsubmit="return confirm('هل أنت متأكد من حذف هذا العنوان؟');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-[#A59D92] hover:text-red-500 p-1">
                                        <x-icon name="trash" class="w-3.5 h-3.5" />
                                    </button>
                                </form>
                            </div>

                            <p class="text-xs text-[#555555] leading-relaxed">
                                {{ $addr->city }}@if($addr->district)، حي {{ $addr->district }}@endif، {{ $addr->street_address }}
                            </p>

                            <div class="pt-2 border-t border-[#FAF8F4] flex items-center justify-between text-xs text-[#A59D92]">
                                <span>المستلم: {{ $addr->recipient_name }} ({{ $addr->recipient_phone }})</span>
                                @if(!$addr->is_default)
                                <form method="POST" action="{{ route('customer.addresses.default', $addr->id) }}">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="text-xs font-bold text-[#C5A059] hover:underline">
                                        جعله الرئيسي
                                    </button>
                                </form>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <div class="bg-white rounded-3xl border border-[#EDE6DA] p-10 text-center text-xs text-[#A59D92]">
                        لا توجد عناوين محفوظة حتى الآن. اضغط على "إضافة عنوان جديد" لحفظ عنوانك.
                    </div>
                    @endif
                </div>

                <!-- ==================== TAB 5: PROFILE & SECURITY ==================== -->
                <div x-show="activeTab === 'profile'" class="bg-white rounded-3xl border border-[#EDE6DA] p-6 sm:p-10 shadow-xs space-y-6">
                    <div class="border-b border-[#FAF8F4] pb-4">
                        <h2 class="text-base font-bold text-[#242424]">تعديل البيانات الشخصية وكلمة المرور</h2>
                        <p class="text-xs text-[#777777] mt-0.5">حافظ على سرية وتحديث بيانات حسابك لتجربة تسوق آمنة وسريعة.</p>
                    </div>

                    <form action="{{ route('customer.profile.update') }}" method="POST" class="space-y-5 max-w-xl">
                        @csrf

                        <!-- Name -->
                        <div>
                            <label class="block text-xs font-bold text-[#242424] mb-1.5">الاسم الكريم</label>
                            <input type="text" name="name" value="{{ old('name', $user->name) }}" required class="w-full bg-[#FAF8F4] border border-[#EDE6DA] rounded-xl px-3.5 py-2.5 text-xs text-[#242424] focus:outline-none focus:border-[#C5A059]">
                        </div>

                        <!-- Email (read-only for security) -->
                        <div>
                            <label class="block text-xs font-bold text-[#242424] mb-1.5">البريد الإلكتروني</label>
                            <input type="email" value="{{ $user->email }}" disabled class="w-full bg-gray-100 border border-gray-200 rounded-xl px-3.5 py-2.5 text-xs text-gray-500 cursor-not-allowed text-left font-mono" dir="ltr">
                            <span class="text-[10px] text-[#A59D92] mt-1 block">البريد الإلكتروني مرتبط بحسابك ولا يمكن تعديله لحماية أمان الحساب.</span>
                        </div>

                        <!-- Phone -->
                        <div>
                            <label class="block text-xs font-bold text-[#242424] mb-1.5">رقم الجوال للتواصل</label>
                            <input type="tel" name="phone" value="{{ old('phone', $user->phone) }}" placeholder="05XXXXXXXX" class="w-full bg-[#FAF8F4] border border-[#EDE6DA] rounded-xl px-3.5 py-2.5 text-xs text-[#242424] focus:outline-none focus:border-[#C5A059] text-left" dir="ltr">
                        </div>

                        <!-- Password change section -->
                        <div class="pt-4 border-t border-[#FAF8F4] space-y-4">
                            <h3 class="text-xs font-bold text-[#242424]">تغيير كلمة المرور (اختياري)</h3>

                            <div>
                                <label class="block text-xs font-medium text-[#777777] mb-1">كلمة المرور الحالية</label>
                                <input type="password" name="current_password" placeholder="أدخل كلمة المرور الحالية" class="w-full bg-[#FAF8F4] border border-[#EDE6DA] rounded-xl px-3.5 py-2.5 text-xs text-[#242424] focus:outline-none focus:border-[#C5A059]">
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-medium text-[#777777] mb-1">كلمة المرور الجديدة</label>
                                    <input type="password" name="new_password" placeholder="لا تقل عن 8 أحرف" class="w-full bg-[#FAF8F4] border border-[#EDE6DA] rounded-xl px-3.5 py-2.5 text-xs text-[#242424] focus:outline-none focus:border-[#C5A059]">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-[#777777] mb-1">تأكيد كلمة المرور الجديدة</label>
                                    <input type="password" name="new_password_confirmation" placeholder="أعد إدخال كلمة المرور" class="w-full bg-[#FAF8F4] border border-[#EDE6DA] rounded-xl px-3.5 py-2.5 text-xs text-[#242424] focus:outline-none focus:border-[#C5A059]">
                                </div>
                            </div>
                        </div>

                        <div class="pt-2">
                            <button type="submit" class="bg-[#C5A059] hover:bg-[#AF8B44] text-white font-bold text-xs py-3 px-8 rounded-xl shadow-xs transition">
                                حفظ التغييرات
                            </button>
                        </div>
                    </form>
                </div>

            </div>

        </div>

    </div>
</x-app-layout>

