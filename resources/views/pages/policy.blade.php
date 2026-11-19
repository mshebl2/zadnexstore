<x-app-layout>
    <x-slot:title>{{ $title }} | متجر زاد نيكس</x-slot:title>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12 text-right">
        <div class="bg-white rounded-3xl border border-[#EDE6DA] p-8 sm:p-12 shadow-xs space-y-6">
            <h1 class="text-2xl sm:text-3xl font-extrabold text-[#242424] border-b border-[#EDE6DA] pb-4">
                {{ $title }}
            </h1>

            <div class="prose prose-sm text-xs sm:text-sm text-[#777777] leading-loose space-y-4">
                {!! $content !!}
            </div>

            <div class="pt-6 border-t border-[#EDE6DA] text-xs text-[#A59D92] flex items-center justify-between">
                <span>آخر تحديث: نوفمبر 2026</span>
                <a href="{{ route('home') }}" class="text-[#C5A059] font-bold hover:underline">العودة للرئيسية ←</a>
            </div>
        </div>
    </div>
</x-app-layout>

