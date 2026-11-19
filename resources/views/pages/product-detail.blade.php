<x-app-layout>
    <x-slot:title>{{ $product->seo_title ?? $product->name_ar . ' | متجر زاد نيكس' }}</x-slot:title>
    <x-slot:description>{{ $product->seo_description ?? Str::limit(strip_tags($product->description_ar), 160) }}</x-slot:description>

    <!-- Google Merchant & SEO Schema.org Product Rich Snippet -->
    <script type="application/ld+json">
    {
      "{{ '@' }}context": "https://schema.org/",
      "{{ '@' }}type": "Product",
      "name": "{{ $product->name_ar }}",
      "image": ["{{ $product->primary_image }}"],
      "description": "{{ Str::limit(strip_tags($product->description_ar), 200) }}",
      "sku": "{{ $product->sku }}",
      "brand": {
        "{{ '@' }}type": "Brand",
        "name": "متجر زاد نيكس"
      },
      "offers": {
        "{{ '@' }}type": "Offer",
        "url": "{{ url()->current() }}",
        "priceCurrency": "SAR",
        "price": "{{ $product->current_price }}",
        "availability": "{{ $product->stock_quantity > 0 ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock' }}",
        "seller": {
          "{{ '@' }}type": "Organization",
          "name": "متجر زاد نيكس"
        }
      }
    }
    </script>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12"
         x-data="{
            selectedImage: '{{ $product->primary_image }}',
            selectedVariantId: {{ $product->variants->first()?->id ?? 'null' }},
            basePrice: {{ $product->current_price }},
            regularPrice: {{ $product->regular_price }},
            currentPrice: {{ $product->variants->first()?->current_price ?? $product->current_price }},
            currentRegularPrice: {{ $product->variants->first()?->price ?? $product->regular_price }},
            quantity: 1,
            variants: {{ $product->variants->map(function($v) {
                return [
                    'id' => $v->id,
                    'title' => $v->title_ar,
                    'price' => $v->price,
                    'sale_price' => $v->sale_price,
                    'current_price' => $v->current_price,
                    'stock' => $v->stock_quantity,
                ];
            })->toJson() }},

            selectVariant(v) {
                this.selectedVariantId = v.id;
                this.currentPrice = v.current_price;
                this.currentRegularPrice = v.price;
            }
         }">

        <!-- Breadcrumbs -->
        <nav class="text-xs text-[#777777] mb-6 flex items-center gap-2">
            <a href="{{ route('home') }}" class="hover:text-[#C5A059]">الرئيسية</a>
            <span>/</span>
            <a href="{{ route('category.show', $product->category->slug) }}" class="hover:text-[#C5A059]">{{ $product->category->name_ar }}</a>
            <span>/</span>
            <span class="text-[#242424] font-medium truncate">{{ $product->name_ar }}</span>
        </nav>

        <!-- Main Product Card Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 lg:gap-14">
            
            <!-- Gallery Column -->
            <div class="space-y-4">
                <!-- Large Active Image -->
                <div class="aspect-square bg-white rounded-2xl border border-[#EDE6DA] overflow-hidden relative shadow-xs flex items-center justify-center p-4">
                    <img :src="selectedImage" 
                         alt="{{ $product->name_ar }}" 
                         class="w-full h-full object-contain transition-all duration-300">
                    
                    @if($product->has_discount)
                    <span class="absolute top-4 right-4 bg-[#C5A059] text-white text-xs font-bold px-3 py-1 rounded-full shadow-sm">
                        خصم {{ $product->discount_percentage }}%
                    </span>
                    @endif
                </div>

                <!-- Thumbnails Carousel -->
                @if($product->images->count() > 1)
                <div class="flex items-center gap-3 overflow-x-auto pb-2 scrollbar-none">
                    @foreach($product->images as $img)
                    <button type="button" 
                            @click="selectedImage = '{{ $img->image_path }}'"
                            :class="selectedImage === '{{ $img->image_path }}' ? 'border-[#C5A059] ring-2 ring-[#C5A059]/30' : 'border-[#EDE6DA] hover:border-[#C5A059]'"
                            class="w-20 h-20 rounded-xl overflow-hidden bg-white border p-1 transition flex-shrink-0">
                        <img src="{{ $img->image_path }}" alt="صورة مصغرة" class="w-full h-full object-cover rounded-lg">
                    </button>
                    @endforeach
                </div>
                @endif
            </div>

            <!-- Details & Purchase Column -->
            <div class="text-right space-y-6">
                <!-- Category & SKU -->
                <div class="flex items-center justify-between text-xs text-[#777777] border-b border-[#EDE6DA]/70 pb-3">
                    <a href="{{ route('category.show', $product->category->slug) }}" class="text-[#C5A059] font-medium hover:underline">
                        {{ $product->category->name_ar }}
                    </a>
                    <span>رمز المنتج (SKU): <strong class="text-[#242424]">{{ $product->sku }}</strong></span>
                </div>

                <!-- Title -->
                <h1 class="text-2xl sm:text-3xl font-extrabold text-[#242424] leading-snug">
                    {{ $product->name_ar }}
                </h1>

                <!-- Price Box -->
                <div class="bg-[#FAF8F4] p-4 rounded-xl border border-[#EDE6DA] flex items-baseline gap-3">
                    <span class="text-3xl font-black text-[#242424]">
                        <span x-text="currentPrice.toFixed(2)"></span> 
                        <span class="text-sm font-normal text-[#777777]">ر.س</span>
                    </span>
                    <template x-if="currentRegularPrice > currentPrice">
                        <span class="text-base text-[#A59D92] line-through" x-text="currentRegularPrice.toFixed(2) + ' ر.س'"></span>
                    </template>
                    <span class="text-xs text-emerald-700 bg-emerald-50 border border-emerald-200 px-2 py-0.5 rounded font-medium mr-auto">
                        شامل ضريبة القيمة المضافة
                    </span>
                </div>

                @if(\App\Models\StoreSetting::get('tamara_enabled', '0') === '1' || \App\Models\StoreSetting::get('tabby_enabled', '0') === '1')
                <!-- Tamara & Tabby Installments Summary -->
                <div class="space-y-2">
                    @if(\App\Models\StoreSetting::get('tamara_enabled', '0') === '1')
                    <tamara-widget 
                        type="tamara-summary" 
                        inline-type="2" 
                        :amount="currentPrice" 
                        inline-variant="tamara-logo-badge"
                        currency="SAR"
                        public-key="{{ \App\Models\StoreSetting::get('tamara_public_key', 'a713d53f-00db-4ec1-8fa6-97446013c0d9') }}"
                        country-code="SA"
                        lang="ar">
                    </tamara-widget>
                    @endif

                    <div class="p-3 bg-gradient-to-r from-[#FAF8F4] to-white rounded-xl border border-[#EDE6DA] flex items-center justify-between gap-3 text-xs shadow-2xs">
                        <div class="flex items-center gap-2 flex-wrap">
                            <span class="text-[#777777]">أو قسّم فاتورتك على 4 دفعات بقيمة</span>
                            <span class="font-bold text-[#242424]" x-text="(currentPrice / 4).toFixed(2) + ' ر.س'"></span>
                            <span class="text-[10px] text-emerald-700 bg-emerald-50 px-1.5 py-0.5 rounded font-bold">بدون فوائد</span>
                        </div>
                        <div class="flex items-center gap-2 flex-shrink-0">
                            @if(\App\Models\StoreSetting::get('tamara_enabled', '0') === '1')
                            <div class="h-6 px-1.5 bg-white rounded border border-[#EDE6DA] flex items-center justify-center">
                                <img src="{{ asset('images/payments/tamara.png') }}" alt="تمارا Tamara" class="h-4 w-auto object-contain">
                            </div>
                            @endif
                            @if(\App\Models\StoreSetting::get('tabby_enabled', '0') === '1')
                            <span class="bg-[#3BFF9E] text-black font-black text-[10px] px-2 py-0.5 rounded font-sans shadow-2xs">tabby</span>
                            @endif
                        </div>
                    </div>
                </div>
                @else
                <!-- Trial Mode Makkah Badge -->
                <div class="p-3 bg-emerald-50/70 border border-emerald-200/80 rounded-xl flex items-center justify-between gap-3 text-xs">
                    <div class="flex items-center gap-2 text-emerald-900 font-bold">
                        <span class="text-base">🚀</span>
                        <span>مرحلة الإطلاق التجريبي: توصيل سريع داخل مكة المكرمة</span>
                    </div>
                    <span class="bg-emerald-600 text-white text-[10px] font-bold px-2.5 py-1 rounded-lg shrink-0">
                        الدفع عند الاستلام (COD)
                    </span>
                </div>
                @endif

                <!-- Variant Selector (if available) -->
                @if($product->variants->count() > 0)
                <div class="space-y-2 pt-2">
                    <label class="block text-xs font-bold text-[#242424]">اختر الحجم أو السعة:</label>
                    <div class="flex flex-wrap gap-2.5">
                        <template x-for="v in variants" :key="v.id">
                            <button type="button" 
                                    @click="selectVariant(v)"
                                    :class="selectedVariantId === v.id ? 'bg-[#242424] text-white border-[#242424] shadow-sm' : 'bg-white text-[#242424] border-[#EDE6DA] hover:border-[#C5A059]'"
                                    class="px-4 py-2.5 rounded-xl border text-xs font-semibold transition flex items-center gap-2">
                                <span x-text="v.title"></span>
                                <span class="text-[11px] text-[#A59D92]" x-text="'(' + v.current_price.toFixed(2) + ' ر.س)'"></span>
                            </button>
                        </template>
                    </div>
                </div>
                @endif

                <!-- Quantity & Add to Cart -->
                <div class="pt-2 space-y-4">
                    <div class="flex items-center gap-4">
                        <!-- Quantity incrementor -->
                        <div class="flex items-center border border-[#EDE6DA] rounded-xl bg-white p-1">
                            <button type="button" @click="if(quantity > 1) quantity--" class="w-9 h-9 flex items-center justify-center text-[#777777] hover:text-[#242424] hover:bg-[#FAF8F4] rounded-lg transition">
                                <x-icon name="minus" class="w-4 h-4" />
                            </button>
                            <input type="text" readonly x-model="quantity" class="w-12 text-center text-sm font-bold text-[#242424] focus:outline-none">
                            <button type="button" @click="quantity++" class="w-9 h-9 flex items-center justify-center text-[#777777] hover:text-[#242424] hover:bg-[#FAF8F4] rounded-lg transition">
                                <x-icon name="plus" class="w-4 h-4" />
                            </button>
                        </div>

                        <!-- Add to cart CTA -->
                        <button type="button" 
                                @click="$store.cart.addItem({{ $product->id }}, selectedVariantId, quantity)"
                                class="flex-1 bg-[#C5A059] hover:bg-[#AF8B44] text-white font-bold text-sm py-3.5 px-6 rounded-xl shadow-lg flex items-center justify-center gap-2 transition duration-200">
                            <x-icon name="shopping-bag" class="w-5 h-5" />
                            <span>إضافة إلى سلة الشراء</span>
                        </button>
                    </div>

                    <!-- Direct WhatsApp Order Option -->
                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', \App\Models\StoreSetting::get('whatsapp_number', '966580760890')) }}?text={{ urlencode('مرحباً متجر زاد نيكس، أود الاستفسار عن منتج: ' . $product->name_ar) }}" 
                       target="_blank" 
                       class="w-full bg-[#25D366]/10 hover:bg-[#25D366]/20 text-[#128C7E] border border-[#25D366]/30 font-bold text-xs py-2.5 px-4 rounded-xl flex items-center justify-center gap-2 transition">
                        <x-icon name="whatsapp" class="w-4 h-4" />
                        <span>طلب فوري ومباشر عبر واتساب</span>
                    </a>
                </div>

                <!-- Shopping Guarantees Box -->
                <div class="bg-white rounded-xl border border-[#E2E8F0] p-4 text-xs text-[#64748B] space-y-2.5">
                    <div class="flex items-center gap-2">
                        <x-icon name="truck" class="w-4 h-4 text-[#0B2E75]" />
                        <span>شحن سريع ومبرد (داخل مكة المكرمة خلال ساعات، ولباقي المدن خلال 24-48 ساعة)</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <x-icon name="shield-check" class="w-4 h-4 text-[#0B2E75]" />
                        <span>سلامة غذائية موثقة 100% وشهادة سلامة معتمدة رقم 101565890</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <x-icon name="package" class="w-4 h-4 text-[#0B2E75]" />
                        <span>تغليف صحي محكم مطابق لاشتراطات هيئة الغذاء والدواء</span>
                    </div>
                </div>

                <!-- Product Specifications & Features -->
                @if($product->fragrance_top || $product->fragrance_heart || $product->fragrance_base)
                <div class="border border-[#E2E8F0] rounded-xl p-5 bg-white space-y-3">
                    <h3 class="text-xs font-bold text-[#0B2E75] uppercase tracking-wider flex items-center gap-1.5">
                        <x-icon name="layers" class="w-4 h-4 text-[#FBB404]" />
                        <span>مواصفات ومزايا المنتج</span>
                    </h3>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 pt-2 text-xs">
                        @if($product->fragrance_top)
                        <div class="p-3 bg-[#F8FAFC] rounded-lg border border-[#E2E8F0]">
                            <span class="font-bold text-[#0F172A] block mb-1">المواصفات:</span>
                            <span class="text-[#64748B]">{{ $product->fragrance_top }}</span>
                        </div>
                        @endif

                        @if($product->fragrance_heart)
                        <div class="p-3 bg-[#F8FAFC] rounded-lg border border-[#E2E8F0]">
                            <span class="font-bold text-[#0F172A] block mb-1">الجودة والسلامة:</span>
                            <span class="text-[#64748B]">{{ $product->fragrance_heart }}</span>
                        </div>
                        @endif

                        @if($product->fragrance_base)
                        <div class="p-3 bg-[#F8FAFC] rounded-lg border border-[#E2E8F0]">
                            <span class="font-bold text-[#0F172A] block mb-1">المصدر والتعبئة:</span>
                            <span class="text-[#64748B]">{{ $product->fragrance_base }}</span>
                        </div>
                        @endif
                    </div>
                </div>
                @endif

                <!-- Long Description -->
                <div class="prose prose-sm text-xs text-[#64748B] leading-relaxed pt-2">
                    <h3 class="text-sm font-bold text-[#0F172A] mb-2">تفاصيل المنتج:</h3>
                    <p class="whitespace-pre-line">{{ $product->description_ar }}</p>
                </div>
            </div>
        </div>

        <!-- Related Products Section -->
        @if($relatedProducts->count() > 0)
        <div class="mt-20 pt-12 border-t border-[#E2E8F0]">
            <div class="text-right mb-8">
                <span class="text-xs font-bold text-[#0B2E75] tracking-widest uppercase">قد يعجبك أيضاً</span>
                <h2 class="text-2xl font-extrabold text-[#0F172A] mt-1">منتجات وسلع مقترحة</h2>
            </div>

            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
                @foreach($relatedProducts as $relProduct)
                    <x-product-card :product="$relProduct" />
                @endforeach
            </div>
        </div>
        @endif

        <!-- Sticky Mobile Bottom Purchase Bar -->
        <div class="md:hidden fixed bottom-14 inset-x-0 bg-white/95 backdrop-blur-md border-t border-[#EDE6DA] p-3 z-25 flex items-center justify-between gap-3 shadow-lg">
            <div>
                <span class="text-[10px] text-[#777777] block">السعر:</span>
                <span class="text-sm font-bold text-[#242424]" x-text="currentPrice.toFixed(2) + ' ر.س'"></span>
            </div>
            <button type="button" 
                    @click="$store.cart.addItem({{ $product->id }}, selectedVariantId, quantity)"
                    class="bg-[#C5A059] hover:bg-[#AF8B44] text-white font-bold text-xs py-2.5 px-6 rounded-lg flex items-center gap-1.5 shadow-sm transition">
                <x-icon name="shopping-bag" class="w-4 h-4" />
                <span>أضف للسلة</span>
            </button>
        </div>

    </div>
</x-app-layout>

