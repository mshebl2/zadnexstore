<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Category;
use App\Models\Coupon;
use App\Models\HomepageBanner;
use App\Models\FlashPopup;
use App\Models\ShippingZone;
use App\Models\StoreSetting;
use App\Models\ContactMessage;
use App\Models\PayoutRequest;
use App\Models\ReferralCommission;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    // 1. Dashboard Overview
    public function dashboard()
    {
        $totalRevenue = (float) Order::where('payment_status', 'paid')->sum('total');
        $totalOrdersCount = Order::count();
        $paidOrdersCount = Order::where('payment_status', 'paid')->count();
        $pendingOrdersCount = Order::where('status', 'pending')->orWhere('status', 'processing')->count();
        $lowStockCount = Product::where('stock_quantity', '<=', 5)->count();
        $recentOrders = Order::latest()->take(6)->get();

        return view('admin.dashboard', compact(
            'totalRevenue',
            'totalOrdersCount',
            'paidOrdersCount',
            'pendingOrdersCount',
            'lowStockCount',
            'recentOrders'
        ));
    }

    // 2. Products
    public function productsIndex()
    {
        $products = Product::with('category')->latest()->paginate(15);
        return view('admin.products.index', compact('products'));
    }

    public function productsCreate()
    {
        $categories = Category::with('children')->whereNull('parent_id')->get();
        $allCategories = Category::active()->get();
        return view('admin.products.create', compact('categories', 'allCategories'));
    }

    public function productsStore(Request $request)
    {
        $request->validate([
            'name_ar' => 'required|string|max:200',
            'category_id' => 'required|exists:categories,id',
            'sku' => 'required|string|unique:products,sku',
            'regular_price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|lt:regular_price',
            'stock_quantity' => 'required|integer|min:0',
            'description_ar' => 'required|string',
            'image_file' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:10240',
            'image_url' => 'nullable|string',
            'gallery_files.*' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:10240',
        ]);

        $product = Product::create([
            'category_id' => $request->category_id,
            'name_ar' => $request->name_ar,
            'name_en' => $request->name_en,
            'slug' => Str::slug($request->name_en ?? $request->name_ar) . '-' . rand(100, 999),
            'sku' => $request->sku,
            'description_ar' => $request->description_ar,
            'regular_price' => $request->regular_price,
            'sale_price' => $request->sale_price,
            'stock_quantity' => $request->stock_quantity,
            'size' => $request->size,
            'fragrance_top' => $request->fragrance_top,
            'fragrance_heart' => $request->fragrance_heart,
            'fragrance_base' => $request->fragrance_base,
            'seo_title' => $request->seo_title,
            'seo_description' => $request->seo_description,
            'is_featured' => $request->boolean('is_featured'),
            'is_active' => $request->boolean('is_active'),
        ]);

        // Primary Image (Mobile file upload priority over URL)
        $primaryImagePath = null;
        if ($request->hasFile('image_file')) {
            $path = $request->file('image_file')->store('products', 'public');
            $primaryImagePath = asset('storage/' . $path);
        } elseif ($request->filled('image_url')) {
            $primaryImagePath = $request->image_url;
        }

        if ($primaryImagePath) {
            ProductImage::create([
                'product_id' => $product->id,
                'image_path' => $primaryImagePath,
                'is_primary' => true,
                'sort_order' => 0,
            ]);
        }

        // Additional Gallery Photos
        if ($request->hasFile('gallery_files')) {
            $sort = 1;
            foreach ($request->file('gallery_files') as $file) {
                $path = $file->store('products', 'public');
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => asset('storage/' . $path),
                    'is_primary' => false,
                    'sort_order' => $sort++,
                ]);
            }
        }

        return redirect()->route('admin.products.index')->with('success', 'تم إنشاء المنتج وحفظ الصور بنجاح');
    }

    public function productsEdit($id)
    {
        $product = Product::with(['category', 'images'])->findOrFail($id);
        $categories = Category::with('children')->whereNull('parent_id')->get();
        $allCategories = Category::active()->get();
        return view('admin.products.edit', compact('product', 'categories', 'allCategories'));
    }

    public function productsUpdate(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $request->validate([
            'name_ar' => 'required|string|max:200',
            'category_id' => 'required|exists:categories,id',
            'sku' => 'required|string|unique:products,sku,' . $product->id,
            'regular_price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|lt:regular_price',
            'stock_quantity' => 'required|integer|min:0',
            'description_ar' => 'required|string',
            'image_file' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:10240',
            'image_url' => 'nullable|string',
            'gallery_files.*' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:10240',
        ]);

        $product->update([
            'category_id' => $request->category_id,
            'name_ar' => $request->name_ar,
            'name_en' => $request->name_en,
            'sku' => $request->sku,
            'description_ar' => $request->description_ar,
            'regular_price' => $request->regular_price,
            'sale_price' => $request->sale_price,
            'stock_quantity' => $request->stock_quantity,
            'size' => $request->size,
            'fragrance_top' => $request->fragrance_top,
            'fragrance_heart' => $request->fragrance_heart,
            'fragrance_base' => $request->fragrance_base,
            'seo_title' => $request->seo_title,
            'seo_description' => $request->seo_description,
            'is_featured' => $request->boolean('is_featured'),
            'is_active' => $request->boolean('is_active'),
        ]);

        // Primary Image (file upload takes priority)
        $primaryImagePath = null;
        if ($request->hasFile('image_file')) {
            $path = $request->file('image_file')->store('products', 'public');
            $primaryImagePath = asset('storage/' . $path);
        } elseif ($request->filled('image_url')) {
            $primaryImagePath = $request->image_url;
        }

        if ($primaryImagePath) {
            $image = $product->images()->where('is_primary', true)->first() ?: $product->images()->first();
            if ($image) {
                $image->update(['image_path' => $primaryImagePath]);
            } else {
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $primaryImagePath,
                    'is_primary' => true,
                    'sort_order' => 0,
                ]);
            }
        }

        // Additional Gallery Photos
        if ($request->hasFile('gallery_files')) {
            $lastSort = (int) $product->images()->max('sort_order') + 1;
            foreach ($request->file('gallery_files') as $file) {
                $path = $file->store('products', 'public');
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => asset('storage/' . $path),
                    'is_primary' => false,
                    'sort_order' => $lastSort++,
                ]);
            }
        }

        return redirect()->route('admin.products.index')->with('success', 'تم تحديث بيانات المنتج وصوره بنجاح');
    }

    public function productsDestroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();
        return redirect()->route('admin.products.index')->with('success', 'تم حذف المنتج بنجاح');
    }

    public function productsTemplate()
    {
        $filename = 'products-sample-template.csv';
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $callback = function () {
            $handle = fopen('php://output', 'w');
            fwrite($handle, "\xEF\xBB\xBF"); // UTF-8 BOM for Arabic support in Excel

            fputcsv($handle, [
                'name_ar',
                'name_en',
                'sku',
                'category',
                'regular_price',
                'sale_price',
                'stock_quantity',
                'size',
                'description_ar',
                'image_url',
                'seo_title',
                'seo_description',
            ]);

            fputcsv($handle, [
                'أرز بسمتي سيلا هندي كلاسيك 5 كجم',
                'Classic Basmati Rice 5KG',
                'INC-PRF-801',
                'مواد غذائية',
                '395.00',
                '320.00',
                '25',
                '100 مل',
                'أرز بسمتي هندي طويل الحبة عالي الجودة مناسب للكبسة والبرياني ومائدة العائلة.',
                'https://images.unsplash.com/photo-1594035910387-fea47794261f?auto=format&fit=crop&w=800&q=80',
                'أرز بسمتي سيلا هندي كلاسيك 5 كجم | متجر زاد نيكس',
                'تسوق أجود أنواع الأرز البسمتي والمواد الغذائية والتموينية بأسعار مميزة وتوصيل سريع.',
            ]);

            fputcsv($handle, [
                'سائل غسيل الأطباق فيري بالليمون 1 لتر',
                'Maroki King Agarwood',
                'INC-OUD-802',
                'منظفات',
                '210.00',
                '',
                '40',
                'أوقية (30 جرام)',
                'سائل غسيل الأطباق المركز بقوة الليمون الفعالة لإزالة الدهون العنيدة وحماية اليدين.',
                'https://images.unsplash.com/photo-1592945403244-b3fbafd7f539?auto=format&fit=crop&w=800&q=80',
                'سائل غسيل الأطباق فيري بالليمون 1 لتر | متجر زاد نيكس',
                'تسوق أفضل المنظفات المنزلية ومستلزمات العناية بنظافة منزلك من متجر زاد نيكس.',
            ]);

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function productsImport(Request $request)
    {
        $request->validate([
            'file' => 'required|file|max:10240',
        ]);

        $file = $request->file('file');
        $content = file_get_contents($file->getRealPath());

        if (str_starts_with($content, "\xEF\xBB\xBF")) {
            $content = substr($content, 3);
        }

        $lines = preg_split('/\r\n|\r|\n/', trim($content));
        if (empty($lines)) {
            return back()->with('error', 'الملف المرفوع فارغ.');
        }

        $delimiter = (substr_count($lines[0], ';') > substr_count($lines[0], ',')) ? ';' : ',';

        $handle = fopen('php://memory', 'r+');
        fwrite($handle, $content);
        rewind($handle);

        $header = fgetcsv($handle, 0, $delimiter);
        if (!$header) {
            fclose($handle);
            return back()->with('error', 'تعذر قراءة ترويسة ملف الـ CSV.');
        }

        $headerMap = [];
        foreach ($header as $idx => $col) {
            $cleanCol = str_replace(['_', '-'], '', strtolower(trim($col)));
            $cleanCol = preg_replace('/[^a-z0-9]/', '', $cleanCol);
            $headerMap[$cleanCol] = $idx;
        }

        if (!isset($headerMap['namear']) && !isset($headerMap['name'])) {
            fclose($handle);
            return back()->with('error', 'الملف غير مطابق للنموذج المطلوب. يجب أن يحتوي على عمود name_ar أو name.');
        }

        $nameCol = $headerMap['namear'] ?? $headerMap['name'];
        $nameEnCol = $headerMap['nameen'] ?? null;
        $skuCol = $headerMap['sku'] ?? null;
        $catCol = $headerMap['category'] ?? $headerMap['categoryid'] ?? null;
        $priceCol = $headerMap['regularprice'] ?? $headerMap['price'] ?? null;
        $salePriceCol = $headerMap['saleprice'] ?? null;
        $stockCol = $headerMap['stockquantity'] ?? $headerMap['stock'] ?? null;
        $sizeCol = $headerMap['size'] ?? null;
        $descCol = $headerMap['descriptionar'] ?? $headerMap['description'] ?? null;
        $imgCol = $headerMap['imageurl'] ?? $headerMap['image'] ?? null;
        $seoTitleCol = $headerMap['seotitle'] ?? null;
        $seoDescCol = $headerMap['seodescription'] ?? null;

        $createdCount = 0;
        $updatedCount = 0;

        $defaultCategory = Category::first() ?: Category::create([
            'name_ar' => 'مواد غذائية وتموين عام',
            'name_en' => 'General Perfumes',
            'slug' => 'general-perfumes',
            'is_active' => true,
        ]);

        while (($row = fgetcsv($handle, 0, $delimiter)) !== false) {
            if (empty($row) || count(array_filter($row)) === 0) {
                continue;
            }

            $nameAr = isset($row[$nameCol]) ? trim($row[$nameCol]) : '';
            if (empty($nameAr)) {
                continue;
            }

            $nameEn = ($nameEnCol !== null && isset($row[$nameEnCol])) ? trim($row[$nameEnCol]) : null;
            $sku = ($skuCol !== null && isset($row[$skuCol]) && trim($row[$skuCol]) !== '')
                ? trim($row[$skuCol])
                : 'INC-' . strtoupper(Str::random(6));

            $categoryId = $defaultCategory->id;
            if ($catCol !== null && isset($row[$catCol]) && trim($row[$catCol]) !== '') {
                $catName = trim($row[$catCol]);
                $category = Category::where('name_ar', $catName)
                    ->orWhere('name_en', $catName)
                    ->orWhere('id', is_numeric($catName) ? (int)$catName : 0)
                    ->first();
                if (!$category) {
                    $category = Category::create([
                        'name_ar' => $catName,
                        'name_en' => Str::slug($catName) ?: 'category-' . rand(10, 999),
                        'slug' => (Str::slug($catName) ?: 'category') . '-' . rand(100, 999),
                        'is_active' => true,
                    ]);
                }
                $categoryId = $category->id;
            }

            $regularPrice = ($priceCol !== null && isset($row[$priceCol]) && is_numeric(trim($row[$priceCol])))
                ? (float)trim($row[$priceCol])
                : 100.00;

            $salePrice = ($salePriceCol !== null && isset($row[$salePriceCol]) && is_numeric(trim($row[$salePriceCol])))
                ? (float)trim($row[$salePriceCol])
                : null;

            $stock = ($stockCol !== null && isset($row[$stockCol]) && is_numeric(trim($row[$stockCol])))
                ? (int)trim($row[$stockCol])
                : 15;

            $size = ($sizeCol !== null && isset($row[$sizeCol])) ? trim($row[$sizeCol]) : null;
            $desc = ($descCol !== null && isset($row[$descCol])) ? trim($row[$descCol]) : $nameAr;
            $imageUrl = ($imgCol !== null && isset($row[$imgCol])) ? trim($row[$imgCol]) : null;
            $seoTitle = ($seoTitleCol !== null && isset($row[$seoTitleCol])) ? trim($row[$seoTitleCol]) : null;
            $seoDesc = ($seoDescCol !== null && isset($row[$seoDescCol])) ? trim($row[$seoDescCol]) : null;

            $existingProduct = Product::where('sku', $sku)->first();

            if ($existingProduct) {
                $existingProduct->update([
                    'category_id' => $categoryId,
                    'name_ar' => $nameAr,
                    'name_en' => $nameEn ?: $existingProduct->name_en,
                    'regular_price' => $regularPrice,
                    'sale_price' => $salePrice,
                    'stock_quantity' => $stock,
                    'size' => $size ?: $existingProduct->size,
                    'description_ar' => $desc ?: $existingProduct->description_ar,
                    'seo_title' => $seoTitle ?: $existingProduct->seo_title,
                    'seo_description' => $seoDesc ?: $existingProduct->seo_description,
                    'is_active' => true,
                ]);

                if ($imageUrl) {
                    $primaryImg = $existingProduct->images()->where('is_primary', true)->first();
                    if ($primaryImg) {
                        $primaryImg->update(['image_path' => $imageUrl]);
                    } else {
                        ProductImage::create([
                            'product_id' => $existingProduct->id,
                            'image_path' => $imageUrl,
                            'is_primary' => true,
                            'sort_order' => 0,
                        ]);
                    }
                }
                $updatedCount++;
            } else {
                $slugBase = Str::slug($nameEn ?: '') ?: 'perfume';
                $product = Product::create([
                    'category_id' => $categoryId,
                    'name_ar' => $nameAr,
                    'name_en' => $nameEn,
                    'slug' => $slugBase . '-' . rand(100, 999) . '-' . time(),
                    'sku' => $sku,
                    'regular_price' => $regularPrice,
                    'sale_price' => $salePrice,
                    'stock_quantity' => $stock,
                    'size' => $size,
                    'description_ar' => $desc,
                    'seo_title' => $seoTitle,
                    'seo_description' => $seoDesc,
                    'is_active' => true,
                ]);

                $img = $imageUrl ?: 'https://images.unsplash.com/photo-1594035910387-fea47794261f?auto=format&fit=crop&w=800&q=80';
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $img,
                    'is_primary' => true,
                    'sort_order' => 0,
                ]);
                $createdCount++;
            }
        }

        fclose($handle);

        return redirect()->route('admin.products.index')->with(
            'success',
            "اكتمل الاستيراد بنجاح! تم إنشاء {$createdCount} منتج جديد وتحديث {$updatedCount} منتج مسجل."
        );
    }

    // 3. Categories
    public function categoriesIndex()
    {
        $categories = Category::with(['products', 'parent', 'children'])->get();
        $parentCategories = Category::whereNull('parent_id')->get();
        return view('admin.categories.index', compact('categories', 'parentCategories'));
    }

    public function categoriesStore(Request $request)
    {
        $request->validate([
            'name_ar' => 'required|string|max:150',
            'parent_id' => 'nullable|exists:categories,id',
            'image_file' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif|max:10240',
            'image_url' => 'nullable|string',
        ]);

        $imagePath = 'https://images.unsplash.com/photo-1594035910387-fea47794261f?auto=format&fit=crop&w=600&q=80';
        if ($request->hasFile('image_file')) {
            $path = $request->file('image_file')->store('categories', 'public');
            $imagePath = asset('storage/' . $path);
        } elseif ($request->filled('image_url')) {
            $imagePath = $request->image_url;
        } elseif ($request->filled('image')) {
            $imagePath = $request->image;
        }

        Category::create([
            'parent_id' => $request->filled('parent_id') ? $request->parent_id : null,
            'name_ar' => $request->name_ar,
            'name_en' => $request->name_en,
            'slug' => Str::slug($request->name_en ?? $request->name_ar) . '-' . rand(10, 999),
            'image' => $imagePath,
            'description_ar' => $request->description_ar,
            'is_active' => true,
        ]);

        return back()->with('success', 'تم إنشاء القسم وحفظ الصورة بنجاح');
    }

    public function categoriesUpdate(Request $request, $id)
    {
        $category = Category::findOrFail($id);
        $request->validate([
            'name_ar' => 'required|string|max:150',
            'parent_id' => 'nullable|exists:categories,id',
            'image_file' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif|max:10240',
            'image_url' => 'nullable|string',
        ]);

        $imagePath = $category->image;
        if ($request->hasFile('image_file')) {
            $path = $request->file('image_file')->store('categories', 'public');
            $imagePath = asset('storage/' . $path);
        } elseif ($request->filled('image_url')) {
            $imagePath = $request->image_url;
        } elseif ($request->filled('image')) {
            $imagePath = $request->image;
        }

        $category->update([
            'parent_id' => $request->filled('parent_id') ? $request->parent_id : null,
            'name_ar' => $request->name_ar,
            'name_en' => $request->name_en,
            'description_ar' => $request->description_ar,
            'image' => $imagePath,
            'is_active' => $request->boolean('is_active'),
        ]);

        return back()->with('success', 'تم تحديث بيانات القسم وحفظ الصورة بنجاح');
    }

    public function categoriesDestroy($id)
    {
        Category::findOrFail($id)->delete();
        return back()->with('success', 'تم حذف القسم بنجاح');
    }

    // 4. Orders
    public function ordersIndex(Request $request)
    {
        $query = Order::latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        $orders = $query->paginate(15)->withQueryString();
        return view('admin.orders.index', compact('orders'));
    }

    public function ordersShow($id)
    {
        $order = Order::with(['items.product', 'payments'])->findOrFail($id);
        return view('admin.orders.show', compact('order'));
    }

    public function ordersInvoice($id)
    {
        $order = Order::with(['items.product', 'payments'])->findOrFail($id);
        return view('admin.orders.invoice', compact('order'));
    }

    public function ordersUpdateStatus(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        $order->status = $request->status;
        $order->payment_status = $request->payment_status;
        $order->save();

        return back()->with('success', 'تم تحديث حالة الطلب بنجاح');
    }

    public function ordersExport(Request $request)
    {
        $query = Order::with('items')->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        $orders = $query->get();
        $filename = 'orders-export-' . now()->format('Y-m-d_H-i') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $callback = function () use ($orders) {
            $handle = fopen('php://output', 'w');
            fwrite($handle, "\xEF\xBB\xBF"); // UTF-8 BOM for Excel Arabic

            fputcsv($handle, [
                'رقم الطلب',
                'تاريخ الطلب',
                'اسم العميل',
                'رقم الجوال',
                'البريد الإلكتروني',
                'المدينة',
                'الحي والشارع',
                'تفاصيل المبنى',
                'ملاحظات العميل',
                'طريقة الدفع',
                'حالة الدفع',
                'حالة التوصيل والشحن',
                'المجموع الفرعي (ر.س)',
                'رسوم الشحن (ر.س)',
                'مبلغ الخصم (ر.س)',
                'الإجمالي النهائي (ر.س)',
                'كوبون الخصم',
                'شركة الشحن',
                'رقم بوليصة / تتبع الشحنة',
                'المنتجات والكميات',
            ]);

            foreach ($orders as $order) {
                $itemsList = $order->items->map(function ($item) {
                    return ($item->product_name_ar ?: 'منتج') . ' (x' . $item->quantity . ' - ' . $item->price . ' ر.س)';
                })->implode(' | ');

                fputcsv($handle, [
                    $order->order_number,
                    $order->created_at->format('Y-m-d H:i:s'),
                    $order->customer_name,
                    $order->customer_phone,
                    $order->customer_email,
                    $order->city,
                    $order->district . ($order->street_address ? ' - ' . $order->street_address : ''),
                    $order->building_info,
                    $order->notes,
                    $order->payment_method,
                    $order->payment_status_label_ar,
                    $order->status_label_ar,
                    $order->subtotal,
                    $order->shipping_cost,
                    $order->discount_amount,
                    $order->total,
                    $order->coupon_code,
                    $order->shipping_company ?: 'MapIt',
                    $order->shipping_tracking_number,
                    $itemsList,
                ]);
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function ordersDestroy($id)
    {
        $order = Order::findOrFail($id);
        $order->items()->delete();
        $order->payments()->delete();
        $order->delete();

        return redirect()->route('admin.orders.index')->with('success', 'تم حذف الطلب وسجلاته بنجاح');
    }

    public function ordersShipMapit($id, \App\Services\MapitService $mapitService)
    {
        $order = Order::with('items')->findOrFail($id);
        $res = $mapitService->createShipment($order);

        if ($res['success']) {
            return back()->with('success', 'تم إنشاء وإرسال الشحنة لمنصة مابت (MapIt) بنجاح! رقم البوليصة: ' . $res['order_number']);
        }

        return back()->with('error', $res['message']);
    }

    // 5. Coupons
    public function couponsIndex()
    {
        $coupons = Coupon::latest()->get();
        return view('admin.coupons.index', compact('coupons'));
    }

    public function couponsStore(Request $request)
    {
        $request->validate([
            'code' => 'required|string|unique:coupons,code',
            'type' => 'required|in:fixed,percent',
            'value' => 'required|numeric|min:0.01',
        ]);

        Coupon::create([
            'code' => strtoupper(trim($request->code)),
            'type' => $request->type,
            'value' => $request->value,
            'min_order_amount' => $request->min_order_amount ?? 0,
            'max_uses' => $request->max_uses,
            'is_active' => true,
        ]);

        return back()->with('success', 'تم إنشاء وتفعيل الكوبون بنجاح');
    }

    public function couponsDestroy($id)
    {
        Coupon::findOrFail($id)->delete();
        return back()->with('success', 'تم حذف الكوبون');
    }

    // 6. Homepage Banners & Section Management
    public function bannersIndex()
    {
        $banners = HomepageBanner::orderBy('sort_order', 'asc')->get();
        $products = Product::active()->select('id', 'name_ar', 'slug', 'regular_price')->get();
        $categories = Category::parents()->with('children')->get();
        $settings = StoreSetting::pluck('setting_value', 'setting_key')->toArray();

        return view('admin.banners.index', compact('banners', 'products', 'categories', 'settings'));
    }

    public function bannersStore(Request $request)
    {
        $request->validate([
            'title_ar' => 'required|string|max:255',
            'placement' => 'nullable|string|in:hero,secondary,offers,promo',
            'subtitle_ar' => 'nullable|string|max:500',
            'badge_ar' => 'nullable|string|max:100',
            'button_text_ar' => 'nullable|string|max:100',
            'button_url' => 'nullable|string|max:255',
            'link_type' => 'nullable|string|in:url,product,category',
            'link_target' => 'nullable|string|max:255',
            'image_file' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:10240',
            'image_url' => 'nullable|string|max:500',
            'mobile_image_file' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:10240',
            'mobile_image_url' => 'nullable|string|max:500',
            'sort_order' => 'nullable|integer',
            'starts_at' => 'nullable|date',
            'ends_at' => 'nullable|date|after_or_equal:starts_at',
        ]);

        $imageUrl = $request->image_url ?: 'https://images.unsplash.com/photo-1547887537-6158d64c35b3?auto=format&fit=crop&w=1920&q=80';
        $mobileImageUrl = $request->mobile_image_url;

        if ($request->hasFile('image_file')) {
            $path = $request->file('image_file')->store('banners', 'public');
            $imageUrl = asset('storage/' . $path);
        }

        if ($request->hasFile('mobile_image_file')) {
            $pathMobile = $request->file('mobile_image_file')->store('banners', 'public');
            $mobileImageUrl = asset('storage/' . $pathMobile);
        }

        $linkTarget = $request->link_target ?: $request->button_url ?: route('products.index');

        HomepageBanner::create([
            'placement' => $request->placement ?: 'hero',
            'badge_ar' => $request->badge_ar ?: 'تشكيلة حصرية',
            'title_ar' => $request->title_ar,
            'title_en' => $request->title_en ?: 'ROYAL COLLECTION',
            'subtitle_ar' => $request->subtitle_ar,
            'button_text_ar' => $request->button_text_ar ?: 'تسوق الآن',
            'button_url' => $linkTarget,
            'link_type' => $request->link_type ?: 'url',
            'link_target' => $linkTarget,
            'image_url' => $imageUrl,
            'mobile_image_url' => $mobileImageUrl ?: $imageUrl,
            'sort_order' => $request->sort_order ?? ((HomepageBanner::where('placement', $request->placement ?? 'hero')->max('sort_order') ?? 0) + 1),
            'is_active' => true,
            'starts_at' => $request->starts_at,
            'ends_at' => $request->ends_at,
        ]);

        return back()->with('success', 'تمت إضافة البنر الترويجي بنجاح');
    }

    public function bannersUpdate(Request $request, $id)
    {
        $banner = HomepageBanner::findOrFail($id);
        $request->validate([
            'title_ar' => 'required|string|max:255',
            'placement' => 'nullable|string|in:hero,secondary,offers,promo',
            'subtitle_ar' => 'nullable|string|max:500',
            'badge_ar' => 'nullable|string|max:100',
            'button_text_ar' => 'nullable|string|max:100',
            'button_url' => 'nullable|string|max:255',
            'link_type' => 'nullable|string|in:url,product,category',
            'link_target' => 'nullable|string|max:255',
            'image_file' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:10240',
            'image_url' => 'nullable|string|max:500',
            'mobile_image_file' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:10240',
            'mobile_image_url' => 'nullable|string|max:500',
            'sort_order' => 'nullable|integer',
            'starts_at' => 'nullable|date',
            'ends_at' => 'nullable|date|after_or_equal:starts_at',
        ]);

        $linkTarget = $request->link_target ?: $request->button_url ?: $banner->button_url;

        $data = [
            'placement' => $request->placement ?: $banner->placement,
            'badge_ar' => $request->badge_ar,
            'title_ar' => $request->title_ar,
            'subtitle_ar' => $request->subtitle_ar,
            'button_text_ar' => $request->button_text_ar,
            'button_url' => $linkTarget,
            'link_type' => $request->link_type ?: $banner->link_type,
            'link_target' => $linkTarget,
            'sort_order' => $request->sort_order ?? $banner->sort_order,
            'is_active' => $request->boolean('is_active'),
            'starts_at' => $request->starts_at,
            'ends_at' => $request->ends_at,
        ];

        if ($request->filled('image_url')) {
            $data['image_url'] = $request->image_url;
        }

        if ($request->hasFile('image_file')) {
            $path = $request->file('image_file')->store('banners', 'public');
            $data['image_url'] = asset('storage/' . $path);
        }

        if ($request->filled('mobile_image_url')) {
            $data['mobile_image_url'] = $request->mobile_image_url;
        }

        if ($request->hasFile('mobile_image_file')) {
            $pathMobile = $request->file('mobile_image_file')->store('banners', 'public');
            $data['mobile_image_url'] = asset('storage/' . $pathMobile);
        }

        $banner->update($data);

        return back()->with('success', 'تم تحديث بيانات البنر الترويجي بنجاح');
    }

    public function bannersDestroy($id)
    {
        HomepageBanner::findOrFail($id)->delete();
        return back()->with('success', 'تم حذف البنر بنجاح');
    }

    public function bannersToggle($id)
    {
        $banner = HomepageBanner::findOrFail($id);
        $banner->is_active = !$banner->is_active;
        $banner->save();
        return back()->with('success', $banner->is_active ? 'تم تفعيل ظهور البنر' : 'تم إيقاف ظهور البنر');
    }

    public function bannersReorder(Request $request)
    {
        $request->validate([
            'orders' => 'required|array',
            'orders.*.id' => 'required|exists:homepage_banners,id',
            'orders.*.sort_order' => 'required|integer',
        ]);

        foreach ($request->orders as $item) {
            HomepageBanner::where('id', $item['id'])->update(['sort_order' => $item['sort_order']]);
        }

        return response()->json(['success' => true, 'message' => 'تم حفظ الترتيب بنجاح']);
    }

    public function homepageSectionsUpdate(Request $request)
    {
        $keys = [
            'homepage_offers_title',
            'homepage_offers_subtitle',
            'homepage_section_hero_visible',
            'homepage_section_secondary_visible',
            'homepage_section_offers_visible',
            'homepage_section_categories_visible',
            'homepage_section_bestsellers_visible',
            'homepage_section_new_arrivals_visible',
            'homepage_section_story_visible',
        ];

        foreach ($keys as $k) {
            if ($request->has($k)) {
                StoreSetting::set($k, $request->input($k));
            }
        }

        // Toggle switches
        $toggles = [
            'homepage_section_hero_visible',
            'homepage_section_secondary_visible',
            'homepage_section_offers_visible',
            'homepage_section_categories_visible',
            'homepage_section_bestsellers_visible',
            'homepage_section_new_arrivals_visible',
            'homepage_section_story_visible',
        ];

        foreach ($toggles as $t) {
            StoreSetting::set($t, $request->has($t) ? '1' : '0');
        }

        return back()->with('success', 'تم حفظ إعدادات وأقسام الصفحة الرئيسية بنجاح');
    }

    // 7. Shipping Zones
    public function shippingIndex()
    {
        $zones = ShippingZone::orderBy('shipping_cost', 'asc')->get();
        return view('admin.shipping.index', compact('zones'));
    }

    public function shippingStore(Request $request)
    {
        $request->validate([
            'city_name_ar' => 'required|string|max:150',
            'shipping_cost' => 'required|numeric|min:0',
        ]);

        ShippingZone::create([
            'city_name_ar' => $request->city_name_ar,
            'city_name_en' => $request->city_name_en,
            'shipping_cost' => $request->shipping_cost,
            'free_shipping_threshold' => $request->free_shipping_threshold ?? 299,
            'estimated_days' => $request->estimated_days ?? '2 - 4 أيام عمل',
            'is_active' => true,
        ]);

        return back()->with('success', 'تمت إضافة منطقة الشحن بنجاح');
    }

    public function shippingUpdate(Request $request, $id)
    {
        $zone = ShippingZone::findOrFail($id);
        $request->validate([
            'city_name_ar' => 'required|string|max:150',
            'shipping_cost' => 'required|numeric|min:0',
        ]);

        $zone->update([
            'city_name_ar' => $request->city_name_ar,
            'city_name_en' => $request->city_name_en,
            'shipping_cost' => $request->shipping_cost,
            'free_shipping_threshold' => $request->free_shipping_threshold,
            'estimated_days' => $request->estimated_days,
            'is_active' => $request->boolean('is_active'),
        ]);

        return back()->with('success', 'تم تحديث منطقة الشحن بنجاح');
    }

    public function shippingDestroy($id)
    {
        ShippingZone::findOrFail($id)->delete();
        return back()->with('success', 'تم حذف منطقة الشحن');
    }

    // 8. Flash Offer Popups (Multiple Surprise Flash Ads)
    public function popupAdIndex()
    {
        $popups = FlashPopup::latest()->get();
        $coupons = Coupon::where('is_active', true)->get();
        $settings = StoreSetting::pluck('setting_value', 'setting_key')->toArray();
        return view('admin.popup.index', compact('popups', 'coupons', 'settings'));
    }

    public function popupStore(Request $request)
    {
        if ($request->has('popup_ad_title')) {
            return $this->popupAdUpdate($request);
        }

        $request->validate([
            'title_ar' => 'required|string|max:255',
            'subtitle_ar' => 'nullable|string|max:500',
            'badge_ar' => 'nullable|string|max:100',
            'coupon_code' => 'nullable|string|max:50',
            'button_text_ar' => 'nullable|string|max:100',
            'button_url' => 'nullable|string|max:255',
            'delay_seconds' => 'nullable|integer|min:0',
            'duration_seconds' => 'nullable|integer|min:1',
            'image_file' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:10240',
            'image_url' => 'nullable|string|max:500',
        ]);

        $imageUrl = $request->image_url ?: 'https://images.unsplash.com/photo-1592945403244-b3fbafd7f539?auto=format&fit=crop&w=800&q=80';

        if ($request->hasFile('image_file')) {
            $path = $request->file('image_file')->store('popups', 'public');
            $imageUrl = asset('storage/' . $path);
        }

        FlashPopup::create([
            'title_ar' => $request->title_ar,
            'subtitle_ar' => $request->subtitle_ar ?: 'استمتع بخصم فوري حصري عند إتمام طلبك اليوم',
            'badge_ar' => $request->badge_ar ?: 'عرض مفاجئ وحصري',
            'coupon_code' => $request->coupon_code,
            'button_text_ar' => $request->button_text_ar ?: 'تسوّق الآن واستفد من العرض',
            'button_url' => $request->button_url ?: route('products.index'),
            'image_url' => $imageUrl,
            'delay_seconds' => $request->delay_seconds ?? 3,
            'duration_seconds' => $request->duration_seconds ?? 5,
            'is_active' => true,
        ]);

        return back()->with('success', 'تم إنشاء وتفعيل الإعلان المفاجئ بنجاح');
    }

    public function popupUpdate(Request $request, $id)
    {
        $popup = FlashPopup::findOrFail($id);
        $request->validate([
            'title_ar' => 'required|string|max:255',
            'subtitle_ar' => 'nullable|string|max:500',
            'badge_ar' => 'nullable|string|max:100',
            'coupon_code' => 'nullable|string|max:50',
            'button_text_ar' => 'nullable|string|max:100',
            'button_url' => 'nullable|string|max:255',
            'delay_seconds' => 'nullable|integer|min:0',
            'duration_seconds' => 'nullable|integer|min:1',
            'image_file' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:10240',
            'image_url' => 'nullable|string|max:500',
        ]);

        $data = [
            'title_ar' => $request->title_ar,
            'subtitle_ar' => $request->subtitle_ar,
            'badge_ar' => $request->badge_ar,
            'coupon_code' => $request->coupon_code,
            'button_text_ar' => $request->button_text_ar,
            'button_url' => $request->button_url,
            'delay_seconds' => $request->delay_seconds ?? 3,
            'duration_seconds' => $request->duration_seconds ?? 5,
            'is_active' => $request->boolean('is_active'),
        ];

        if ($request->filled('image_url')) {
            $data['image_url'] = $request->image_url;
        }

        if ($request->hasFile('image_file')) {
            $path = $request->file('image_file')->store('popups', 'public');
            $data['image_url'] = asset('storage/' . $path);
        }

        $popup->update($data);

        return back()->with('success', 'تم تحديث بيانات الإعلان المفاجئ بنجاح');
    }

    public function popupDestroy($id)
    {
        FlashPopup::findOrFail($id)->delete();
        return back()->with('success', 'تم حذف الإعلان المفاجئ بنجاح');
    }

    public function popupToggle($id)
    {
        $popup = FlashPopup::findOrFail($id);
        $popup->is_active = !$popup->is_active;
        $popup->save();
        return back()->with('success', $popup->is_active ? 'تم تفعيل الإعلان' : 'تم تعطيل الإعلان');
    }

    public function popupAdUpdate(Request $request)
    {
        $keys = [
            'popup_ad_title',
            'popup_ad_subtitle',
            'popup_ad_coupon',
            'popup_ad_badge',
            'popup_ad_delay',
            'popup_ad_duration',
            'popup_ad_image',
            'paymob_mode',
            'paymob_api_key',
            'paymob_public_key',
            'paymob_secret_key',
            'paymob_hmac_secret',
            'paymob_integration_id',
            'paymob_apple_pay_integration_id',
            'paymob_iframe_id',
            'paymob_currency',
            'tamara_enabled',
            'tamara_mode',
            'tamara_api_token',
            'tamara_notification_token',
            'tamara_public_key',
            'tabby_public_key',
            'tabby_secret_key',
            'mapit_api_key',
            'mapit_enabled',
            'meta_title',
            'meta_description',
            'meta_keywords',
            'google_site_verification',
            'google_analytics_id',
            'google_tag_manager_id',
            'google_ads_id',
            'meta_pixel_id',
            'tiktok_pixel_id',
            'snapchat_pixel_id',
            'custom_head_scripts',
            'custom_body_scripts',
        ];

        foreach ($keys as $k) {
            if ($request->has($k)) {
                StoreSetting::set($k, $request->input($k));
            }
        }

        StoreSetting::set('popup_ad_active', $request->has('popup_ad_active') ? '1' : '0');

        return back()->with('success', 'تم تحديث إعدادات الإعلان والعرض المفاجئ بنجاح');
    }

    // 9. Store General Settings
    public function settingsIndex()
    {
        $settings = StoreSetting::pluck('setting_value', 'setting_key')->toArray();
        return view('admin.settings.index', compact('settings'));
    }

    public function settingsUpdate(Request $request)
    {
        $keys = [
            'store_name',
            'store_tagline',
            'whatsapp_number',
            'contact_phone',
            'contact_email',
            'commercial_registry',
            'municipal_license',
            'safety_certificate',
            'tax_number',
            'maroof_link',
            'address',
            'announcement_text',
            'instagram',
            'snapchat',
            'tiktok',
            'twitter',
            'free_shipping_threshold',
            'popup_ad_title',
            'popup_ad_subtitle',
            'popup_ad_coupon',
            'popup_ad_badge',
            'popup_ad_delay',
            'popup_ad_duration',
            'popup_ad_image',
            'paymob_mode',
            'paymob_api_key',
            'paymob_public_key',
            'paymob_secret_key',
            'paymob_hmac_secret',
            'paymob_integration_id',
            'paymob_apple_pay_integration_id',
            'paymob_iframe_id',
            'paymob_currency',
            'tamara_enabled',
            'tamara_mode',
            'tamara_api_token',
            'tamara_notification_token',
            'tamara_public_key',
            'tabby_public_key',
            'tabby_secret_key',
            'mapit_api_key',
            'mapit_enabled',
            'meta_title',
            'meta_description',
            'meta_keywords',
            'google_site_verification',
            'google_analytics_id',
            'google_tag_manager_id',
            'google_ads_id',
            'meta_pixel_id',
            'tiktok_pixel_id',
            'snapchat_pixel_id',
            'custom_head_scripts',
            'custom_body_scripts',
        ];

        foreach ($keys as $k) {
            if ($request->has($k)) {
                StoreSetting::set($k, $request->input($k));
            }
        }

        StoreSetting::set('announcement_active', $request->has('announcement_active') ? '1' : '0');
        StoreSetting::set('popup_ad_active', $request->has('popup_ad_active') ? '1' : '0');
        StoreSetting::set('paymob_enabled', $request->has('paymob_enabled') ? '1' : '0');
        StoreSetting::set('tamara_enabled', $request->has('tamara_enabled') ? '1' : '0');
        StoreSetting::set('tabby_enabled', $request->has('tabby_enabled') ? '1' : '0');
        StoreSetting::set('whatsapp_order_enabled', $request->has('whatsapp_order_enabled') ? '1' : '0');
        StoreSetting::set('cod_enabled', $request->has('cod_enabled') ? '1' : '0');

        return back()->with('success', 'تم حفظ جميع إعدادات المتجر بنجاح');
    }

    /**
     * Trigger instant search engine ping for sitemap & IndexNow
     */
    public function pingSearchEngines()
    {
        $res = \App\Services\AutoIndexService::pingFullSitemap();
        return back()->with('success', "تم إرسال إشعار الأرشفة الفوري لمحركات البحث (IndexNow + Google + Bing) بنجاح لـ {$res['urls_count']} صفحة ورابط خريطة الموقع.");
    }

    // 10. Customer Inquiries & Messages
    public function messagesIndex()
    {
        $messages = ContactMessage::latest()->paginate(15);
        return view('admin.messages.index', compact('messages'));
    }

    public function messagesDestroy($id)
    {
        $message = ContactMessage::findOrFail($id);
        $message->delete();
        return back()->with('success', 'تم حذف الرسالة بنجاح.');
    }

    // 11. Affiliate & Payout Management
    public function affiliatesIndex(Request $request)
    {
        $search = $request->input('search');

        // Affiliates query (Users who have a referral code)
        $affiliatesQuery = User::whereNotNull('referral_code')
            ->withCount(['referredUsers', 'referralCommissions'])
            ->withSum('referralCommissions as total_commission_earned', 'commission_amount');

        if ($search) {
            $affiliatesQuery->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('referral_code', 'like', "%{$search}%");
            });
        }

        $affiliates = $affiliatesQuery->latest()->paginate(15, ['*'], 'affiliates_page');

        // Referred Customers query (Users who registered using a referral code)
        $referralSearch = $request->input('referral_search');
        $referredCustomersQuery = User::whereNotNull('referred_by')
            ->withCount('orders')
            ->withSum('orders as total_spent', 'total');

        if ($referralSearch) {
            $referredCustomersQuery->where(function ($q) use ($referralSearch) {
                $q->where('name', 'like', "%{$referralSearch}%")
                  ->orWhere('email', 'like', "%{$referralSearch}%")
                  ->orWhere('referred_by', 'like', "%{$referralSearch}%");
            });
        }

        $referredCustomers = $referredCustomersQuery->latest()->paginate(15, ['*'], 'referrals_page');

        $payoutRequests = PayoutRequest::with('user')->latest()->paginate(15, ['*'], 'payouts_page');
        $totalCommissionPaid = (float) ReferralCommission::sum('commission_amount');
        $totalAffiliatesCount = User::whereNotNull('referral_code')->count();
        $totalReferredUsersCount = User::whereNotNull('referred_by')->count();
        $recentCommissions = ReferralCommission::with(['referrer', 'buyer', 'order'])->latest()->take(10)->get();

        // Global settings
        $globalSettings = [
            'type' => StoreSetting::get('affiliate_commission_type', 'percent'),
            'rate' => StoreSetting::get('affiliate_commission_rate', '10.00'),
            'min_payout' => StoreSetting::get('affiliate_min_payout', '100.00'),
            'enabled' => StoreSetting::get('affiliate_enabled', '1'),
        ];

        return view('admin.affiliates.index', compact(
            'affiliates',
            'referredCustomers',
            'payoutRequests',
            'totalCommissionPaid',
            'totalAffiliatesCount',
            'totalReferredUsersCount',
            'recentCommissions',
            'globalSettings'
        ));
    }

    public function affiliateUpdateRate(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $request->validate([
            'custom_commission_type' => 'nullable|in:default,percent,fixed',
            'custom_commission_rate' => 'nullable|numeric|min:0|max:1000',
            'is_affiliate_active' => 'nullable|boolean',
        ]);

        $type = $request->input('custom_commission_type');
        if ($type === 'default' || empty($type)) {
            $user->custom_commission_type = null;
            $user->custom_commission_rate = null;
        } else {
            $user->custom_commission_type = $type;
            $user->custom_commission_rate = $request->input('custom_commission_rate', 10.00);
        }

        if ($request->has('is_affiliate_active')) {
            $user->is_affiliate_active = $request->boolean('is_affiliate_active');
        }

        $user->save();

        return back()->with('success', "تم تحديث إعدادات عمولة المسوق {$user->name} بنجاح.");
    }

    public function affiliateAdjustBalance(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $request->validate([
            'amount' => 'required|numeric',
            'action' => 'required|in:add,deduct,set',
            'note' => 'nullable|string|max:255',
        ]);

        $amount = (float) $request->input('amount');
        $action = $request->input('action');

        if ($action === 'add') {
            $user->increment('wallet_balance', $amount);
        } elseif ($action === 'deduct') {
            $user->decrement('wallet_balance', min($user->wallet_balance, $amount));
        } elseif ($action === 'set') {
            $user->wallet_balance = max(0, $amount);
            $user->save();
        }

        return back()->with('success', "تم تحديث رصيد محفظة المسوق {$user->name} بنجاح. الرصيد الحالي: {$user->wallet_balance} ر.س");
    }

    public function affiliateUpdateSettings(Request $request)
    {
        $request->validate([
            'affiliate_commission_type' => 'required|in:percent,fixed',
            'affiliate_commission_rate' => 'required|numeric|min:0|max:1000',
            'affiliate_min_payout' => 'required|numeric|min:1',
            'affiliate_enabled' => 'nullable|boolean',
        ]);

        StoreSetting::set('affiliate_commission_type', $request->input('affiliate_commission_type'));
        StoreSetting::set('affiliate_commission_rate', $request->input('affiliate_commission_rate'));
        StoreSetting::set('affiliate_min_payout', $request->input('affiliate_min_payout'));
        StoreSetting::set('affiliate_enabled', $request->boolean('affiliate_enabled') ? '1' : '0');

        return back()->with('success', 'تم حفظ إعدادات نظام الإحالات والعمولات العامة بنجاح.');
    }

    public function payoutUpdateStatus(Request $request, $id)
    {
        $payout = PayoutRequest::findOrFail($id);
        $status = $request->input('status', 'completed'); // completed, rejected
        $payout->status = $status;
        $payout->admin_notes = $request->input('admin_notes');
        $payout->save();

        if ($status === 'rejected') {
            // Refund to user's wallet
            $payout->user->increment('wallet_balance', $payout->amount);
        }

        return back()->with('success', 'تم تحديث حالة طلب سحب الأرباح بنجاح');
    }

    // 12. Admin Profile & Account Security Management
    public function profileIndex()
    {
        $admin = auth()->user();
        return view('admin.profile', compact('admin'));
    }

    public function profileUpdate(Request $request)
    {
        $admin = auth()->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $admin->id,
            'phone' => 'nullable|string|max:50',
            'current_password' => 'nullable|required_with:new_password',
            'new_password' => 'nullable|min:6|confirmed',
        ], [
            'name.required' => 'يرجى إدخال اسم المدير',
            'email.required' => 'يرجى إدخال البريد الإلكتروني',
            'email.unique' => 'البريد الإلكتروني مستخدم بالفعل بحساب آخر',
            'current_password.required_with' => 'يرجى إدخال كلمة المرور الحالية لتأكيد التغيير',
            'new_password.min' => 'يجب ألا تقل كلمة المرور الجديدة عن 6 أحرف',
            'new_password.confirmed' => 'تأكيد كلمة المرور الجديدة غير متطابق',
        ]);

        if ($request->filled('new_password')) {
            if (!Hash::check($request->input('current_password'), $admin->password)) {
                return back()->withErrors(['current_password' => 'كلمة المرور الحالية غير صحيحة.'])->withInput();
            }
            $admin->password = Hash::make($request->input('new_password'));
        }

        $admin->name = $request->input('name');
        $admin->email = $request->input('email');
        if ($request->filled('phone')) {
            $admin->phone = $request->input('phone');
        }
        $admin->save();

        return back()->with('success', 'تم تحديث بيانات حساب المدير وكلمة المرور بنجاح.');
    }
}

