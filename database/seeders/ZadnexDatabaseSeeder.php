<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use App\Models\ShippingZone;
use App\Models\Coupon;
use App\Models\HomepageBanner;
use App\Models\StoreSetting;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ZadnexDatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Disable foreign key checks for clean refresh (compatible with both SQLite and MySQL)
        if (DB::getDriverName() === 'sqlite') {
            DB::statement('PRAGMA foreign_keys = OFF;');
        } else {
            DB::statement('SET FOREIGN_KEY_CHECKS = 0;');
        }

        ProductImage::truncate();
        ProductVariant::truncate();
        Product::truncate();
        Category::truncate();
        HomepageBanner::truncate();
        Coupon::truncate();
        ShippingZone::truncate();
        StoreSetting::truncate();
        \App\Models\FlashPopup::truncate();
        User::truncate();

        if (DB::getDriverName() === 'sqlite') {
            DB::statement('PRAGMA foreign_keys = ON;');
        } else {
            DB::statement('SET FOREIGN_KEY_CHECKS = 1;');
        }

        // 1. Admin & Customer Users
        User::updateOrCreate(
            ['email' => 'info@zadnexstore.com'],
            [
                'name' => 'إدارة متجر زاد نيكس',
                'phone' => '+966580760890',
                'role' => 'admin',
                'password' => Hash::make('zadnex2026@admin'),
            ]
        );

        User::updateOrCreate(
            ['email' => 'admin@zadnexstore.com'],
            [
                'name' => 'مدير النظام',
                'phone' => '0580760890',
                'role' => 'admin',
                'password' => Hash::make('password123'),
            ]
        );

        User::updateOrCreate(
            ['email' => 'customer@zadnexstore.com'],
            [
                'name' => 'عبدالله السعد',
                'phone' => '0559876543',
                'role' => 'customer',
                'password' => Hash::make('password123'),
            ]
        );

        // 2. Categories: مواد غذائية، مشروبات، منظفات، كماليات
        $categoriesData = [
            [
                'name_ar' => 'مواد غذائية',
                'name_en' => 'Food Products',
                'slug' => 'food-products',
                'description_ar' => 'أجود المواد الغذائية والتموينية الأساسية، أرز فاخر، زيوت، بقوليات، مكرونة ومعلبات طازجة مطابقة لأعلى معايير سلامة الغذاء.',
                'image' => 'https://images.unsplash.com/photo-1542838132-92c53300491e?auto=format&fit=crop&w=800&q=80',
                'display_order' => 1,
                'is_active' => true,
            ],
            [
                'name_ar' => 'مشروبات',
                'name_en' => 'Beverages',
                'slug' => 'beverages',
                'description_ar' => 'تشكيلة متكاملة من المياه المعدنية النقية، العصائر الطبيعية، الشاي والقهوة، والمشروبات الغازية والمنعشة لكافة الأوقات.',
                'image' => 'https://images.unsplash.com/photo-1551024709-8f23befc6f87?auto=format&fit=crop&w=800&q=80',
                'display_order' => 2,
                'is_active' => true,
            ],
            [
                'name_ar' => 'منظفات',
                'name_en' => 'Detergents & Cleaning',
                'slug' => 'detergents-cleaning',
                'description_ar' => 'مساحيق غسيل الملابس، منظفات الأسطح والأرضيات، سوائل غسيل الأطباق، والمطهرات والمعقمات لبيئة منزلية صحية ونقية.',
                'image' => 'https://images.unsplash.com/photo-1585421514284-efb74c2b69ba?auto=format&fit=crop&w=800&q=80',
                'display_order' => 3,
                'is_active' => true,
            ],
            [
                'name_ar' => 'كماليات',
                'name_en' => 'Sundries & Household',
                'slug' => 'household-essentials',
                'description_ar' => 'مستلزمات المائدة والمنزل، مناديل ورقية فائقة النعومة، أكياس حفظ وقصدير، بلاستيكيات، وكماليات استهلاكية يومية.',
                'image' => 'https://images.unsplash.com/photo-1583947215259-38e31be8751f?auto=format&fit=crop&w=800&q=80',
                'display_order' => 4,
                'is_active' => true,
            ],
        ];

        $categories = [];
        foreach ($categoriesData as $c) {
            $categories[$c['slug']] = Category::create($c);
        }

        // Subcategories for rich browsing
        $subCategories = [
            ['parent_slug' => 'food-products', 'name_ar' => 'أرز وزيوت وسمن', 'slug' => 'rice-and-oils', 'image' => 'https://images.unsplash.com/photo-1586201375761-83865001e31c?auto=format&fit=crop&w=600&q=80', 'display_order' => 1],
            ['parent_slug' => 'food-products', 'name_ar' => 'معلبات وبقوليات', 'slug' => 'canned-and-legumes', 'image' => 'https://images.unsplash.com/photo-1534483509719-3feaee7c30da?auto=format&fit=crop&w=600&q=80', 'display_order' => 2],
            ['parent_slug' => 'food-products', 'name_ar' => 'بهارات وتوابل', 'slug' => 'spices-seasonings', 'image' => 'https://images.unsplash.com/photo-1596040033229-a9821ebd058d?auto=format&fit=crop&w=600&q=80', 'display_order' => 3],
            ['parent_slug' => 'food-products', 'name_ar' => 'مكرونة وصلصات', 'slug' => 'pasta-and-sauces', 'image' => 'https://images.unsplash.com/photo-1621996346565-e3d5d6281699?auto=format&fit=crop&w=600&q=80', 'display_order' => 4],

            ['parent_slug' => 'beverages', 'name_ar' => 'مياه شرب معبأة', 'slug' => 'bottled-water', 'image' => 'https://images.unsplash.com/photo-1548839140-29a749e1bc4e?auto=format&fit=crop&w=600&q=80', 'display_order' => 1],
            ['parent_slug' => 'beverages', 'name_ar' => 'عصائر طبيعية', 'slug' => 'natural-juices', 'image' => 'https://images.unsplash.com/photo-1613478223719-2ab802602423?auto=format&fit=crop&w=600&q=80', 'display_order' => 2],
            ['parent_slug' => 'beverages', 'name_ar' => 'شاي وقهوة', 'slug' => 'tea-and-coffee', 'image' => 'https://images.unsplash.com/photo-1514432324607-a09d9b4aefdd?auto=format&fit=crop&w=600&q=80', 'display_order' => 3],
            ['parent_slug' => 'beverages', 'name_ar' => 'مشروبات غازية', 'slug' => 'soft-drinks', 'image' => 'https://images.unsplash.com/photo-1622483767028-3f66f32aef97?auto=format&fit=crop&w=600&q=80', 'display_order' => 4],

            ['parent_slug' => 'detergents-cleaning', 'name_ar' => 'مساحيق غسيل الملابس', 'slug' => 'laundry-detergents', 'image' => 'https://images.unsplash.com/photo-1585421514284-efb74c2b69ba?auto=format&fit=crop&w=600&q=80', 'display_order' => 1],
            ['parent_slug' => 'detergents-cleaning', 'name_ar' => 'منظفات أرضيات وأسطح', 'slug' => 'floor-surface-cleaners', 'image' => 'https://images.unsplash.com/photo-1585421514738-01798e348b17?auto=format&fit=crop&w=600&q=80', 'display_order' => 2],
            ['parent_slug' => 'detergents-cleaning', 'name_ar' => 'سائل غسيل الأطباق', 'slug' => 'dishwashing-liquid', 'image' => 'https://images.unsplash.com/photo-1607613009820-a29f7bb81c04?auto=format&fit=crop&w=600&q=80', 'display_order' => 3],

            ['parent_slug' => 'household-essentials', 'name_ar' => 'مناديل ورقية وسفريات', 'slug' => 'tissues-disposables', 'image' => 'https://images.unsplash.com/photo-1583947215259-38e31be8751f?auto=format&fit=crop&w=600&q=80', 'display_order' => 1],
            ['parent_slug' => 'household-essentials', 'name_ar' => 'قصدير وأكياس حفظ', 'slug' => 'foil-storage-bags', 'image' => 'https://images.unsplash.com/photo-1605371924599-2d0365da1ae0?auto=format&fit=crop&w=600&q=80', 'display_order' => 2],
        ];

        foreach ($subCategories as $sub) {
            $parent = $categories[$sub['parent_slug']] ?? null;
            if ($parent) {
                Category::create([
                    'parent_id' => $parent->id,
                    'name_ar' => $sub['name_ar'],
                    'slug' => $sub['slug'],
                    'image' => $sub['image'],
                    'display_order' => $sub['display_order'],
                    'is_active' => true,
                ]);
            }
        }

        // 3. Realistic Products with High-Quality Grocery Photography
        $productsData = [
            // --- مواد غذائية ---
            [
                'category_slug' => 'food-products',
                'name_ar' => 'أرز بسمتي هندي سيلا طويل الحبة فاخر - كيس 5 كجم',
                'name_en' => 'Premium Long Grain Indian Basmati Rice 5kg',
                'slug' => 'premium-basmati-rice-5kg',
                'sku' => 'ZN-FOOD-001',
                'regular_price' => 48.00,
                'sale_price' => 41.50,
                'stock_quantity' => 120,
                'size' => '5 كجم',
                'weight' => '5000g',
                'fragrance_top' => 'نكهة أرز غنية وحبة ناصعة البياض',
                'fragrance_heart' => 'منتج معبأ وفق أعلى مواصفات سلامة الغذاء',
                'fragrance_base' => 'المملكة العربية السعودية / استيراد الهند',
                'description_ar' => "أرز بسمتي هندي سيلا فاخر منتقى بعناية من أجود المحاصيل الزراعية.\nيمتاز بالحبة الطويلة التي تتضاعف عند الطهي دون تكسر، مع رائحة زكية وطعم أصيل يليق بأشهى أطباق الكبسة والبرياني والمندي السعودي.\nمطابق لاشتراطات الهيئة العامة للغذاء والدواء.",
                'is_featured' => true,
                'images' => [
                    'https://images.unsplash.com/photo-1586201375761-83865001e31c?auto=format&fit=crop&w=800&q=80',
                    'https://images.unsplash.com/photo-1542838132-92c53300491e?auto=format&fit=crop&w=800&q=80',
                ],
            ],
            [
                'category_slug' => 'food-products',
                'name_ar' => 'زيت زيتون بكر ممتاز طبيعي معصور على البارد - 1 لتر',
                'name_en' => 'Extra Virgin Cold Pressed Olive Oil 1L',
                'slug' => 'extra-virgin-olive-oil-1l',
                'sku' => 'ZN-FOOD-002',
                'regular_price' => 38.00,
                'sale_price' => 32.00,
                'stock_quantity' => 85,
                'size' => '1 لتر',
                'weight' => '1000ml',
                'fragrance_top' => 'حموضة أقل من 0.8% وعصرة أولى',
                'fragrance_heart' => 'غني بمضادات الأكسدة والدهون الصحية',
                'fragrance_base' => 'معصور ومعبأ بأحدث المعايير الصحية',
                'description_ar' => "زيت زيتون بكر ممتاز نقي 100% مستخلص بالعصرة الأولى على البارد للحفاظ على كامل العناصر الغذائية والنكهة الفريدة.\nمثالي لإعداد السلطات والوجبات الصحية والطهي الخفيف.",
                'is_featured' => true,
                'images' => [
                    'https://images.unsplash.com/photo-1474979266404-7eaacbcd87c5?auto=format&fit=crop&w=800&q=80',
                ],
            ],
            [
                'category_slug' => 'food-products',
                'name_ar' => 'زيت ذرة نقي فائق الجودة للقلي والطهي - 1.5 لتر',
                'name_en' => 'Pure Corn Cooking Oil 1.5L',
                'slug' => 'pure-corn-oil-1-5l',
                'sku' => 'ZN-FOOD-003',
                'regular_price' => 22.00,
                'sale_price' => 18.50,
                'stock_quantity' => 150,
                'size' => '1.5 لتر',
                'weight' => '1500ml',
                'fragrance_top' => 'خفيف وخالٍ من الكوليسترول',
                'fragrance_heart' => 'مثالي لكافة أنواع القلي والطبخ',
                'fragrance_base' => 'جودة مضمونة وصحية',
                'description_ar' => "زيت ذرة مكرر ونقي 100% يمنح مأكولاتك قرمشة لذيذة وخفة لا تضاهى دون ترك أي أثر دهني ثقيل.",
                'is_featured' => false,
                'images' => [
                    'https://images.unsplash.com/photo-1542838132-92c53300491e?auto=format&fit=crop&w=800&q=80',
                ],
            ],
            [
                'category_slug' => 'food-products',
                'name_ar' => 'معجون طماطم طبيعي مركز 100% - عبوة اقتصادية (شدة 8 حبات)',
                'name_en' => 'Natural Tomato Paste Pack of 8',
                'slug' => 'natural-tomato-paste-8pack',
                'sku' => 'ZN-FOOD-004',
                'regular_price' => 16.00,
                'sale_price' => 13.50,
                'stock_quantity' => 200,
                'size' => '8 × 135 جم',
                'weight' => '1080g',
                'fragrance_top' => 'طماطم طازجة حمراء غنية',
                'fragrance_heart' => 'خالٍ تماماً من المواد الحافظة والألوان الصناعية',
                'fragrance_base' => 'قوام كثيف ولون غني للمرق والصلصات',
                'description_ar' => "صلصة طماطم طبيعية مركزة مصنوعة من أفضل حبات الطماطم الناضجة لتضفي طعماً غنياً ولوناً أحمر زاهياً لكافة الأطباق والمكرونات واليخنات.",
                'is_featured' => true,
                'images' => [
                    'https://images.unsplash.com/photo-1534483509719-3feaee7c30da?auto=format&fit=crop&w=800&q=80',
                ],
            ],
            [
                'category_slug' => 'food-products',
                'name_ar' => 'تونة لحم أبيض خفيف بالزيت النباتي - باقة 3 علب (185 جم)',
                'name_en' => 'Light White Meat Tuna 3-Pack 185g',
                'slug' => 'light-white-meat-tuna-3pack',
                'sku' => 'ZN-FOOD-005',
                'regular_price' => 24.00,
                'sale_price' => 19.50,
                'stock_quantity' => 90,
                'size' => '3 × 185 جم',
                'weight' => '555g',
                'fragrance_top' => 'غنية بأوميغا 3 والبروتين الصافي',
                'fragrance_heart' => 'لحم تونة متماسك ونكهة شهية',
                'fragrance_base' => 'تغليف آمن وسهل الفتح',
                'description_ar' => "تونة قطع لحم أبيض خفيف في زيت نباتي نقي، مصدر ممتاز للبروتين الطبيعي والوجبات السريعة الصحية والساندويتشات.",
                'is_featured' => false,
                'images' => [
                    'https://images.unsplash.com/photo-1534483509719-3feaee7c30da?auto=format&fit=crop&w=800&q=80',
                ],
            ],
            [
                'category_slug' => 'food-products',
                'name_ar' => 'خلطة بهارات كبسة سعودية فاخرة مشكلة - عبوة 250 جم',
                'name_en' => 'Traditional Saudi Kabsa Spices Blend 250g',
                'slug' => 'saudi-kabsa-spices-blend-250g',
                'sku' => 'ZN-FOOD-006',
                'regular_price' => 18.00,
                'sale_price' => 14.50,
                'stock_quantity' => 110,
                'size' => '250 جم',
                'weight' => '250g',
                'fragrance_top' => 'توليفة لومي، هيل، قرفة، وزعفران',
                'fragrance_heart' => 'بهارات مطحونة طازجة بدون أي إضافات',
                'fragrance_base' => 'نكهة مكة والضيافة السعودية الأصيلة',
                'description_ar' => "توليفة بهارات الكبسة الأصلية المحضرة من أرقى حبوب البهارات النقية بتوازن مدروس يمنح الرز ولحم الكبسة رائحة تأسر القلوب ونكهة لا تُنسى.",
                'is_featured' => true,
                'images' => [
                    'https://images.unsplash.com/photo-1596040033229-a9821ebd058d?auto=format&fit=crop&w=800&q=80',
                ],
            ],

            // --- مشروبات ---
            [
                'category_slug' => 'beverages',
                'name_ar' => 'كرتون مياه شرب نقية معبأة - 40 قارورة × 330 مل',
                'name_en' => 'Bottled Pure Drinking Water Carton 40x330ml',
                'slug' => 'pure-drinking-water-carton-40x330ml',
                'sku' => 'ZN-BEV-001',
                'regular_price' => 18.50,
                'sale_price' => 15.00,
                'stock_quantity' => 300,
                'size' => '40 × 330 مل',
                'weight' => '13.2kg',
                'fragrance_top' => 'مياه جوفية طبيعية متوازنة الأملاح',
                'fragrance_heart' => 'معقمة ومفلترة بأحدث تقنيات الأوزون',
                'fragrance_base' => 'ترطيب منعش وصحي لجميع أفراد الأسرة',
                'description_ar' => "كرتون مياه شرب نقية معبأة بحجم مناسب للرحلات والمنازل والمدارس.\nمستخرجة ومعالجة وفق أدق المواصفات السعودية لسلامة المياه والغذاء، توفر لك انتعاشاً يومياً بتركيبة أملاح متوازنة وصحية.",
                'is_featured' => true,
                'images' => [
                    'https://images.unsplash.com/photo-1548839140-29a749e1bc4e?auto=format&fit=crop&w=800&q=80',
                ],
            ],
            [
                'category_slug' => 'beverages',
                'name_ar' => 'عصير برتقال طبيعي 100% طازج بدون سكر مضاف - 1 لتر',
                'name_en' => 'Fresh Natural Orange Juice No Added Sugar 1L',
                'slug' => 'natural-orange-juice-1l',
                'sku' => 'ZN-BEV-002',
                'regular_price' => 11.00,
                'sale_price' => 9.00,
                'stock_quantity' => 95,
                'size' => '1 لتر',
                'weight' => '1000ml',
                'fragrance_top' => 'غني بفيتامين C الطبيعي 100%',
                'fragrance_heart' => 'عصير برتقال نقي وخالٍ من الإضافات',
                'fragrance_base' => 'محفوظ مبرداً بأعلى معايير الجودة',
                'description_ar' => "عصير برتقال طبيعي معصور من أفضل حبات البرتقال المشمسة، يمنحك الطاقة والانتعاش والحيوية مع كل رشفة بدون أي سكر مضاف أو مواد حافظة.",
                'is_featured' => true,
                'images' => [
                    'https://images.unsplash.com/photo-1613478223719-2ab802602423?auto=format&fit=crop&w=800&q=80',
                ],
            ],
            [
                'category_slug' => 'beverages',
                'name_ar' => 'قهوة عربية سعودية شقراء بالهيل والزعفران الفاخر - 500 جم',
                'name_en' => 'Saudi Blonde Arabic Coffee with Cardamom & Saffron 500g',
                'slug' => 'saudi-arabic-coffee-cardamom-saffron-500g',
                'sku' => 'ZN-BEV-003',
                'regular_price' => 45.00,
                'sale_price' => 38.00,
                'stock_quantity' => 70,
                'size' => '500 جم',
                'weight' => '500g',
                'fragrance_top' => 'بن خولاني جبلي محمص بدرجة شقراء متقنة',
                'fragrance_heart' => 'هيل أخضر نفاذ مع خيوط الزعفران الطبيعي',
                'fragrance_base' => 'رمز كرم الضيافة وأصالة الكيف السعودي',
                'description_ar' => "قهوة عربية سعودية فاخرة مجهزة بخلطة الكيف الموزونة بالهيل الهندي الفاخر وشعيرات الزعفران الطبيعي لتستمتع بدلة قهوة ذهبية اللون تفوح في المجالس.",
                'is_featured' => true,
                'images' => [
                    'https://images.unsplash.com/photo-1514432324607-a09d9b4aefdd?auto=format&fit=crop&w=800&q=80',
                ],
            ],
            [
                'category_slug' => 'beverages',
                'name_ar' => 'شاي أحمر سيلاني فاخر فرط نخب أول - عبوة معدنية 400 جم',
                'name_en' => 'Ceylon Pure Black Loose Leaf Tea 400g',
                'slug' => 'ceylon-pure-black-tea-400g',
                'sku' => 'ZN-BEV-004',
                'regular_price' => 26.00,
                'sale_price' => 21.00,
                'stock_quantity' => 80,
                'size' => '400 جم',
                'weight' => '400g',
                'fragrance_top' => 'أوراق شاي كاملة مقطوفة من مرتفعات سيلان',
                'fragrance_heart' => 'لون عنبري زاهٍ ونكهة متوازنة نقية',
                'fragrance_base' => 'علبة معدنية محكمة الإغلاق لحفظ الرائحة',
                'description_ar' => "شاي سيلاني فاخر ذو أوراق كاملة عالية الجودة، يمنحك كوب شاي أصيل بلون عنبري جذاب ومذاق سلس يلائم جلسات الصباح والمساء.",
                'is_featured' => false,
                'images' => [
                    'https://images.unsplash.com/photo-1576092768241-dec231879fc3?auto=format&fit=crop&w=800&q=80',
                ],
            ],
            [
                'category_slug' => 'beverages',
                'name_ar' => 'كرتون حليب طويل الأجل كامل الدسم - 12 عبوة × 1 لتر',
                'name_en' => 'Full Cream Long Life Milk Carton 12x1L',
                'slug' => 'uht-milk-carton-12x1l',
                'sku' => 'ZN-BEV-005',
                'regular_price' => 56.00,
                'sale_price' => 49.50,
                'stock_quantity' => 140,
                'size' => '12 × 1 لتر',
                'weight' => '12kg',
                'fragrance_top' => 'حليب بقر طازج 100% معالج بالحرارة العالية',
                'fragrance_heart' => 'مدعم بفيتامين د والكالسيوم لتقوية العظام',
                'fragrance_base' => 'صلاحية ممتدة وتخزين مريح',
                'description_ar' => "كرتون حليب كامل الدسم مبستر طويل الأجل، طعم طبيعي غني بالقيمة الغذائية المناسبة للأطفال والبالغين وإعداد المشروبات الساخنة والحلويات.",
                'is_featured' => true,
                'images' => [
                    'https://images.unsplash.com/photo-1550583724-b2692b85b150?auto=format&fit=crop&w=800&q=80',
                ],
            ],

            // --- منظفات ---
            [
                'category_slug' => 'detergents-cleaning',
                'name_ar' => 'مسحوق غسيل الملابس المركز للغسالات الأوتوماتيكية - كيس 5 كجم',
                'name_en' => 'Automatic Laundry Detergent Powder 5kg',
                'slug' => 'automatic-laundry-powder-5kg',
                'sku' => 'ZN-CLN-001',
                'regular_price' => 52.00,
                'sale_price' => 43.50,
                'stock_quantity' => 90,
                'size' => '5 كجم',
                'weight' => '5000g',
                'fragrance_top' => 'تركيبة إنزيمية فعالة ضد أصعب البقع',
                'fragrance_heart' => 'رائحة نسيم الصباح المنعشة التي تدوم طويلاً',
                'fragrance_base' => 'يحافظ على نصاعة الأبيض وبريق الألوان',
                'description_ar' => "مسحوق غسيل متطور مخصص للغسالات الأوتوماتيكية، يزيل أصعب البقع المستعصية من الغسلة الأولى مع حماية أنسجة الأقمشة وترك رائحة منعشة تدوم لأيام.",
                'is_featured' => true,
                'images' => [
                    'https://images.unsplash.com/photo-1585421514284-efb74c2b69ba?auto=format&fit=crop&w=800&q=80',
                ],
            ],
            [
                'category_slug' => 'detergents-cleaning',
                'name_ar' => 'سائل غسيل الأطباق بالليمون الفعال لمكافحة الدهون - 1.5 لتر',
                'name_en' => 'Dishwashing Liquid Lemon Power 1.5L',
                'slug' => 'dishwashing-liquid-lemon-1-5l',
                'sku' => 'ZN-CLN-002',
                'regular_price' => 15.00,
                'sale_price' => 11.50,
                'stock_quantity' => 180,
                'size' => '1.5 لتر',
                'weight' => '1500ml',
                'fragrance_top' => 'قوة مضاعفة لتفتيت الدهون والشحوم',
                'fragrance_heart' => 'رغوة كثيفة واقتصادية تدوم طويلاً',
                'fragrance_base' => 'لطيف على اليدين برائحة الليمون الطبيعي',
                'description_ar' => "سائل صابون للأطباق عالي التركيز يزيل الدهون المحروقة وبقايا الطعام بسهولة فائقة ويترك الأواني والصحون والزجاج لامعاً بدون أي أثر.",
                'is_featured' => true,
                'images' => [
                    'https://images.unsplash.com/photo-1607613009820-a29f7bb81c04?auto=format&fit=crop&w=800&q=80',
                ],
            ],
            [
                'category_slug' => 'detergents-cleaning',
                'name_ar' => 'مطهر ومعقم الأرضيات والأسطح برائحة اللافندر الفواحة - 3 لتر',
                'name_en' => 'Surface & Floor Disinfectant Lavender 3L',
                'slug' => 'surface-floor-disinfectant-lavender-3l',
                'sku' => 'ZN-CLN-003',
                'regular_price' => 28.00,
                'sale_price' => 22.00,
                'stock_quantity' => 110,
                'size' => '3 لتر',
                'weight' => '3000ml',
                'fragrance_top' => 'يقضي على 99.9% من الجراثيم والبكتيريا',
                'fragrance_heart' => 'يمنح الأرضيات لمعاناً كريستالياً براقاً',
                'fragrance_base' => 'خلاصة اللافندر المنعشة للملابس',
                'description_ar' => "منظف ومطهر أرضيات متعدد الأسطح بتركيبة فعالة توفر حماية صحية متكاملة لبيتك وتترك رائحة زكية ومنعشة تملأ أرجاء المنزل طوال اليوم.",
                'is_featured' => true,
                'images' => [
                    'https://images.unsplash.com/photo-1585421514738-01798e348b17?auto=format&fit=crop&w=800&q=80',
                ],
            ],
            [
                'category_slug' => 'detergents-cleaning',
                'name_ar' => 'مبيض ومعقم أقمشة وأسطح متعدد الاستخدامات - جالون 3.78 لتر',
                'name_en' => 'Multi-Purpose Bleach & Disinfectant 3.78L',
                'slug' => 'multi-purpose-bleach-3-78l',
                'sku' => 'ZN-CLN-004',
                'regular_price' => 17.00,
                'sale_price' => 14.00,
                'stock_quantity' => 130,
                'size' => '3.78 لتر',
                'weight' => '3780ml',
                'fragrance_top' => 'بياض ناصع وقوة تعقيم مثبتة',
                'fragrance_heart' => 'مطهر قوي للمراحيض والأحواض والمطابخ',
                'fragrance_base' => 'عبوة جالون اقتصادية متينة',
                'description_ar' => "مبيض كلور متعدد الاستخدامات يزيل البقع الصعبة عن الملابس البيضاء ويضمن تعقيماً عميقاً للأسطح المنزلية والحمامات والمطابخ.",
                'is_featured' => false,
                'images' => [
                    'https://images.unsplash.com/photo-1585421514284-efb74c2b69ba?auto=format&fit=crop&w=800&q=80',
                ],
            ],

            // --- كماليات ---
            [
                'category_slug' => 'household-essentials',
                'name_ar' => 'مناديل وجه ناعمة ثلاثية الطبقات - عبوة توفيرية 10 علب × 200 منديل',
                'name_en' => 'Soft Facial Tissues 3-Ply 10 Packs x 200 Sheets',
                'slug' => 'soft-facial-tissues-10pack-200sheets',
                'sku' => 'ZN-ACC-001',
                'regular_price' => 28.00,
                'sale_price' => 22.50,
                'stock_quantity' => 220,
                'size' => '10 علب × 200 منديل',
                'weight' => '2000g',
                'fragrance_top' => 'قطن طبيعي نقي 100% شديد النعومة',
                'fragrance_heart' => 'ثلاث طبقات ماصة ومتينة لا تتمزق بسهولة',
                'fragrance_base' => 'مناسبة للاستخدام اليومي للبشرة الحساسة',
                'description_ar' => "مناديل ورقية فائقة النعومة من لب الشجر الطبيعي النقي، توفر لك ولعائلتك عناية لطيفة وامتصاصاً ممتازاً في كل الأوقات بعبوة توفيرية ممتازة.",
                'is_featured' => true,
                'images' => [
                    'https://images.unsplash.com/photo-1583947215259-38e31be8751f?auto=format&fit=crop&w=800&q=80',
                ],
            ],
            [
                'category_slug' => 'household-essentials',
                'name_ar' => 'قصدير ألومنيوم فائق القوة للطهي والتغليف - 45 سم × 100 متر',
                'name_en' => 'Heavy Duty Aluminium Cooking Foil 45cm x 100m',
                'slug' => 'heavy-duty-aluminium-foil-45cm-100m',
                'sku' => 'ZN-ACC-002',
                'regular_price' => 34.00,
                'sale_price' => 27.50,
                'stock_quantity' => 100,
                'size' => '45 سم × 100 م',
                'weight' => '1200g',
                'fragrance_top' => 'سماكة عالية مقاومة للتمزق والحرارة العالية',
                'fragrance_heart' => 'مثالي للشواء، تغليف الصواني، وحفظ الأطعمة',
                'fragrance_base' => 'صحي وآمن للتلامس المباشر مع الأغذية',
                'description_ar' => "رول قصدير ألومنيوم شديد التحمل بسماكة ممتازة يحمي اللحوم والأطعمة أثناء الخبز والشواء ويحافظ على الرطوبة والنكهة الطازجة.",
                'is_featured' => true,
                'images' => [
                    'https://images.unsplash.com/photo-1605371924599-2d0365da1ae0?auto=format&fit=crop&w=800&q=80',
                ],
            ],
            [
                'category_slug' => 'household-essentials',
                'name_ar' => 'رول مناديل مطبخ ماصة متعددة الاستخدامات - شدة 6 رول جامبو',
                'name_en' => 'Jumbo Kitchen Towels 6-Roll Pack',
                'slug' => 'jumbo-kitchen-towels-6roll',
                'sku' => 'ZN-ACC-003',
                'regular_price' => 20.00,
                'sale_price' => 16.00,
                'stock_quantity' => 160,
                'size' => '6 رول جامبو',
                'weight' => '1100g',
                'fragrance_top' => 'قدرة امتصاص فائقة للزيوت والسوائل',
                'fragrance_heart' => 'ملمس متماسك لا يترك وبر على الزجاج والصحون',
                'fragrance_base' => 'اقتصادي وعملي لجميع مهام المطبخ',
                'description_ar' => "مناديل مطبخ فائقة الامتصاص مخصصة لتجفيف الأطعمة وتصفية الزيوت وتنظيف أسطح الطهي والزجاج بكفاءة عالية وبدون ترك شوائب.",
                'is_featured' => false,
                'images' => [
                    'https://images.unsplash.com/photo-1583947215259-38e31be8751f?auto=format&fit=crop&w=800&q=80',
                ],
            ],
            [
                'category_slug' => 'household-essentials',
                'name_ar' => 'أكياس نفايات متينة برباط سحب محكم سعة 50 لتر - شدة 30 كيس',
                'name_en' => 'Heavy Duty Drawstring Trash Bags 50L 30ct',
                'slug' => 'heavy-duty-trash-bags-50l-30ct',
                'sku' => 'ZN-ACC-004',
                'regular_price' => 16.00,
                'sale_price' => 12.50,
                'stock_quantity' => 170,
                'size' => '30 كيس × 50 لتر',
                'weight' => '800g',
                'fragrance_top' => 'بلاستيك مرن عالي الكثافة مانع للتسريب',
                'fragrance_heart' => 'رباط سحب سهل للإغلاق والحمل المحكم',
                'fragrance_base' => 'رائحة خفيفة مانعة للروائح غير المرغوبة',
                'description_ar' => "أكياس نفايات منزلية قوية التحمل تمنع تسرب السوائل ومزودة بشريط سحب علوي لسهولة الإغلاق والحمل دون ملامسة المحتويات.",
                'is_featured' => false,
                'images' => [
                    'https://images.unsplash.com/photo-1605371924599-2d0365da1ae0?auto=format&fit=crop&w=800&q=80',
                ],
            ],
        ];

        foreach ($productsData as $pData) {
            $cat = $categories[$pData['category_slug']] ?? null;
            if (!$cat) continue;

            $images = $pData['images'] ?? [];
            unset($pData['images'], $pData['category_slug']);

            $pData['category_id'] = $cat->id;
            $pData['is_active'] = true;

            $product = Product::create($pData);

            foreach ($images as $index => $img) {
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $img,
                    'is_primary' => ($index === 0),
                    'sort_order' => $index,
                ]);
            }
        }

        // 4. Homepage Promotional Banners matching Zad Nex Groceries & Essentials
        $bannersData = [
            [
                'placement' => 'hero',
                'title_ar' => 'زاد نيكس — الجودة والتوفير في كل بيت',
                'subtitle_ar' => 'تسوق كافة احتياجات أسرتك من أجود المواد الغذائية، المشروبات، المنظفات والكماليات بأفضل الأسعار مع توصيل سريع في مكة وكافة أنحاء المملكة.',
                'badge_ar' => 'متجر زاد نيكس المعتمد',
                'button_text_ar' => 'تسوق العروض التوفيرية',
                'button_url' => '/products',
                'image_url' => 'https://images.unsplash.com/photo-1542838132-92c53300491e?auto=format&fit=crop&w=1600&q=85',
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'placement' => 'hero',
                'title_ar' => 'سلامة غذائية معتمدة وجودة لا تضاهى',
                'subtitle_ar' => 'منشأة مرخصة وموثقة رسمياً بالسجل التجاري وشهادة سلامة الغذاء (محل تجزئة / بقالات). نوفر لكم منتجات طازجة ومضمونة 100%.',
                'badge_ar' => 'سجل تجاري 7055435114',
                'button_text_ar' => 'تعرف على من نحن',
                'button_url' => '/about',
                'image_url' => 'https://images.unsplash.com/photo-1578916171728-46686eac8d58?auto=format&fit=crop&w=1600&q=85',
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'placement' => 'secondary',
                'title_ar' => 'كراتين المياه والمشروبات المنعشة',
                'subtitle_ar' => 'أسعار جملة تنافسية لكراتين المياه والعصائر الطبيعية لبيتك ومناسباتك.',
                'badge_ar' => 'عروض المشروبات',
                'button_text_ar' => 'عرض المشروبات',
                'button_url' => '/category/beverages',
                'image_url' => 'https://images.unsplash.com/photo-1551024709-8f23befc6f87?auto=format&fit=crop&w=1000&q=80',
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'placement' => 'secondary',
                'title_ar' => 'باقات المنظفات والتعقيم المنزلي',
                'subtitle_ar' => 'مساحيق غسيل ومطهرات برائحة فواحة وحماية متكاملة من الجراثيم.',
                'badge_ar' => 'عروض النظافة',
                'button_text_ar' => 'عرض المنظفات',
                'button_url' => '/category/detergents-cleaning',
                'image_url' => 'https://images.unsplash.com/photo-1585421514284-efb74c2b69ba?auto=format&fit=crop&w=1000&q=80',
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'placement' => 'offers',
                'title_ar' => 'سلة المواد التموينية الأساسية',
                'subtitle_ar' => 'وفر حتى 25% على الأرز والزيوت والمعلبات والبهارات الطازجة.',
                'badge_ar' => 'توفير التموين',
                'button_text_ar' => 'تسوق السلة التموينية',
                'button_url' => '/category/food-products',
                'image_url' => 'https://images.unsplash.com/photo-1586201375761-83865001e31c?auto=format&fit=crop&w=800&q=80',
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'placement' => 'offers',
                'title_ar' => 'عروض كراتين المياه العائلية',
                'subtitle_ar' => 'مياه شرب نقية معقمة بأفضل سعر كرتون مع توصيل للمنزل.',
                'badge_ar' => 'سعر الجملة',
                'button_text_ar' => 'اطلب المياه الآن',
                'button_url' => '/category/beverages',
                'image_url' => 'https://images.unsplash.com/photo-1548839140-29a749e1bc4e?auto=format&fit=crop&w=800&q=80',
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'placement' => 'offers',
                'title_ar' => 'كماليات ومستلزمات السفرة والمنزل',
                'subtitle_ar' => 'مناديل، قصدير، أكياس حفظ ومستلزمات استهلاكية عالية الجودة.',
                'badge_ar' => 'الكماليات المنزلية',
                'button_text_ar' => 'تصفح الكماليات',
                'button_url' => '/category/household-essentials',
                'image_url' => 'https://images.unsplash.com/photo-1583947215259-38e31be8751f?auto=format&fit=crop&w=800&q=80',
                'sort_order' => 3,
                'is_active' => true,
            ],
        ];

        foreach ($bannersData as $b) {
            HomepageBanner::create($b);
        }

        // 5. Shipping Zones (Makkah active for trial launch; other KSA regions ready to enable)
        $shippingZones = [
            ['city_name_ar' => 'مكة المكرمة (توصيل فوري محلي)', 'city_name_en' => 'Makkah Local', 'shipping_cost' => 12.00, 'free_shipping_threshold' => 150.00, 'estimated_days' => 'خلال ساعات / نفس اليوم', 'is_active' => true],
            ['city_name_ar' => 'جدة والطائف', 'city_name_en' => 'Jeddah & Taif', 'shipping_cost' => 18.00, 'free_shipping_threshold' => 199.00, 'estimated_days' => 'خلال 24-48 ساعة', 'is_active' => false],
            ['city_name_ar' => 'مدينة الرياض', 'city_name_en' => 'Riyadh', 'shipping_cost' => 22.00, 'free_shipping_threshold' => 249.00, 'estimated_days' => '2 - 3 أيام عمل', 'is_active' => false],
            ['city_name_ar' => 'المدينة المنورة وينبع', 'city_name_en' => 'Madinah & Yanbu', 'shipping_cost' => 22.00, 'free_shipping_threshold' => 249.00, 'estimated_days' => '2 - 3 أيام عمل', 'is_active' => false],
            ['city_name_ar' => 'المنطقة الشرقية (الدمام، الخبر، الأحساء)', 'city_name_en' => 'Eastern Province', 'shipping_cost' => 24.00, 'free_shipping_threshold' => 249.00, 'estimated_days' => '2 - 4 أيام عمل', 'is_active' => false],
            ['city_name_ar' => 'القصيم وحائل والحدود الشمالية', 'city_name_en' => 'Qassim & Northern', 'shipping_cost' => 25.00, 'free_shipping_threshold' => 299.00, 'estimated_days' => '3 - 5 أيام عمل', 'is_active' => false],
            ['city_name_ar' => 'عسير، جازان، نجران والباحة', 'city_name_en' => 'Southern Province', 'shipping_cost' => 25.00, 'free_shipping_threshold' => 299.00, 'estimated_days' => '3 - 5 أيام عمل', 'is_active' => false],
        ];

        foreach ($shippingZones as $zone) {
            ShippingZone::create($zone);
        }

        // 6. Coupons
        Coupon::create([
            'code' => 'ZADNEX10',
            'type' => 'percent',
            'value' => 10.00,
            'min_order_amount' => 100.00,
            'max_uses' => 1000,
            'used_count' => 0,
            'expires_at' => now()->addYear(),
            'is_active' => true,
        ]);

        Coupon::create([
            'code' => 'WELCOME',
            'type' => 'fixed',
            'value' => 15.00,
            'min_order_amount' => 80.00,
            'max_uses' => null,
            'used_count' => 0,
            'expires_at' => now()->addYear(),
            'is_active' => true,
        ]);

        // 7. Store Settings for Zad Nex Store
        $settings = [
            'store_name' => 'متجر زاد نيكس | ZADNEX STORE',
            'store_tagline' => 'متجر المواد الغذائية، المشروبات، المنظفات والكماليات — جودة وتوفير وسلامة غذائية موثقة',
            'contact_phone' => '+966 58 076 0890',
            'whatsapp_number' => '966580760890',
            'contact_email' => 'info@zadnexstore.com',
            'commercial_registry' => '7055435114',
            'tax_number' => '7055435114',
            'safety_certificate' => '101565890',
            'municipal_license' => '480526670333',
            'license_expiry' => '1449/06/08',
            'municipality' => 'أمانة العاصمة المقدسة - بلدية الحديبية',
            'detailed_activity' => 'تموينات ضمن محطة وقود (البقالات)',
            'isic_code' => '471131',
            'isic_activity' => 'البقالات ومحلات التجزئة',
            'address' => 'المملكة العربية السعودية، مكة المكرمة (العاصمة المقدسة)، حي أم الجود، شارع عبد الله بن أبي بكر الصديق',
            'domain' => 'https://www.zadnexstore.com/',
            'instagram' => 'https://www.instagram.com/zadnexstore',
            'snapchat' => 'https://www.snapchat.com/add/zadnexstore',
            'tiktok' => 'https://www.tiktok.com/@zadnexstore',
            'announcement_text' => 'مرحباً بكم في متجر زاد نيكس — عروض توفير المواد الغذائية والمشروبات والمنظفات — سجل تجاري موثق: 7055435114 — هاتف: 0580760890',
            'announcement_active' => '1',
            'free_shipping_threshold' => '199',
            'paymob_enabled' => '0',
            'paymob_mode' => 'mock',
            'tamara_enabled' => '0',
            'tabby_enabled' => '0',
            'whatsapp_order_enabled' => '1',
            'cod_enabled' => '1',
            'popup_ad_active' => '1',
            'popup_ad_title' => 'أهلاً بكم في متجر زاد نيكس!',
            'popup_ad_subtitle' => 'استخدم كود الخصم ZADNEX10 واحصل على خصم 10% على كافة طلبات المواد الغذائية والمنظفات والكماليات.',
            'popup_ad_coupon' => 'ZADNEX10',
            'popup_ad_badge' => 'كوبون الترحيب الحصري',
            'popup_ad_delay' => '2',
            'popup_ad_duration' => '6',
            'popup_ad_image' => 'https://images.unsplash.com/photo-1542838132-92c53300491e?auto=format&fit=crop&w=700&q=80',
            'meta_title' => 'متجر زاد نيكس | ZADNEX — مواد غذائية، مشروبات، منظفات، كماليات',
            'meta_description' => 'متجر زاد نيكس في مكة المكرمة — خيارك الأول لأجود المواد الغذائية، المشروبات، المنظفات ومستلزمات البيت بأفضل الأسعار وسلامة غذائية معتمدة.',
            'meta_keywords' => 'زاد نيكس, متجر زاد نيكس, مواد غذائية, مشروبات, منظفات, كماليات, بقالة مكة, أم الجود, توصيل تموين, ZADNEX, zadnexstore',
            'google_maps_iframe' => '<iframe src="https://www.google.com/maps/embed?pb=!1m14!1m12!1m3!1d316.6133110631134!2d39.70255475138687!3d21.419342605780784!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!5e1!3m2!1sar!2ssa!4v1795073236256!5m2!1sar!2ssa" width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="strict-origin-when-cross-origin"></iframe>',
        ];

        foreach ($settings as $k => $v) {
            StoreSetting::set($k, $v);
        }

        // 8. Flash Popup Promotional Ad
        \App\Models\FlashPopup::create([
            'title_ar' => 'أهلاً بكم في متجر زاد نيكس!',
            'subtitle_ar' => 'احصل على خصم 10% إضافي على كافة سلال المواد الغذائية، المشروبات، المنظفات، ومستلزمات البيت.',
            'badge_ar' => 'كوبون التوفير الحصري',
            'coupon_code' => 'ZADNEX10',
            'button_text_ar' => 'تسوق منتجات زاد نيكس',
            'button_url' => '/products',
            'image_url' => 'https://images.unsplash.com/photo-1542838132-92c53300491e?auto=format&fit=crop&w=700&q=80',
            'delay_seconds' => 3,
            'duration_seconds' => 6,
            'is_active' => true,
        ]);
    }
}
