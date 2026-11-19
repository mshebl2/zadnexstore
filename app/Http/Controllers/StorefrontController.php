<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\HomepageBanner;
use App\Models\StoreSetting;
use App\Models\ContactMessage;
use Illuminate\Http\Request;

class StorefrontController extends Controller
{
    public function about()
    {
        $settings = StoreSetting::pluck('setting_value', 'setting_key')->toArray();
        $categories = Category::parents()->withCount('products')->get();

        return view('pages.about', compact('settings', 'categories'));
    }

    public function home()
    {
        try {
            $heroBanners = HomepageBanner::hero()->get();
            if ($heroBanners->isEmpty()) {
                $heroBanners = HomepageBanner::active()->get();
            }
            $secondaryBanners = HomepageBanner::secondary()->take(2)->get();
            $offerBanners = HomepageBanner::offers()->take(3)->get();

            $banners = $heroBanners;
            $categories = Category::parents()->with(['children'])->withCount('products')->get();
            $featuredProducts = Product::active()->featured()->with(['category', 'images'])->take(8)->get();
            $newArrivals = Product::active()->latest()->with(['category', 'images'])->take(8)->get();
            $settings = StoreSetting::pluck('setting_value', 'setting_key')->toArray();
        } catch (\Throwable $e) {
            $heroBanners = collect();
            $secondaryBanners = collect();
            $offerBanners = collect();
            $banners = collect();
            $categories = collect();
            $featuredProducts = collect();
            $newArrivals = collect();
            $settings = [];
        }

        return view('pages.home', compact(
            'heroBanners', 
            'secondaryBanners', 
            'offerBanners', 
            'banners', 
            'categories', 
            'featuredProducts', 
            'newArrivals',
            'settings'
        ));
    }

