<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'لوحة التحكم' }} | متجر زاد نيكس</title>

    <!-- Official Zad Nex Logo -->
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}?v=2">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}?v=2">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}?v=2">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}?v=2">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans+Arabic:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        [x-cloak] { display: none !important; }

        @media print {
            aside, header, nav, .print\:hidden, .no-print {
                display: none !important;
            }
            body, .min-h-screen, main {
                background: #ffffff !important;
                background-color: #ffffff !important;
                padding: 0 !important;
                margin: 0 !important;
                width: 100% !important;
                min-width: 100% !important;
            }
        }
    </style>
</head>
<body class="bg-[#F8F9FA] text-[#242424] antialiased" x-data="{ sidebarOpen: false }">
    <div class="min-h-screen flex">
        
        <!-- Sidebar Navigation -->
        <aside class="w-64 bg-[#1E1E1E] text-white flex-shrink-0 flex flex-col justify-between hidden md:flex border-l border-[#2E2E2E]">
            <div>
                <!-- Brand / Logo -->
                <div class="h-20 flex items-center px-6 border-b border-[#2E2E2E]">
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3">
                        <img src="{{ asset('images/logo.png') }}" alt="متجر زاد نيكس" class="h-10 w-auto object-contain">
                        <div class="flex flex-col">
                            <span class="text-sm font-black text-[#FBB404] tracking-wide">زاد نيكس</span>
                            <span class="text-[10px] text-gray-400">لوحة الإدارة والتحكم</span>
                        </div>
                    </a>
                </div>

                <!-- Navigation Links -->
                <nav class="p-4 space-y-1 text-xs font-semibold">
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3.5 py-3 rounded-xl transition {{ request()->routeIs('admin.dashboard') ? 'bg-[#0B2E75] text-white font-bold border-r-4 border-[#FBB404]' : 'text-[#A59D92] hover:bg-[#2A2A2A] hover:text-white' }}">
                        <x-icon name="home" class="w-4 h-4" />
                        <span>لوحة المؤشرات العامة</span>
                    </a>

                    <a href="{{ route('admin.orders.index') }}" class="flex items-center gap-3 px-3.5 py-3 rounded-xl transition {{ request()->routeIs('admin.orders*') ? 'bg-[#0B2E75] text-white font-bold border-r-4 border-[#FBB404]' : 'text-[#A59D92] hover:bg-[#2A2A2A] hover:text-white' }}">
                        <x-icon name="shopping-bag" class="w-4 h-4" />
                        <span>إدارة الطلبات</span>
                    </a>

                    <a href="{{ route('admin.products.index') }}" class="flex items-center gap-3 px-3.5 py-3 rounded-xl transition {{ request()->routeIs('admin.products*') ? 'bg-[#0B2E75] text-white font-bold border-r-4 border-[#FBB404]' : 'text-[#A59D92] hover:bg-[#2A2A2A] hover:text-white' }}">
                        <x-icon name="package" class="w-4 h-4" />
                        <span>المنتجات والمخزون</span>
                    </a>

                    <a href="{{ route('admin.categories.index') }}" class="flex items-center gap-3 px-3.5 py-3 rounded-xl transition {{ request()->routeIs('admin.categories*') ? 'bg-[#0B2E75] text-white font-bold border-r-4 border-[#FBB404]' : 'text-[#A59D92] hover:bg-[#2A2A2A] hover:text-white' }}">
                        <x-icon name="filter" class="w-4 h-4" />
                        <span>أقسام وتصنيفات المتجر</span>
                    </a>

                    <a href="{{ route('admin.coupons.index') }}" class="flex items-center gap-3 px-3.5 py-3 rounded-xl transition {{ request()->routeIs('admin.coupons*') ? 'bg-[#0B2E75] text-white font-bold border-r-4 border-[#FBB404]' : 'text-[#A59D92] hover:bg-[#2A2A2A] hover:text-white' }}">
                        <x-icon name="check-circle" class="w-4 h-4" />
                        <span>قسائم الخصم والكوبونات</span>
                    </a>

                    <a href="{{ route('admin.banners.index') }}" class="flex items-center gap-3 px-3.5 py-3 rounded-xl transition {{ request()->routeIs('admin.banners*') ? 'bg-[#0B2E75] text-white font-bold border-r-4 border-[#FBB404]' : 'text-[#A59D92] hover:bg-[#2A2A2A] hover:text-white' }}">
                        <x-icon name="image" class="w-4 h-4" />
                        <span>إدارة الصفحة الرئيسية والبنرات</span>
                    </a>

                    <a href="{{ route('admin.popup.index') }}" class="flex items-center gap-3 px-3.5 py-3 rounded-xl transition {{ request()->routeIs('admin.popup*') ? 'bg-[#0B2E75] text-white font-bold border-r-4 border-[#FBB404]' : 'text-[#A59D92] hover:bg-[#2A2A2A] hover:text-white' }}">
                        <x-icon name="award" class="w-4 h-4" />
                        <span>عروض الإعلانات المفاجئة</span>
                    </a>

                    <a href="{{ route('admin.shipping.index') }}" class="flex items-center gap-3 px-3.5 py-3 rounded-xl transition {{ request()->routeIs('admin.shipping*') ? 'bg-[#0B2E75] text-white font-bold border-r-4 border-[#FBB404]' : 'text-[#A59D92] hover:bg-[#2A2A2A] hover:text-white' }}">
                        <x-icon name="truck" class="w-4 h-4" />
                        <span>مناطق ورسوم الشحن</span>
                    </a>

                    <a href="{{ route('admin.settings.index') }}" class="flex items-center gap-3 px-3.5 py-3 rounded-xl transition {{ request()->routeIs('admin.settings*') ? 'bg-[#0B2E75] text-white font-bold border-r-4 border-[#FBB404]' : 'text-[#A59D92] hover:bg-[#2A2A2A] hover:text-white' }}">
                        <x-icon name="shield-check" class="w-4 h-4" />
                        <span>إعدادات المتجر العامة</span>
                    </a>

                    <a href="{{ route('admin.profile') }}" class="flex items-center gap-3 px-3.5 py-3 rounded-xl transition {{ request()->routeIs('admin.profile*') ? 'bg-[#0B2E75] text-white font-bold border-r-4 border-[#FBB404]' : 'text-[#A59D92] hover:bg-[#2A2A2A] hover:text-white' }}">
                        <x-icon name="user" class="w-4 h-4" />
                        <span>حساب المدير والأمان</span>
                    </a>

                    <a href="{{ route('admin.messages.index') }}" class="flex items-center gap-3 px-3.5 py-3 rounded-xl transition {{ request()->routeIs('admin.messages*') ? 'bg-[#0B2E75] text-white font-bold border-r-4 border-[#FBB404]' : 'text-[#A59D92] hover:bg-[#2A2A2A] hover:text-white' }}">
                        <x-icon name="mail" class="w-4 h-4" />
                        <span>رسائل واستفسارات العملاء</span>
                        @if(\App\Models\ContactMessage::where('is_read', false)->count() > 0)
                            <span class="mr-auto bg-[#FBB404] text-[#071E50] text-[10px] font-black px-1.5 py-0.5 rounded-full">
                                {{ \App\Models\ContactMessage::where('is_read', false)->count() }}
                            </span>
                        @endif
                    </a>

                    <a href="{{ route('admin.affiliates.index') }}" class="flex items-center gap-3 px-3.5 py-3 rounded-xl transition {{ request()->routeIs('admin.affiliates*') ? 'bg-[#0B2E75] text-white font-bold border-r-4 border-[#FBB404]' : 'text-[#A59D92] hover:bg-[#2A2A2A] hover:text-white' }}">
                        <x-icon name="share-2" class="w-4 h-4" />
                        <span>المسوقون وعمولات الإحالة</span>
                        @if(\App\Models\PayoutRequest::where('status', 'pending')->count() > 0)
                            <span class="mr-auto bg-[#FBB404] text-[#071E50] text-[10px] font-black px-1.5 py-0.5 rounded-full">
                                {{ \App\Models\PayoutRequest::where('status', 'pending')->count() }}
                            </span>
                        @endif
                    </a>
                </nav>
            </div>

            <!-- Bottom user / return to storefront -->
            <div class="p-4 border-t border-[#2E2E2E] space-y-2 text-xs">
                <a href="{{ route('home') }}" target="_blank" class="flex items-center gap-2 text-[#FBB404] hover:underline px-3 py-2 font-bold">
                    <x-icon name="arrow-right" class="w-4 h-4" />
                    <span>معاينة واجهة المتجر</span>
                </a>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full text-right text-red-400 hover:text-red-300 px-3 py-2">
                        تسجيل الخروج
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content Layout -->
        <div class="flex-1 flex flex-col min-w-0 overflow-y-auto">
            
            <!-- Top App Bar -->
            <header class="h-20 bg-white border-b border-[#E5E7EB] px-6 sm:px-8 flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <button @click="sidebarOpen = !sidebarOpen" class="md:hidden p-2 text-gray-600">
                        <x-icon name="menu" class="w-6 h-6" />
                    </button>
                    <h1 class="text-base sm:text-lg font-bold text-[#242424]">{{ $title ?? 'لوحة الإدارة' }}</h1>
                </div>

                <div class="flex items-center gap-4 text-xs">
                    <!-- Environment Mode Pill -->
                    @php
                        $paymentMode = \App\Models\StoreSetting::get('paymob_mode', config('services.paymob.mode', 'mock'));
                    @endphp
                    @if($paymentMode === 'live')
                        <span class="bg-emerald-50 text-emerald-800 border border-emerald-300 px-3 py-1 rounded-full font-bold flex items-center gap-1.5 shadow-xs">
                            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                            وضع الدفع الحي (Live Mode)
                        </span>
                    @else
                        <span class="bg-amber-100 text-amber-800 border border-amber-300 px-3 py-1 rounded-full font-bold flex items-center gap-1.5 shadow-xs">
                            <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                            وضع الدفع التجريبي (Mock Mode)
                        </span>
                    @endif

                    <a href="{{ route('admin.profile') }}" class="hidden sm:flex items-center gap-2 text-gray-700 hover:text-[#0B2E75] transition px-3 py-1.5 rounded-xl hover:bg-gray-50 border border-transparent hover:border-gray-200" title="إعدادات الحساب وكلمة المرور">
                        <x-icon name="user" class="w-4 h-4 text-[#0B2E75]" />
                        <span class="font-bold">{{ auth()->user()?->name ?? 'المدير' }}</span>
                    </a>
                </div>
            </header>

            <!-- Page Body -->
            <main class="flex-1 p-6 sm:p-8">
                @if(session('success'))
                    <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs rounded-xl flex items-center gap-2">
                        <x-icon name="check-circle" class="w-4 h-4 text-emerald-600" />
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-700 text-xs rounded-xl flex items-center gap-2">
                        <x-icon name="x" class="w-4 h-4 text-red-600" />
                        <span>{{ session('error') }}</span>
                    </div>
                @endif

                {{ $slot }}
            </main>

            <!-- Admin Footer -->
            <footer class="px-6 sm:px-8 py-4 border-t border-gray-200 bg-white text-xs text-gray-500 flex flex-col sm:flex-row items-center justify-between gap-2">
                <span>متجر زاد نيكس &copy; {{ date('Y') }} — لوحة الإدارة والتحكم</span>
                <span>صُمم بواسطة <a href="https://wa.me/966543907572" target="_blank" rel="noopener noreferrer" class="font-bold text-[#0B2E75] hover:text-[#FBB404] transition">رواد الرقمية</a></span>
            </footer>
        </div>
    </div>
</body>
</html>

