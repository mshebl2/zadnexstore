<div x-data
     x-show="$store.quickView.isOpen" 
     x-cloak 
     @keydown.escape.window="$store.quickView.close()"
     class="fixed inset-0 z-50 overflow-y-auto" 
     role="dialog" 
     aria-modal="true">
    
    <!-- Backdrop overlay -->
    <div x-show="$store.quickView.isOpen" 
         x-transition:enter="transition-opacity ease-linear duration-200" 
         x-transition:enter-start="opacity-0" 
         x-transition:enter-end="opacity-100" 
         x-transition:leave="transition-opacity ease-linear duration-200" 
         x-transition:leave-start="opacity-100" 
         x-transition:leave-end="opacity-0"
         @click="$store.quickView.close()" 
         class="fixed inset-0 bg-black/60 backdrop-blur-xs"></div>

    <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
        <!-- Modal Card -->
        <div x-show="$store.quickView.isOpen" 
             x-transition:enter="transition ease-out duration-300" 
             x-transition:enter-start="opacity-0 scale-95" 
             x-transition:enter-end="opacity-100 scale-100" 
             x-transition:leave="transition ease-in duration-200" 
             x-transition:leave-start="opacity-100 scale-100" 
             x-transition:leave-end="opacity-0 scale-95"
             class="relative transform overflow-hidden rounded-2xl bg-white text-right shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-2xl border border-[#EDE6DA]">
            
            <!-- Close button -->
            <button @click="$store.quickView.close()" class="absolute top-4 left-4 z-20 p-2 text-[#777777] hover:text-[#242424] bg-white/80 rounded-full hover:bg-white shadow-xs">
                <x-icon name="x" class="w-5 h-5" />
            </button>

            <!-- Loading Spinner -->
            <div x-show="$store.quickView.loading" class="py-24 text-center">
                <div class="inline-block w-8 h-8 border-3 border-[#C5A059] border-t-transparent rounded-full animate-spin"></div>
                <p class="text-xs text-[#777777] mt-3">جاري تحميل تفاصيل المنتج...</p>
            </div>

            <!-- Content -->
            <div x-show="!$store.quickView.loading && $store.quickView.product" class="grid grid-cols-1 md:grid-cols-2">
                <!-- Image -->
                <div class="bg-[#FAF8F4] p-6 flex items-center justify-center border-b md:border-b-0 md:border-l border-[#EDE6DA]">
                    <img :src="$store.quickView.product ? $store.quickView.product.image : ''" 
                         :alt="$store.quickView.product ? $store.quickView.product.name : ''" 
                         class="max-h-72 object-contain rounded-xl shadow-xs">
                </div>

                <!-- Info -->
                <div class="p-6 flex flex-col justify-between">
                    <div>
                        <span class="text-xs text-[#C5A059] font-medium" x-text="$store.quickView.product ? $store.quickView.product.category : ''"></span>
                        <h3 class="text-lg font-bold text-[#242424] mt-1" x-text="$store.quickView.product ? $store.quickView.product.name : ''"></h3>
                        
                        <div class="mt-3 flex items-baseline gap-2">
                            <span class="text-xl font-bold text-[#242424]" x-text="$store.quickView.product ? $store.quickView.product.current_price.toFixed(2) + ' ر.س' : ''"></span>
                            <template x-if="$store.quickView.product && $store.quickView.product.has_discount">
                                <span class="text-xs text-[#A59D92] line-through" x-text="$store.quickView.product.regular_price.toFixed(2) + ' ر.س'"></span>
                            </template>
                        </div>

                        <p class="text-xs text-[#777777] mt-3 line-clamp-3 leading-relaxed" x-text="$store.quickView.product ? $store.quickView.product.description : ''"></p>

                        <!-- Fragrance notes pill if available -->
                        <template x-if="$store.quickView.product && $store.quickView.product.fragrance_top">
                            <div class="mt-4 p-3 bg-[#FAF8F4] rounded-lg border border-[#EDE6DA] text-[11px] text-[#777777]">
                                <span class="font-bold text-[#242424] block mb-1">مواصفات المنتج:</span>
                                <span x-text="$store.quickView.product.fragrance_top"></span>
                            </div>
                        </template>
                    </div>

                    <div class="mt-6 pt-4 border-t border-[#EDE6DA] space-y-2">
                        <button @click="$store.cart.addItem($store.quickView.product.id); $store.quickView.close()" 
                                type="button" 
                                class="w-full bg-[#C5A059] hover:bg-[#AF8B44] text-white font-bold text-xs py-3 px-4 rounded-lg flex items-center justify-center gap-2 shadow-sm transition">
                            <x-icon name="shopping-bag" class="w-4 h-4" />
                            <span>إضافة سريعة إلى السلة</span>
                        </button>

                        <a :href="'/product/' + ($store.quickView.product ? $store.quickView.product.slug : '')" 
                           class="w-full text-center block text-xs font-semibold text-[#777777] hover:text-[#C5A059] py-1.5 transition">
                            عرض كامل تفاصيل ومواصفات المنتج ←
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

