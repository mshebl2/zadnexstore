<div x-data 
     x-show="$store.cart.isOpen" 
     x-cloak 
     @keydown.escape.window="$store.cart.close()"
     class="fixed inset-0 z-50 overflow-hidden" 
     role="dialog" 
     aria-modal="true">
    
    <!-- Backdrop overlay -->
    <div x-show="$store.cart.isOpen"
         x-transition:enter="transition-opacity ease-linear duration-300" 
         x-transition:enter-start="opacity-0" 
         x-transition:enter-end="opacity-100" 
         x-transition:leave="transition-opacity ease-linear duration-300" 
         x-transition:leave-start="opacity-100" 
         x-transition:leave-end="opacity-0"
         @click="$store.cart.close()"
         class="fixed inset-0 bg-black/50 backdrop-blur-xs"></div>

    <div class="fixed inset-y-0 left-0 max-w-full flex z-10">
        <!-- Slide-out Drawer Panel -->
        <div x-show="$store.cart.isOpen"
             x-transition:enter="transition ease-in-out duration-300 transform" 
             x-transition:enter-start="-translate-x-full" 
             x-transition:enter-end="translate-x-0" 
             x-transition:leave="transition ease-in-out duration-300 transform" 
             x-transition:leave-start="translate-x-0" 
             x-transition:leave-end="-translate-x-full"
             class="w-screen max-w-md bg-white shadow-2xl flex flex-col justify-between text-right">
            
            <!-- Drawer Header -->
            <div class="p-5 border-b border-[#EDE6DA] flex items-center justify-between bg-[#FAF8F4]">
                <div class="flex items-center gap-2">
                    <x-icon name="shopping-bag" class="w-5 h-5 text-[#C5A059]" />
                    <h2 class="text-base font-semibold text-[#242424]">سلة المشتريات</h2>
                    <span class="text-xs bg-[#EDE6DA] text-[#242424] font-bold px-2 py-0.5 rounded-full" x-text="$store.cart.count + ' منتجات'"></span>
                </div>
                <button @click="$store.cart.close()" class="p-1.5 text-[#777777] hover:text-[#242424] rounded-full hover:bg-white transition">
                    <x-icon name="x" class="w-5 h-5" />
                </button>
            </div>

            <!-- Free Shipping Bar (Dynamic) -->
            <div class="px-5 py-3 bg-[#F7F3EB] border-b border-[#EDE6DA] text-xs text-[#242424]">
                <template x-if="$store.cart.subtotal >= 299">
                    <div class="flex items-center gap-2 text-emerald-700 font-semibold">
                        <x-icon name="truck" class="w-4 h-4 text-emerald-600" />
                        <span>مؤهل للشحن المجاني لكافة مدن المملكة</span>
                    </div>
                </template>
                <template x-if="$store.cart.subtotal < 299">
                    <div>
                        <div class="flex items-center justify-between mb-1 text-[11px] text-[#777777]">
                            <span>أضف بـ <strong class="text-[#C5A059]" x-text="(299 - $store.cart.subtotal).toFixed(2) + ' ر.س'"></strong> لتحصل على شحن مجاني</span>
                            <span x-text="Math.min(100, Math.round(($store.cart.subtotal / 299) * 100)) + '%'"></span>
                        </div>
                        <div class="w-full h-1.5 bg-gray-200 rounded-full overflow-hidden">
                            <div class="h-full bg-[#C5A059] rounded-full transition-all duration-300" 
                                 :style="'width: ' + Math.min(100, ($store.cart.subtotal / 299) * 100) + '%'"></div>
                        </div>
                    </div>
                </template>
            </div>

            <!-- Cart Items List (Scrollable) -->
            <div class="flex-1 overflow-y-auto p-5 divide-y divide-[#EDE6DA]/70">
                <!-- Empty Cart State -->
                <template x-if="$store.cart.items.length === 0">
                    <div class="py-16 text-center space-y-4">
                        <div class="w-16 h-16 mx-auto rounded-full bg-[#FAF8F4] flex items-center justify-center text-[#A59D92]">
                            <x-icon name="shopping-bag" class="w-8 h-8" />
                        </div>
                        <h3 class="text-base font-semibold text-[#242424]">سلتك فارغة حالياً</h3>
                        <p class="text-xs text-[#777777] max-w-xs mx-auto">
                            استكشف تشكيلتنا الواسعة من المواد الغذائية، المشروبات، المنظفات والكماليات واملأ سلتك بما تحتاجه أسرتك.
                        </p>
                        <a href="{{ route('products.index') }}" 
                           @click="$store.cart.close()"
                           class="inline-block bg-[#C5A059] hover:bg-[#AF8B44] text-white font-semibold text-xs px-6 py-2.5 rounded-lg transition duration-200">
                            تصفح المنتجات الآن
                        </a>
                    </div>
                </template>

                <!-- Loaded Items -->
                <template x-for="item in $store.cart.items" :key="item.id">
                    <div class="py-4 flex gap-4 items-start">
                        <!-- Product thumbnail -->
                        <img :src="item.image" :alt="item.name" class="w-18 h-18 object-cover rounded-lg border border-[#EDE6DA] flex-shrink-0 bg-[#FAF8F4]">
                        
                        <div class="flex-1 min-w-0">
                            <h4 class="text-xs font-semibold text-[#242424] truncate" x-text="item.name"></h4>
                            <template x-if="item.variant">
                                <span class="text-[10px] text-[#A59D92] block" x-text="item.variant"></span>
                            </template>
                            
                            <div class="mt-1 text-xs font-bold text-[#C5A059]">
                                <span x-text="item.price.toFixed(2)"></span> <span class="text-[10px] text-[#777777]">ر.س</span>
                            </div>

                            <!-- Quantity + Remove controls -->
                            <div class="mt-2.5 flex items-center justify-between">
                                <div class="flex items-center border border-[#EDE6DA] rounded-md bg-[#FAF8F4]">
                                    <button @click="$store.cart.updateQuantity(item.id, item.quantity - 1)" 
                                            class="px-2 py-1 text-[#777777] hover:text-[#242424] transition text-xs">
                                        <x-icon name="minus" class="w-3 h-3" />
                                    </button>
                                    <span class="px-2.5 text-xs font-bold text-[#242424]" x-text="item.quantity"></span>
                                    <button @click="$store.cart.updateQuantity(item.id, item.quantity + 1)" 
                                            class="px-2 py-1 text-[#777777] hover:text-[#242424] transition text-xs">
                                        <x-icon name="plus" class="w-3 h-3" />
                                    </button>
                                </div>

                                <button @click="$store.cart.removeItem(item.id)" 
                                        class="text-[11px] text-red-500 hover:text-red-700 flex items-center gap-1 transition">
                                    <x-icon name="trash" class="w-3.5 h-3.5" />
                                    <span>حذف</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </template>
            </div>

            <!-- Drawer Footer (Subtotal & Actions) -->
            <template x-if="$store.cart.items.length > 0">
                <div class="p-5 border-t border-[#EDE6DA] bg-[#FAF8F4] space-y-3">
                    <div class="flex items-center justify-between text-sm font-bold text-[#242424]">
                        <span>المجموع الفرعي:</span>
                        <div class="text-base text-[#C5A059]">
                            <span x-text="$store.cart.subtotal.toFixed(2)"></span> 
                            <span class="text-xs text-[#777777]">ر.س</span>
                        </div>
                    </div>
                    <p class="text-[10px] text-[#777777]">
                        * الضرائب وتكاليف الشحن تُحسب عند إتمام الطلب في صفحة الدفع.
                    </p>

                    <div class="space-y-2 pt-1">
                        <a href="{{ route('checkout.index') }}" 
                           @click="$store.cart.close()"
                           class="w-full bg-[#C5A059] hover:bg-[#AF8B44] text-white font-bold text-xs py-3 px-4 rounded-lg shadow-md flex items-center justify-center gap-2 transition duration-200">
                            <x-icon name="shield-check" class="w-4 h-4" />
                            <span>متابعة الشراء والدفع الآمن</span>
                        </a>

                        <a href="{{ route('cart.index') }}" 
                           @click="$store.cart.close()"
                           class="w-full bg-white border border-[#EDE6DA] hover:bg-[#FAF8F4] text-[#242424] font-semibold text-xs py-2.5 px-4 rounded-lg flex items-center justify-center transition duration-200">
                            <span>عرض وتعديل السلة كاملة</span>
                        </a>
                    </div>
                </div>
            </template>
        </div>
    </div>
</div>

