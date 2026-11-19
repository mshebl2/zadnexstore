<x-app-layout>
    <x-slot:title>بوابة الدفع التجريبية | ZADNEX STORE Payment Sandbox</x-slot:title>

    <div class="max-w-xl mx-auto px-4 py-12 text-right">
        
        <!-- Sandbox Notice Banner -->
        <div class="bg-amber-500/10 border-2 border-amber-500/40 rounded-2xl p-4 mb-6 text-center text-xs text-amber-900 font-medium">
            <span class="block text-sm font-bold text-amber-800 mb-1">⚠️ وضع الدفع التجريبي المحلي (Mock Payment Simulator)</span>
            هذه الصفحة مخصصة للاختبار والتطوير فقط. لن يتم خصم أي مبالغ مالية فعلية.
        </div>

        <div class="bg-white rounded-3xl border border-[#EDE6DA] p-6 sm:p-8 shadow-xl space-y-6">
            
            <div class="flex items-center justify-between border-b border-[#EDE6DA] pb-4">
                <div class="flex items-center gap-2">
                    <x-icon name="credit-card" class="w-6 h-6 text-[#C5A059]" />
                    <h2 class="text-base font-bold text-[#242424]">محاكي بوابة Paymob KSA</h2>
                </div>
                <span class="bg-[#FAF8F4] border border-[#EDE6DA] text-[#242424] text-xs font-bold px-3 py-1 rounded-md">
                    {{ number_format($order->total, 2) }} ر.س
                </span>
            </div>

            <!-- Transaction Summary -->
            <div class="bg-[#FAF8F4] p-4 rounded-xl border border-[#EDE6DA] text-xs space-y-2">
                <div class="flex items-center justify-between">
                    <span class="text-[#777777]">رقم الطلب:</span>
                    <span class="font-bold font-mono text-[#242424]">{{ $order->order_number }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-[#777777]">اسم العميل:</span>
                    <span class="font-bold text-[#242424]">{{ $order->customer_name }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-[#777777]">رقم المعاملة التجريبية:</span>
                    <span class="font-mono text-[#777777] text-[10px]">{{ $transactionId }}</span>
                </div>
            </div>

            <!-- Simulated Card Preview -->
            <div class="rounded-2xl bg-gradient-to-tr from-[#242424] via-[#333333] to-[#444444] text-white p-6 shadow-md relative overflow-hidden">
                <div class="flex items-center justify-between mb-6">
                    <span class="font-bold text-sm tracking-wider text-[#C5A059]">مدى / mada</span>
                    <x-icon name="shield-check" class="w-5 h-5 text-emerald-400" />
                </div>
                <div class="font-mono text-base tracking-widest mb-4">
                    4000 •••• •••• 1234
                </div>
                <div class="flex items-center justify-between text-xs text-gray-300">
                    <span>{{ $order->customer_name }}</span>
                    <span>12/28</span>
                </div>
            </div>

            <!-- Simulation Controls -->
            <div class="space-y-3 pt-2">
                <!-- Success -->
                <form action="{{ route('payment.mock.process', $order->order_number) }}" method="POST">
                    @csrf
                    <input type="hidden" name="action" value="success">
                    <input type="hidden" name="transaction_id" value="{{ $transactionId }}">
                    <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs py-3.5 px-4 rounded-xl shadow-md flex items-center justify-center gap-2 transition duration-200">
                        <x-icon name="check-circle" class="w-4 h-4" />
                        <span>محاكاة دفع ناجح (Approve Payment)</span>
                    </button>
                </form>

                <!-- Failed -->
                <form action="{{ route('payment.mock.process', $order->order_number) }}" method="POST">
                    @csrf
                    <input type="hidden" name="action" value="fail">
                    <input type="hidden" name="transaction_id" value="{{ $transactionId }}">
                    <button type="submit" class="w-full bg-red-50 hover:bg-red-100 text-red-700 border border-red-200 font-semibold text-xs py-3 px-4 rounded-xl flex items-center justify-center gap-2 transition duration-200">
                        <x-icon name="x" class="w-4 h-4" />
                        <span>محاكاة عملية مرفوضة (Simulate Declined)</span>
                    </button>
                </form>
            </div>

            <div class="text-center pt-2">
                <a href="{{ route('checkout.index') }}" class="text-xs text-[#777777] hover:underline">
                    إلغاء والعودة لصفحة الدفع
                </a>
            </div>

        </div>
    </div>
</x-app-layout>

