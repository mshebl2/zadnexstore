<x-app-layout>
    <x-slot:title>سجل طلباتي | متجر زاد نيكس</x-slot:title>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12 text-right">
        <div class="mb-8">
            <h1 class="text-2xl font-extrabold text-[#242424]">سجل طلباتي</h1>
            <p class="text-xs text-[#777777] mt-1">مرحباً بك، {{ auth()->user()->name }}. هنا يمكنك متابعة حالة طلباتك وفواتيرك.</p>
        </div>

        @if($orders->count() > 0)
        <div class="bg-white rounded-2xl border border-[#EDE6DA] overflow-hidden divide-y divide-[#EDE6DA]/70 shadow-xs">
            @foreach($orders as $order)
            <div class="p-6 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                <div>
                    <span class="font-mono font-bold text-xs bg-[#FAF8F4] border border-[#EDE6DA] px-2.5 py-1 rounded-md text-[#242424]">
                        {{ $order->order_number }}
                    </span>
                    <span class="text-xs text-[#777777] mr-2">{{ $order->created_at->format('Y-m-d') }}</span>

                    <div class="mt-2 text-xs text-[#777777]">
                        <span>عدد المنتجات: {{ $order->items->count() }}</span>
                        <span>•</span>
                        <span>المجموع: <strong class="text-[#242424]">{{ number_format($order->total, 2) }} ر.س</strong></span>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <span class="px-2.5 py-1 rounded-md text-[11px] font-bold {{ $order->payment_status === 'paid' ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">
                        {{ $order->payment_status_label_ar }}
                    </span>

                    <span class="px-2.5 py-1 rounded-md text-[11px] font-bold bg-blue-50 text-blue-800">
                        {{ $order->status_label_ar }}
                    </span>

                    <a href="{{ route('checkout.confirmation', $order->order_number) }}" class="bg-[#FAF8F4] hover:bg-[#C5A059] hover:text-white border border-[#EDE6DA] text-[#242424] text-xs font-semibold px-3 py-1.5 rounded-lg transition">
                        عرض التفاصيل
                    </a>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="bg-white rounded-2xl border border-[#EDE6DA] p-12 text-center space-y-4">
            <div class="w-16 h-16 mx-auto rounded-full bg-[#FAF8F4] flex items-center justify-center text-[#A59D92]">
                <x-icon name="shopping-bag" class="w-8 h-8" />
            </div>
            <h3 class="text-base font-bold text-[#242424]">لا توجد طلبات سابقة</h3>
            <p class="text-xs text-[#777777]">لم تقم بإنشاء أي طلبات حتى الآن.</p>
            <a href="{{ route('products.index') }}" class="inline-block bg-[#C5A059] text-white text-xs font-bold px-6 py-2.5 rounded-xl hover:bg-[#AF8B44] transition">
                تسوق منتجات زاد نيكس الآن
            </a>
        </div>
        @endif
    </div>
</x-app-layout>

