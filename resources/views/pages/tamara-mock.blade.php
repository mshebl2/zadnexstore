<x-app-layout title="محاكي دفع تمارا | زاد نيكس">
    <div class="max-w-xl mx-auto px-4 py-16">
        <div class="bg-white border border-[#EDE6DA] rounded-3xl p-8 shadow-sm text-center">
            
            <div class="inline-flex items-center justify-center p-3 rounded-2xl bg-gradient-to-r from-[#FFB4A2]/20 via-[#E2A9E5]/20 to-[#9CD5FF]/20 border border-[#EDE6DA] mb-6">
                <img src="{{ asset('images/payments/tamara.png') }}" alt="تمارا" class="h-8 w-auto object-contain">
            </div>

            <span class="inline-block px-3 py-1 bg-amber-100 text-amber-800 text-xs font-bold rounded-full mb-3">
                ⚠️ وضع تجربة تمارا الآمن (Tamara Sandbox Simulator)
            </span>

            <h1 class="text-xl font-bold text-[#242424] mb-2">محاكاة الدفع عبر تمارا (4 دفعات)</h1>
            <p class="text-xs text-[#777777] mb-6">هذه صفحة محاكاة آمنة لتجربة تجربة العميل عند الشراء والدفع عبر تمارا قبل تفعيل المفاتيح الرسمية.</p>

            <div class="bg-[#FAF8F4] border border-[#EDE6DA] rounded-2xl p-5 mb-6 text-right text-xs space-y-2">
                <div class="flex justify-between border-b border-[#EDE6DA] pb-2">
                    <span class="text-[#777777]">رقم الطلب:</span>
                    <span class="font-mono font-bold text-[#242424]">{{ $order->order_number }}</span>
                </div>
                <div class="flex justify-between border-b border-[#EDE6DA] pb-2">
                    <span class="text-[#777777]">إجمالي الطلب:</span>
                    <span class="font-bold text-[#242424]">{{ number_format($order->total, 2) }} ر.س</span>
                </div>
                <div class="flex justify-between border-b border-[#EDE6DA] pb-2 text-[#C5A059] font-bold">
                    <span>قسط اليوم (الدَفعة الأولى 1 من 4):</span>
                    <span>{{ number_format($order->total / 4, 2) }} ر.س</span>
                </div>
                <div class="flex justify-between text-[11px] text-[#777777] pt-1">
                    <span>الدفعات المتبقية:</span>
                    <span>3 أقساط شهرية متساوية (بدون أي فوائد)</span>
                </div>
            </div>

            <div class="flex flex-col sm:flex-row gap-3">
                <form action="{{ route('payment.tamara.mock.process', $order->order_number) }}" method="POST" class="flex-1">
                    @csrf
                    <input type="hidden" name="orderId" value="TAMARA_MOCK_{{ $order->order_number }}">
                    <button type="submit" class="w-full bg-[#242424] hover:bg-[#333333] text-white py-3.5 px-6 rounded-xl font-bold text-xs transition duration-200 shadow-md flex items-center justify-center gap-2">
                        <span>✓ محاكاة إتمام الدفع بنجاح</span>
                    </button>
                </form>

                <form action="{{ route('payment.tamara.cancel', $order->order_number) }}" method="GET" class="sm:w-auto">
                    <button type="submit" class="w-full bg-gray-100 hover:bg-gray-200 text-gray-700 py-3.5 px-5 rounded-xl font-bold text-xs transition duration-200">
                        إلغاء والعودة
                    </button>
                </form>
            </div>

        </div>
    </div>
</x-app-layout>

