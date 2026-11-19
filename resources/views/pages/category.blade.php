<x-app-layout>
    <x-slot:title>{{ $category->name_ar }} | متجر زاد نيكس</x-slot:title>
    <x-slot:description>{{ $category->description_ar }}</x-slot:description>

    <!-- Category Header Hero -->
    <div class="relative bg-[#242424] text-white py-14 overflow-hidden border-b border-[#3E3E3E]">
        <div class="absolute inset-0 z-0 opacity-25">
            <img src="{{ $category->image }}" alt="{{ $category->name_ar }}" class="w-full h-full object-cover">
        </div>
        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-right">
            <nav class="text-xs text-[#A59D92] mb-3 flex items-center gap-2">
                <a href="{{ route('home') }}" class="hover:text-[#C5A059]">الرئيسية</a>
                <span>/</span>
                <a href="{{ route('products.index') }}" class="hover:text-[#C5A059]">المنتجات</a>
                @if($category->parent)
                <span>/</span>
                <a href="{{ route('category.show', $category->parent->slug) }}" class="hover:text-[#C5A059]">{{ $category->parent->name_ar }}</a>
                @endif
                <span>/</span>
                <span class="text-[#E3CD98] font-medium">{{ $category->name_ar }}</span>
            </nav>
            <h1 class="text-3xl sm:text-4xl font-extrabold tracking-tight">{{ $category->name_ar }}</h1>
            <p class="text-xs sm:text-sm text-[#D4C9BC] max-w-2xl mt-2 leading-relaxed">
                {{ $category->description_ar }}
            </p>

            @if($category->children && $category->children->isNotEmpty())
            <div class="mt-4 flex flex-wrap gap-2 pt-3 border-t border-white/10 items-center">
                <span class="text-xs text-[#E3CD98] font-semibold">تصفح الأقسام الفرعية:</span>
                @foreach($category->children as $sub)
                <a href="{{ route('category.show', $sub->slug) }}" class="text-xs bg-white/15 hover:bg-[#C5A059] hover:text-[#121212] text-white px-3.5 py-1.5 rounded-xl backdrop-blur-xs transition border border-white/10 font-medium">
                    {{ $sub->name_ar }}
                </a>
                @endforeach
            </div>
            @endif
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <!-- Sort Control -->
        <div class="flex items-center justify-between mb-8 pb-4 border-b border-[#EDE6DA]">
            <span class="text-xs text-[#777777]">عرض {{ $products->total() }} منتجات</span>
            
            <form method="GET" action="{{ route('category.show', $category->slug) }}" class="flex items-center gap-2">
                <label for="sort" class="text-xs text-[#777777]">الترتيب:</label>
                <select name="sort" id="sort" onchange="this.form.submit()" class="bg-white border border-[#EDE6DA] rounded-lg px-3 py-1.5 text-xs text-[#242424] focus:outline-none focus:border-[#C5A059]">
                    <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>الأحدث</option>
                    <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>السعر: من الأقل للأعلى</option>
                    <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>السعر: من الأعلى للأقل</option>
                </select>
            </form>
        </div>

        <!-- Products Grid -->
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 sm:gap-6">
            @forelse($products as $product)
                <x-product-card :product="$product" />
            @empty
                <div class="col-span-full py-16 text-center text-xs text-[#777777]">
                    لا توجد منتجات متاحة في هذا التصنيف حالياً.
                </div>
            @endforelse
        </div>

        <div class="mt-12">
            {{ $products->links() }}
        </div>
    </div>
</x-app-layout>

