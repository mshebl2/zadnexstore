<!DOCTYPE html>
<html lang="ar" dir="rtl" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @php
        $siteMetaTitle = \App\Models\StoreSetting::get('meta_title', 'متجر زاد نيكس | ZADNEX STORE — مواد غذائية، مشروبات، منظفات، كماليات');
        $siteMetaDesc = \App\Models\StoreSetting::get('meta_description', 'متجر زاد نيكس لتجارة التجزئة للمواد الغذائية، المشروبات، المنظفات، والكماليات بمكة المكرمة. سجل تجاري 7055435114 وشهادة سلامة معتمدة 101565890.');
        $siteMetaKeywords = \App\Models\StoreSetting::get('meta_keywords', 'زاد نيكس, متجر زاد نيكس, مواد غذائية, مشروبات, منظفات, كماليات, بقالة, مكة المكرمة, أم الجود');
        
        $gVerification = \App\Models\StoreSetting::get('google_site_verification');
        if ($gVerification && preg_match('/content="([^"]+)"/i', $gVerification, $m)) {
            $gVerification = $m[1];
        }

        $gaId = \App\Models\StoreSetting::get('google_analytics_id');
        $gtmId = \App\Models\StoreSetting::get('google_tag_manager_id');
        $gAdsId = \App\Models\StoreSetting::get('google_ads_id');
        $metaPixelId = \App\Models\StoreSetting::get('meta_pixel_id');
        $tiktokPixelId = \App\Models\StoreSetting::get('tiktok_pixel_id');
        $snapchatPixelId = \App\Models\StoreSetting::get('snapchat_pixel_id');
        $headScripts = \App\Models\StoreSetting::get('custom_head_scripts');
        $bodyScripts = \App\Models\StoreSetting::get('custom_body_scripts');
    @endphp

    <title>{{ $title ?? $siteMetaTitle }}</title>
    <meta name="description" content="{{ $description ?? $siteMetaDesc }}">
    <meta name="keywords" content="{{ $keywords ?? $siteMetaKeywords }}">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="{{ url()->current() }}">

    <!-- Brand Favicon (Official Zad Nex Logo) -->
    <link rel="icon" type="image/jpeg" href="{{ asset('images/logo.png') }}?v=2">
    <link rel="apple-touch-icon" href="{{ asset('images/logo.png') }}?v=2">

    <!-- Schema.org Store Structured Data -->
    <script type="application/ld+json">
    {
      "{{ '@' }}context": "https://schema.org",
      "{{ '@' }}type": "GroceryStore",
      "name": "{{ $siteMetaTitle }}",
      "url": "{{ url('/') }}",
      "description": "{{ $siteMetaDesc }}",
      "telephone": "{{ \App\Models\StoreSetting::get('contact_phone', '+966580760890') }}",
      "priceRange": "$$",
      "address": {
        "{{ '@' }}type": "PostalAddress",
        "streetAddress": "{{ \App\Models\StoreSetting::get('address', 'المملكة العربية السعودية، مكة المكرمة، حي أم الجود، شارع عبد الله بن أبي بكر الصديق') }}",
        "addressCountry": "SA"
      }
    }
    </script>

    @if(!empty($gVerification))
    <!-- Google Search Console Verification -->
    <meta name="google-site-verification" content="{{ strip_tags($gVerification) }}">
    @endif

    <!-- Open Graph / Meta -->
    <meta property="og:type" content="website">
    <meta property="og:title" content="{{ $title ?? $siteMetaTitle }}">
    <meta property="og:description" content="{{ $description ?? $siteMetaDesc }}">
    <meta property="og:site_name" content="زاد نيكس | ZADNEX STORE">

    <!-- Luxury Typography: IBM Plex Sans Arabic (Brand Font) + Cormorant Garamond -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,600;1,400&family=IBM+Plex+Sans+Arabic:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Styles & Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Tamara Official Installments Widget -->
    <script defer src="https://cdn.tamara.co/widget-v2/tamara-widget.js"></script>

    <!-- Google Tag Manager -->
    @if($gtmId)
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
    new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
    j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
    'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
    })(window,document,'script','dataLayer','{{ $gtmId }}');</script>
    @endif

    <!-- Google Analytics (GA4) / Google Ads -->
    @php $primaryGoogle = $gaId ?: $gAdsId; @endphp
    @if($primaryGoogle)
    <script async src="https://www.googletagmanager.com/gtag/js?id={{ $primaryGoogle }}"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());
      @if($gaId)
      gtag('config', '{{ $gaId }}');
      @endif
      @if($gAdsId && $gAdsId !== $gaId)
      gtag('config', '{{ $gAdsId }}');
      @endif
    </script>
    @endif

    <!-- Meta / Facebook Pixel -->
    @if($metaPixelId)
    <script>
    !function(f,b,e,v,n,t,s)
    {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
    n.callMethod.apply(n,arguments):n.queue.push(arguments)};
    if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
    n.queue=[];t=b.createElement(e);t.async=!0;
    t.src=v;s=b.getElementsByTagName(e)[0];
    s.parentNode.insertBefore(t,s)}(window, document,'script',
    'https://connect.facebook.net/en_US/fbevents.js');
    fbq('init', '{{ $metaPixelId }}');
    fbq('track', 'PageView');
    </script>
    @endif

    <!-- TikTok Pixel -->
    @if($tiktokPixelId)
    <script>
    !function (w, d, t) {
      w.TiktokAnalyticsObject=t;var ttq=w[t]=w[t]||[];ttq.methods=["page","track","identify","instances","debug","on","off","once","ready","alias","group","enableCookie","disableCookie"],ttq.setAndDefer=function(t,e){t[e]=function(){t.push([e].concat(Array.prototype.slice.call(arguments,0)))}};for(var i=0;i<ttq.methods.length;i++)ttq.setAndDefer(ttq,ttq.methods[i]);ttq.instance=function(t){for(var e=ttq._i[t]||[],n=0;n<ttq.methods.length;n++)ttq.setAndDefer(e,ttq.methods[n]);return e},ttq.load=function(e,n){var i="https://analytics.tiktok.com/i18n/pixel/events.js";ttq._i=ttq._i||{},ttq._i[e]=[],ttq._i[e]._u=i,ttq._t=ttq._t||{},ttq._t[e]=+new Date,ttq._o=ttq._o||{},ttq._o[e]=n||{};var o=d.createElement("script");o.type="text/javascript",o.async=!0,o.src=i+"?sdkid="+e+"&lib="+t;var a=d.getElementsByTagName("script")[0];a.parentNode.insertBefore(o,a)};
      ttq.load('{{ $tiktokPixelId }}');
      ttq.page();
    }(window, document, 'ttq');
    </script>
    @endif

    <!-- Snapchat Pixel -->
    @if($snapchatPixelId)
    <script type='text/javascript'>
    (function(e,t,n){if(e.snaptr)return;var a=e.snaptr=function()
    {a.handleRequest?a.handleRequest.apply(a,arguments):a.queue.push(arguments)};
    a.queue=[];var s='script';r=t.createElement(s);r.async=!0;
    r.src=n;var u=t.getElementsByTagName(s)[0];
    u.parentNode.insertBefore(r,u);})(window,document,
    'https://sc-static.net/scevent.min.js');
    snaptr('init', '{{ $snapchatPixelId }}');
    snaptr('track', 'PAGE_VIEW');
    </script>
    @endif

    <!-- Custom Head Tracking & Verification Scripts -->
    @if(!empty($headScripts))
    {!! $headScripts !!}
    @endif

    <style>
        [x-cloak] { display: none !important; }
        .font-serif { font-family: 'Cormorant Garamond', Georgia, serif; }
    </style>
