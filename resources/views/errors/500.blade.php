<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>حدث خطأ مؤقت | متجر زاد نيكس</title>
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}?v=2">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}?v=2">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;600;700&family=IBM+Plex+Sans+Arabic:wght@400;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: 'IBM Plex Sans Arabic', sans-serif; }
        .font-serif { font-family: 'Cormorant Garamond', Georgia, serif; }
    </style>
</head>
<body class="bg-[#FAF8F4] text-[#242424] antialiased min-h-screen flex flex-col items-center justify-center p-6 selection:bg-[#C5A059] selection:text-white">
    <div class="max-w-md w-full text-center space-y-6">
        <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-white border border-[#EDE6DA] shadow-sm mb-2">
            <span class="text-3xl font-serif font-bold text-[#C5A059]">500</span>
        </div>

        <div class="space-y-2">
            <h1 class="text-xl sm:text-2xl font-bold text-[#242424]">عذراً، حدث خطأ غير متوقع</h1>
            <p class="text-xs sm:text-sm text-[#777777] leading-relaxed">
                واجه النظام عائقاً مؤقتاً أثناء معالجة طلبك، ويجري العمل على استعادة الخدمة على الفور. يمكنك تحديث الصفحة أو العودة لسلتك.
            </p>
        </div>

        <div class="flex flex-col sm:flex-row items-center justify-center gap-3 pt-2">
            <a href="{{ url('/') }}" class="w-full sm:w-auto bg-[#242424] hover:bg-black text-[#C5A059] text-xs font-bold px-6 py-3 rounded-xl transition duration-200 shadow-md">
                العودة للرئيسية &larr;
            </a>
            <a href="{{ url('/cart') }}" class="w-full sm:w-auto bg-[#C5A059] hover:bg-[#AF8B44] text-white text-xs font-bold px-6 py-3 rounded-xl transition duration-200 shadow-md">
                الرجوع للسلة
            </a>
        </div>

        <div class="pt-8 text-[11px] text-[#A0A0A0] border-t border-[#EDE6DA]/80">
            متجر زاد نيكس &copy; {{ date('Y') }} — خدمة العملاء والمساندة الفورية
        </div>
    </div>
</body>
</html>

