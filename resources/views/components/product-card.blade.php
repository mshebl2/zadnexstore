@props(['product'])

<div class="group relative bg-white border border-[#E2E8F0] rounded-2xl overflow-hidden hover:border-[#0B2E75]/60 luxury-hover-card flex flex-col justify-between shadow-xs hover:shadow-md transition-all duration-300"
     x-data="{ isHovered: false }"
     @mouseenter="isHovered = true"
     @mouseleave="isHovered = false">
    
    <!-- Image Box -->
    <div class="relative w-full aspect-square bg-[#F8FAFC] overflow-hidden">
        <a href="{{ route('product.show', $product->slug) }}" class="block w-full h-full">
            <img src="{{ $product->primary_image }}" 
                 alt="{{ $product->name_ar }}" 
                 loading="lazy"
                 class="w-full h-full object-cover object-center group-hover:scale-105 transition-transform duration-700 ease-out">
        </a>

        <!-- Discount Badge -->
        @if($product->has_discount)
            <span class="absolute top-3 right-3 bg-[#0B2E75] text-[#FBB404] text-[11px] font-black px-2.5 py-1 rounded-full shadow-sm z-10 border border-[#FBB404]/30">
                وفر {{ $product->discount_percentage }}%
            </span>
        @endif

        <!-- Out of stock badge -->
        @if(!$product->is_in_stock)
            <span class="absolute top-3 right-3 bg-red-600 text-white text-[11px] font-bold px-2.5 py-1 rounded-full shadow-sm z-10">
                نفدت الكمية
            </span>
        @endif

        <!-- Quick View & Wishlist Floating Actions -->
        <div class="absolute top-3 left-3 flex flex-col gap-2 z-10">
            <!-- Wishlist -->
            <button @click.prevent="$store.toast.notify('تم حفظ المنتج في قائمة الرغبات')" 
                    type="button" 
                    class="w-8 h-8 rounded-full bg-white/90 hover:bg-[#0B2E75] text-[#0F172A] hover:text-white flex items-center justify-center shadow-md transition-all duration-200"
                    title="إضافة للمفضلة">
                <x-icon name="heart" class="w-4 h-4" />
            </button>

            <!-- Quick View -->
            <button @click.prevent="$store.quickView.open({{ $product->id }})" 
                    type="button" 
                    class="w-8 h-8 rounded-full bg-white/90 hover:bg-[#0B2E75] text-[#0F172A] hover:text-white flex items-center justify-center shadow-md transition-all duration-200"
                    title="معاينة سريعة">
                <x-icon name="eye" class="w-4 h-4" />
            </button>
        </div>

        <!-- Desktop Quick Add Overlay on Hover -->
        @if($product->is_in_stock)
        <div class="hidden md:block absolute inset-x-3 bottom-3 transform translate-y-12 group-hover:translate-y-0 opacity-0 group-hover:opacity-100 transition-all duration-300 z-10">
            <button @click.prevent="$store.cart.addItem({{ $product->id }})" 
                    type="button" 
                    class="w-full bg-[#0B2E75] hover:bg-[#FBB404] hover:text-[#071E50] text-white text-xs font-bold py-2.5 px-4 rounded-xl shadow-lg flex items-center justify-center gap-2 transition duration-200">
                <x-icon name="shopping-bag" class="w-4 h-4" />
                <span>إضافة سريعة للسلة</span>
            </button>
        </div>
        @endif
    </div>

    <!-- Product Meta Content -->
    <div class="p-4 flex flex-col flex-grow justify-between text-right">
        <div>
            <!-- Category Tag -->
            <a href="{{ route('category.show', $product->category->slug) }}" class="text-[11px] text-[#64748B] hover:text-[#0B2E75] transition font-medium block mb-1">
                {{ $product->category->name_ar }}
            </a>

            <!-- Title -->
            <h3 class="text-sm font-bold text-[#0F172A] hover:text-[#0B2E75] transition line-clamp-1 mb-2">
                <a href="{{ route('product.show', $product->slug) }}">
                    {{ $product->name_ar }}
                </a>
            </h3>

            <!-- Size / Volume Badge if available -->
            @if($product->size)
                <span class="inline-block text-[10px] bg-[#EFF6FF] text-[#0B2E75] border border-[#BFDBFE] px-2 py-0.5 rounded-md font-medium mb-2">
                    {{ $product->size }}
                </span>
            @endif
        </div>

        <!-- Price Section & Mobile Add Button -->
        <div class="pt-2 border-t border-[#E2E8F0] flex items-center justify-between">
            <div class="flex items-baseline gap-1.5 flex-wrap">
                <span class="text-base font-extrabold text-[#0B2E75]">
                    {{ number_format($product->current_price, 2) }} <span class="text-xs font-normal text-[#64748B]">ر.س</span>
                </span>
                @if($product->has_discount)
                    <span class="text-xs text-[#94A3B8] line-through">
                        {{ number_format($product->regular_price, 2) }} ر.س
                    </span>
                @endif
            </div>

            <!-- Mobile Add To Cart Button -->
            @if($product->is_in_stock)
            <button @click.prevent="$store.cart.addItem({{ $product->id }})" 
                    type="button" 
                    class="md:hidden p-2 rounded-xl bg-[#0B2E75] hover:bg-[#FBB404] text-white hover:text-[#071E50] transition shadow-xs" 
                    aria-label="أضف إلى السلة">
                <x-icon name="plus" class="w-4 h-4" />
            </button>
            @endif
        </div>
    </div>
</div>