    public function products(Request $request)
    {
        $categories = Category::parents()->with('children')->get();
        $query = Product::active()->with(['category', 'images']);

        // Category filter (supports parent & subcategories)
        if ($request->filled('category')) {
            $selectedCat = Category::with('children')->where('slug', $request->category)->first();
            if ($selectedCat) {
                $catIds = $selectedCat->children->pluck('id')->push($selectedCat->id);
                $query->whereIn('category_id', $catIds);
            }
        }

        // Search filter
        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($sub) use ($q) {
                $sub->where('name_ar', 'like', "%{$q}%")
                    ->orWhere('name_en', 'like', "%{$q}%")
                    ->orWhere('description_ar', 'like', "%{$q}%")
                    ->orWhere('sku', 'like', "%{$q}%");
            });
        }

        // Sorting
        match ($request->get('sort', 'latest')) {
            'price_asc' => $query->orderBy('regular_price', 'asc'),
            'price_desc' => $query->orderBy('regular_price', 'desc'),
            default => $query->latest(),
        };

        $products = $query->paginate(12)->withQueryString();

        return view('pages.products', compact('products', 'categories'));
    }

    public function category($slug, Request $request)
    {
        $category = Category::with('children')->where('slug', $slug)->firstOrFail();
        $categoryIds = $category->children->pluck('id')->push($category->id);
        $query = Product::whereIn('category_id', $categoryIds)->active()->with(['images', 'category']);

        match ($request->get('sort', 'latest')) {
            'price_asc' => $query->orderBy('regular_price', 'asc'),
            'price_desc' => $query->orderBy('regular_price', 'desc'),
            default => $query->latest(),
        };

        $products = $query->paginate(12)->withQueryString();

        return view('pages.category', compact('category', 'products'));
    }

    public function product($slug)
    {
        $product = Product::active()->with(['category', 'images', 'variants'])->where('slug', $slug)->firstOrFail();
        $relatedProducts = Product::active()
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->take(4)
            ->get();

        return view('pages.product-detail', compact('product', 'relatedProducts'));
    }

    public function quickView($id)
    {
        $product = Product::active()->with(['category', 'images'])->findOrFail($id);

        return response()->json([
            'id' => $product->id,
            'name' => $product->name_ar,
            'slug' => $product->slug,
            'category' => $product->category->name_ar,
            'image' => $product->primary_image,
            'current_price' => $product->current_price,
            'regular_price' => (float) $product->regular_price,
            'has_discount' => $product->has_discount,
            'description' => $product->description_ar,
            'fragrance_top' => $product->fragrance_top,
            'stock' => $product->stock_quantity,
        ]);
    }

    public function liveSearch(Request $request)
    {
        $q = trim($request->get('q', ''));
        if (mb_strlen($q) < 2) {
            return response()->json(['products' => []]);
        }

        $products = Product::active()
            ->with(['category', 'images'])
            ->where(function ($sub) use ($q) {
                $sub->where('name_ar', 'like', "%{$q}%")
                    ->orWhere('name_en', 'like', "%{$q}%")
                    ->orWhere('description_ar', 'like', "%{$q}%")
                    ->orWhere('sku', 'like', "%{$q}%");
            })
            ->take(6)
            ->get()
            ->map(function ($p) {
                return [
                    'id' => $p->id,
                    'name' => $p->name_ar,
                    'category' => $p->category?->name_ar,
                    'slug' => $p->slug,
                    'url' => route('product.show', $p->slug),
                    'price' => number_format($p->current_price, 2) . ' ر.س',
                    'regular_price' => $p->has_discount ? number_format($p->regular_price, 2) . ' ر.س' : null,
                    'image' => $p->primary_image,
                ];
            });

        return response()->json(['products' => $products]);
    }

    public function policyShipping()
    {
        $title = 'الشحن والتوصيل';
        $content = '<p>نوفر خدمة التوصيل السريع والمكيف لكافة المواد الغذائية، المشروبات، المنظفات والكماليات المنزلية لضمان سلامة وجودة السلع وفق اشتراطات سلامة الغذاء.</p>
        <p>• مدة التوصيل داخل مكة المكرمة: خلال ساعات من تأكيد الطلب أو خلال نفس اليوم.</p>
        <p>• مدة التوصيل لمدينة جدة والطائف وباقي مدن المملكة: من 24 إلى 48 ساعة عمل.</p>
        <p>• الشحن مجاني لكافة الطلبات التي تزيد قيمتها عن 199 ر.س.</p>
        <p>• التوصيل متاح يومياً مع فريق توصيل متخصص يراعي معايير النظافة والتعقيم وسلامة العبوات.</p>';
        return view('pages.policy', compact('title', 'content'));
    }

    public function policyReturns()
    {
        $title = 'سياسة الاستبدال والاسترجاع';
        $content = '<p>رضاكم وسلامتكم هما أولويتنا القصوى في متجر زاد نيكس. نتيح الاسترجاع والاستبدال وفق الضوابط الصحية والأنظمة التجارية المعمول بها في المملكة العربية السعودية:</p>
        <p>• المواد التموينية والمنظفات والكماليات غير القابلة للتلف السريع: يمكن استرجاعها أو استبدالها خلال 7 أيام من الاستلام بشرط أن تكون بحالتها الأصلية وتغليفها المصنعي المحكم وغير مفتوحة.</p>
        <p>• المنتجات والمواد الغذائية الطازجة المفتوحة: حرصاً على الصحة العامة وسلامة الغذاء المعتمدة بشهادتنا رقم 101565890، لا يمكن استرجاع المنتجات الغذائية بعد فتح غلافها ما لم يكن هناك عيب مصنعي أو خطأ في التوريد.</p>
        <p>• في حال وصول أي منتج تالف أو به كسر ناتج عن الشحن أو خطأ في الصنف، يتم استبداله فوراً أو استرجاع كامل قيمته دون أي تكاليف إضافية على العميل.</p>';
        return view('pages.policy', compact('title', 'content'));
    }

    public function policyPrivacy()
    {
        $title = 'سياسة الخصوصية والأمان';
        $content = '<p>يلتزم متجر زاد نيكس (سجل تجاري 7055435114) بحماية بياناتكم الشخصية وخصوصيتكم وفقاً لنظام حماية البيانات الشخصية المعمول به في المملكة العربية السعودية.</p>
        <p>• نقوم بجمع واستخدام بيانات الاتصال والعنوان حصرياً لغرض معالجة وتوصيل طلباتكم وتقديم الدعم الفني.</p>
        <p>• لا يتم مشاركة أو بيع بياناتكم لأي أطراف ثالثة، وتتم معالجة المدفوعات الإلكترونية عبر بوابات الدفع المشفرة المعتمدة لدى البنك المركزي السعودي (مدى، فيزا، ماستركارد، أبل باي، وتمارا).</p>';
        return view('pages.policy', compact('title', 'content'));
    }

    public function policyTerms()
    {
        $title = 'الشروط والأحكام';
        $content = '<p>أهلاً بكم في متجر زاد نيكس الإلكتروني (https://www.zadnexstore.com/). استخدامك للمتجر يعني موافقتك الكاملة على هذه الشروط والأحكام:</p>
        <p>• متجر زاد نيكس منشأة سعودية مرخصة بسجل تجاري رقم 7055435114 ومسجلة بنشاط تجزئة البقالات (كود الأيزك 471131).</p>
        <p>• جميع الأسعار المعروضة في المتجر تشمل ضريبة القيمة المضافة وفق الأنظمة واللوائح السعودية.</p>
        <p>• نضمن مطابقة كافة السلع والمواد الغذائية المعروضة لتواريخ الصلاحية واشتراطات الهيئة العامة للغذاء والدواء.</p>';
        return view('pages.policy', compact('title', 'content'));
    }

    public function newsletterSubscribe(Request $request)
    {
        $request->validate(['email' => 'required|email']);
        return back()->with('success', 'شكراً لاشتراكك في نشرة عروض متجر زاد نيكس التوفيرية.');
    }

    public function contact()
    {
        $settings = [
            'phone' => StoreSetting::get('contact_phone', '+966503606971'),
            'whatsapp' => StoreSetting::get('whatsapp_number', '966503606971'),
            'email' => StoreSetting::get('contact_email', 'info@zadnexstore.com'),
            'address' => StoreSetting::get('address', 'المملكة العربية السعودية، الرياض، طريق الملك فهد'),
            'commercial_registry' => StoreSetting::get('commercial_registry', '7025150512'),
            'tax_number' => StoreSetting::get('tax_number', '310458921000003'),
            'instagram' => StoreSetting::get('instagram', 'https://www.instagram.com/zadnexstore/'),
            'snapchat' => StoreSetting::get('snapchat', 'https://www.snapchat.com/add/zadnexstore'),
            'tiktok' => StoreSetting::get('tiktok', 'https://www.tiktok.com/@zadnexstore'),
        ];

        return view('pages.contact', compact('settings'));
    }

    public function contactSubmit(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'phone' => 'required|string|max:30',
            'email' => 'nullable|email|max:100',
            'subject' => 'nullable|string|max:150',
            'message' => 'required|string|max:2000',
        ], [
            'name.required' => 'يرجى كتابة الاسم الكريم.',
            'phone.required' => 'يرجى إدخال رقم الجوال للتواصل.',
            'message.required' => 'يرجى كتابة تفاصيل رسالتكم أو استفساركم.',
        ]);

        ContactMessage::create($validated);

        return back()->with('success', 'تم استلام رسالتكم بنجاح! سيتواصل معكم فريق خدمة عملاء متجر زاد نيكس في أقرب وقت.');
    }
}

