<x-admin-layout>
    <x-slot:title>رسائل واستفسارات العملاء</x-slot:title>
    <div class="space-y-6">
        
        <!-- Header -->
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 bg-white p-6 rounded-2xl border border-[#E2E8F0] shadow-xs">
            <div>
                <h2 class="text-lg font-bold text-[#242424]">رسائل واستفسارات صفحة "اتصل بنا"</h2>
                <p class="text-xs text-[#777777] mt-1">
                    استعراض الرسائل الواردة من عملاء المتجر مع بيانات التواصل وتاريخ الإرسال.
                </p>
            </div>
            <div class="text-xs font-semibold px-3 py-1.5 rounded-full bg-[#F8FAFC] border border-[#E2E8F0] text-[#0B2E75]">
                إجمالي الرسائل: {{ $messages->total() }}
            </div>
        </div>

        <!-- Messages Table -->
        <div class="bg-white rounded-2xl border border-[#E2E8F0] shadow-xs overflow-hidden">
            @if($messages->isEmpty())
                <div class="text-center py-16 px-4">
                    <div class="w-16 h-16 rounded-full bg-[#F8FAFC] text-[#0B2E75] flex items-center justify-center mx-auto mb-4 border border-[#E2E8F0]">
                        <x-icon name="mail" class="w-8 h-8" />
                    </div>
                    <h3 class="text-sm font-bold text-[#242424]">لا توجد رسائل واردة حالياً</h3>
                    <p class="text-xs text-[#777777] mt-1">ستظهر هنا أي رسائل أو استفسارات يرسلها العملاء عبر صفحة اتصل بنا.</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-right text-xs">
                        <thead class="bg-[#F8FAFC] border-b border-[#E2E8F0] text-[#777777] font-semibold">
                            <tr>
                                <th class="p-4">المرسل</th>
                                <th class="p-4">رقم الجوال</th>
                                <th class="p-4">البريد الإلكتروني</th>
                                <th class="p-4">نوع الاستفسار</th>
                                <th class="p-4">نص الرسالة</th>
                                <th class="p-4">التاريخ</th>
                                <th class="p-4 text-center">إجراءات</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#F8FAFC]">
                            @foreach($messages as $msg)
                            <tr class="hover:bg-[#F8FAFC]/50 transition">
                                <td class="p-4 font-bold text-[#242424] whitespace-nowrap">
                                    {{ $msg->name }}
                                </td>
                                <td class="p-4 whitespace-nowrap">
                                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $msg->phone) }}" target="_blank" class="inline-flex items-center gap-1.5 text-xs text-[#25D366] hover:underline font-mono" dir="ltr">
                                        <x-icon name="whatsapp" class="w-3.5 h-3.5" />
                                        <span>{{ $msg->phone }}</span>
                                    </a>
                                </td>
                                <td class="p-4 text-[#555555] font-mono whitespace-nowrap" dir="ltr">
                                    {{ $msg->email ?: '—' }}
                                </td>
                                <td class="p-4 whitespace-nowrap">
                                    <span class="inline-block px-2.5 py-1 bg-[#F8FAFC] border border-[#E2E8F0] rounded-lg text-[11px] font-medium text-[#0B2E75]">
                                        {{ $msg->subject ?: 'عام' }}
                                    </span>
                                </td>
                                <td class="p-4 text-[#555555] max-w-xs break-words leading-relaxed">
                                    {{ $msg->message }}
                                </td>
                                <td class="p-4 text-[#A59D92] whitespace-nowrap text-[11px]">
                                    {{ $msg->created_at->format('Y-m-d H:i') }}
                                </td>
                                <td class="p-4 text-center whitespace-nowrap">
                                    <div class="flex items-center justify-center gap-2">
                                        <!-- WhatsApp reply button -->
                                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $msg->phone) }}?text={{ urlencode('مرحباً أخي ' . $msg->name . '، بخصوص استفساركم لدى متجر متجر زاد نيكس...') }}" 
                                           target="_blank" 
                                           class="p-1.5 bg-[#E8F8EE] text-[#25D366] hover:bg-[#25D366] hover:text-white rounded-lg transition" 
                                           title="الرد عبر واتساب">
                                            <x-icon name="whatsapp" class="w-4 h-4" />
                                        </a>

                                        <!-- Delete -->
                                        <form method="POST" action="{{ route('admin.messages.destroy', $msg->id) }}" onsubmit="return confirm('هل أنت متأكد من حذف هذه الرسالة؟');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-1.5 text-gray-400 hover:text-red-600 rounded-lg transition" title="حذف">
                                                <x-icon name="trash" class="w-4 h-4" />
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if($messages->hasPages())
                <div class="p-4 border-t border-[#E2E8F0]">
                    {{ $messages->links() }}
                </div>
                @endif
            @endif
        </div>

    </div>
</x-admin-layout>