</head>
<body x-data class="bg-[#F8FAFC] text-[#0F172A] antialiased selection:bg-[#0B2E75] selection:text-[#FBB404] flex flex-col min-h-screen">
    
    <!-- Tracking NoScript Fallbacks -->
    @if($gtmId)
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id={{ $gtmId }}" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    @endif
    @if($metaPixelId)
    <noscript><img height="1" width="1" style="display:none" src="https://www.facebook.com/tr?id={{ $metaPixelId }}&ev=PageView&noscript=1" alt="" /></noscript>
    @endif
    @if(!empty($bodyScripts))
    {!! $bodyScripts !!}
    @endif
    
    <!-- Initial Splash Preloader (2 Seconds with Store Logo & Skip Button) -->
    <x-splash-preloader />

    <!-- Top Announcement Bar -->
    <x-announcement-bar />

    <!-- Sticky Luxury Header -->
    <x-header />

    <!-- Main Content Area -->
    <main class="flex-grow pb-16 md:pb-0">
        {{ $slot }}
    </main>

    <!-- Luxury Footer -->
    <x-footer />

    <!-- Slide-out Cart Drawer -->
    <x-cart-drawer />

    <!-- Mobile Slide-out Menu Drawer -->
    <x-mobile-drawer />

    <!-- Quick View Modal -->
    <x-quick-view-modal />

    <!-- Global Toast Alert -->
    <x-toast />

    <!-- Floating WhatsApp & Direct Call Buttons -->
    <x-floating-contact />

    <!-- Flash Surprise Promotional Offer Pop-up -->
    <x-flash-offer-popup />

    <!-- Mobile Fixed Bottom Navigation Bar -->
    <div class="md:hidden fixed bottom-0 inset-x-0 bg-white/95 backdrop-blur-md border-t border-[#E2E8F0] z-30 px-4 py-2 flex items-center justify-around text-[10px] sm:text-[11px] font-bold text-[#64748B]">
        <a href="{{ route('home') }}" class="flex flex-col items-center gap-1 {{ request()->routeIs('home') ? 'text-[#0B2E75]' : 'hover:text-[#0F172A]' }}">
            <x-icon name="home" class="w-5 h-5" />
            <span>الرئيسية</span>
        </a>

        <a href="{{ route('about') }}" class="flex flex-col items-center gap-1 {{ request()->routeIs('about') ? 'text-[#0B2E75]' : 'hover:text-[#0F172A]' }}">
            <x-icon name="award" class="w-5 h-5" />
            <span>من نحن</span>
        </a>

        <a href="{{ route('products.index') }}" class="flex flex-col items-center gap-1 {{ request()->routeIs('products.index') ? 'text-[#0B2E75]' : 'hover:text-[#0F172A]' }}">
            <x-icon name="package" class="w-5 h-5" />
            <span>المنتجات</span>
        </a>

        <button @click="$store.cart.open()" class="relative flex flex-col items-center gap-1 text-[#0F172A] hover:text-[#0B2E75]">
            <x-icon name="shopping-bag" class="w-5 h-5" />
            <span>السلة</span>
            <span x-show="$store.cart.count > 0" 
                  x-text="$store.cart.count" 
                  x-cloak
                  class="absolute -top-1 right-2 bg-[#FBB404] text-[#071E50] text-[9px] font-black w-4 h-4 rounded-full flex items-center justify-center">
            </span>
        </button>

        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', \App\Models\StoreSetting::get('whatsapp_number', '966580760890')) }}" target="_blank" class="flex flex-col items-center gap-1 text-[#25D366]">
            <x-icon name="whatsapp" class="w-5 h-5" />
            <span>واتساب</span>
        </a>
    </div>

</body>
</html>

