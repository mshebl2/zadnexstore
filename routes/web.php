<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StorefrontController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CustomerDashboardController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ShippingController;
use App\Http\Middleware\EnsureAdmin;

/*
|--------------------------------------------------------------------------
| Web Routes — ZADNEX STORE Food & Grocery E-Commerce
|--------------------------------------------------------------------------
*/

// One-click Setup route for Hostinger / Shared Hosting deployment
Route::get('/setup-database', function (\Illuminate\Http\Request $request) {
    if ($request->query('secret') !== 'zadnex2026') {
        abort(403, 'Unauthorized setup access.');
    }

    // Optional remote git pull
    if ($request->query('action') === 'pull') {
        $pullOutput = shell_exec('git pull origin main 2>&1');
        \Illuminate\Support\Facades\Artisan::call('optimize:clear');
        return response("<h2>🔄 نتيجة تحديث Git على الخادم:</h2><pre>{$pullOutput}</pre><p><a href='/'>العودة للمتجر ➔</a></p>", 200, ['Content-Type' => 'text/html; charset=utf-8']);
    }

    // Optional admin password reset / sync
    if ($request->query('action') === 'reset-admin') {
        \App\Models\User::updateOrCreate(
            ['email' => 'info@zadnexstore.com'],
            [
                'name' => 'إدارة متجر زاد نيكس',
                'phone' => '+966580760890',
                'role' => 'admin',
                'password' => \Illuminate\Support\Facades\Hash::make('zadnex2026@admin'),
            ]
        );
        \App\Models\User::updateOrCreate(
            ['email' => 'admin@zadnexstore.com'],
            [
                'name' => 'مدير النظام',
                'phone' => '0580760890',
                'role' => 'admin',
                'password' => \Illuminate\Support\Facades\Hash::make('zadnex2026@admin'),
            ]
        );
        return response("<h2>✅ تم تأكيد وتعيين كلمة مرور الإدارة: zadnex2026@admin للحسابين info و admin بنجاح!</h2><p><a href='/login'>تسجيل الدخول ➔</a></p>", 200, ['Content-Type' => 'text/html; charset=utf-8']);
    }

    try {
        $envPath = base_path('.env');
        
        // Auto-create from .env.production if missing
        if (!file_exists($envPath) && file_exists(base_path('.env.production'))) {
            copy(base_path('.env.production'), $envPath);
        }

        // Always ensure .env has the new database credentials
        if (file_exists($envPath)) {
            $env = file_get_contents($envPath);
            $env = preg_replace('/DB_CONNECTION=.*/', 'DB_CONNECTION=mysql', $env);
            $env = preg_replace('/DB_HOST=.*/', 'DB_HOST=127.0.0.1', $env);
            $env = preg_replace('/DB_PORT=.*/', 'DB_PORT=3306', $env);
            $env = preg_replace('/DB_DATABASE=.*/', 'DB_DATABASE=u363963266_zadnexstore', $env);
            $env = preg_replace('/DB_USERNAME=.*/', 'DB_USERNAME=u363963266_zadnexstore', $env);
            $env = preg_replace('/DB_PASSWORD=.*/', 'DB_PASSWORD=Zadnexstore22', $env);
            $env = preg_replace('/SESSION_DRIVER=.*/', 'SESSION_DRIVER=file', $env);
            $env = preg_replace('/CACHE_STORE=.*/', 'CACHE_STORE=file', $env);
            $env = preg_replace('/QUEUE_CONNECTION=.*/', 'QUEUE_CONNECTION=sync', $env);
            file_put_contents($envPath, $env);
        }

        // Apply dynamically to runtime config
        config([
            'database.connections.mysql.host' => '127.0.0.1',
            'database.connections.mysql.database' => 'u363963266_zadnexstore',
            'database.connections.mysql.username' => 'u363963266_zadnexstore',
            'database.connections.mysql.password' => 'Zadnexstore22',
            'session.driver' => 'file',
            'cache.default' => 'file',
        ]);
        \Illuminate\Support\Facades\DB::purge('mysql');
        \Illuminate\Support\Facades\DB::reconnect('mysql');

        if (empty(config('app.key'))) {
            \Illuminate\Support\Facades\Artisan::call('key:generate', ['--force' => true]);
        }

        \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
        $migrateOutput = \Illuminate\Support\Facades\Artisan::output();

        \Illuminate\Support\Facades\Artisan::call('db:seed', ['--force' => true, '--class' => 'ZadnexDatabaseSeeder']);
        $seedOutput = \Illuminate\Support\Facades\Artisan::output();

        \Illuminate\Support\Facades\Artisan::call('storage:link');
        $storageOutput = \Illuminate\Support\Facades\Artisan::output();

        \Illuminate\Support\Facades\Artisan::call('optimize:clear');

        return response("<h2>✅ تم تهيئة قاعدة بيانات متجر زاد نيكس بنجاح!</h2><pre><strong>Migrations:</strong>\n{$migrateOutput}\n<strong>Seeder:</strong>\n{$seedOutput}\n<strong>Storage:</strong>\n{$storageOutput}</pre><p><a href='/'>انقر هنا للانتقال إلى المتجر ➔</a></p>", 200, ['Content-Type' => 'text/html; charset=utf-8']);
    } catch (\Throwable $e) {
        return response("<h2>❌ تفاصيل الخطأ:</h2><pre>{$e->getMessage()}\n\n{$e->getFile()}:{$e->getLine()}</pre><p>إذا كان الخطأ Access denied، تأكد من الضغط على زر Create في هوستنجر لإنشاء قاعدة البيانات.</p>", 200, ['Content-Type' => 'text/html; charset=utf-8']);
    }
});

// Diagnostics & Debug Route for Hostinger
Route::get('/debug', function (\Illuminate\Http\Request $request) {
    if ($request->query('secret') !== 'zadnex2026') {
        abort(403, 'Unauthorized access.');
    }

    $data = [];
    $data['php_version'] = PHP_VERSION;
    $data['env_exists'] = file_exists(base_path('.env'));
    $data['app_key_set'] = !empty(config('app.key'));
    $data['db_host'] = config('database.connections.mysql.host');
    $data['db_database'] = config('database.connections.mysql.database');

    try {
        \Illuminate\Support\Facades\DB::connection()->getPdo();
        $data['database_status'] = 'CONNECTED';
        $tables = \Illuminate\Support\Facades\Schema::getTableListing();
        $data['tables_count'] = count($tables);
        $data['tables'] = $tables;
    } catch (\Throwable $e) {
        $data['database_status'] = 'ERROR: ' . $e->getMessage();
    }

    $logFile = storage_path('logs/laravel.log');
    if (file_exists($logFile)) {
        $lines = file($logFile);
        $data['latest_log_errors'] = array_slice($lines, -25);
    } else {
        $data['latest_log_errors'] = 'No logs created yet.';
    }

    return response()->json($data, 200, [], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
});

// One-click Cache Clear for Hostinger
Route::get('/clear-cache', function (\Illuminate\Http\Request $request) {
    if ($request->query('secret') !== 'zadnex2026') {
        abort(403, 'Unauthorized access.');
    }

    \Illuminate\Support\Facades\Artisan::call('optimize:clear');
    return response("<h2>✅ تم تفريغ كافة أنواع الكاش بنجاح (Views, Routes, Config)!</h2><p><a href='/'>انقر هنا للعودة للمتجر ➔</a></p>", 200, ['Content-Type' => 'text/html; charset=utf-8']);
});

// --- Storefront ---
Route::get('/', [StorefrontController::class, 'home'])->name('home');
Route::get('/about', [StorefrontController::class, 'about'])->name('about');
Route::get('/products', [StorefrontController::class, 'products'])->name('products.index');
Route::get('/category/{slug}', [StorefrontController::class, 'category'])->name('category.show');
Route::get('/product/{slug}', [StorefrontController::class, 'product'])->name('product.show');
Route::get('/api/products/{id}/quick-view', [StorefrontController::class, 'quickView'])->name('products.quick-view');
Route::get('/api/search', [StorefrontController::class, 'liveSearch'])->name('api.search');

// Policies & Legal
Route::get('/contact', [StorefrontController::class, 'contact'])->name('contact');
Route::post('/contact', [StorefrontController::class, 'contactSubmit'])->name('contact.submit')->middleware('throttle:5,1');
Route::get('/policy/shipping', [StorefrontController::class, 'policyShipping'])->name('policy.shipping');
Route::get('/policy/returns', [StorefrontController::class, 'policyReturns'])->name('policy.returns');
Route::get('/policy/privacy', [StorefrontController::class, 'policyPrivacy'])->name('policy.privacy');
Route::get('/policy/terms', [StorefrontController::class, 'policyTerms'])->name('policy.terms');
Route::post('/newsletter', [StorefrontController::class, 'newsletterSubscribe'])->name('newsletter.subscribe')->middleware('throttle:5,1');

// SEO & Indexing (Dynamic Sitemap & Robots)
Route::get('/sitemap.xml', [\App\Http\Controllers\SeoController::class, 'sitemap'])->name('seo.sitemap');
Route::get('/robots.txt', [\App\Http\Controllers\SeoController::class, 'robots'])->name('seo.robots');

// --- Cart ---
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::get('/my-cart-redirect', fn() => redirect()->route('cart.index'))->name('cart');
Route::get('/api/cart', [CartController::class, 'apiGet'])->name('cart.api.get');
Route::post('/api/cart/add', [CartController::class, 'apiAdd'])->name('cart.api.add');
Route::post('/api/cart/update/{itemId}', [CartController::class, 'apiUpdate'])->name('cart.api.update');
Route::post('/api/cart/remove/{itemId}', [CartController::class, 'apiRemove'])->name('cart.api.remove');
Route::post('/cart/coupon/apply', [CartController::class, 'applyCoupon'])->name('cart.coupon.apply');
Route::post('/cart/coupon/remove', [CartController::class, 'removeCoupon'])->name('cart.coupon.remove');

// --- Checkout ---
Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
Route::post('/checkout', [CheckoutController::class, 'process'])->name('checkout.process')->middleware('throttle:10,1');
Route::get('/checkout/confirmation/{order_number}', [CheckoutController::class, 'confirmation'])->name('checkout.confirmation');

// --- Payment & Shipping Webhooks ---
Route::get('/payment/mock/{order_number}', [PaymentController::class, 'mockView'])->name('payment.mock.view');
Route::post('/payment/mock/{order_number}', [PaymentController::class, 'mockProcess'])->name('payment.mock.process');
Route::match(['get', 'post'], '/webhook/paymob', [PaymentController::class, 'paymobWebhook'])->name('webhook.paymob');
Route::post('/webhook/mapit', [ShippingController::class, 'mapitWebhook'])->name('webhook.mapit');

// --- Tamara BNPL Routes ---
Route::get('/tamara/success/{order_number}', [PaymentController::class, 'tamaraSuccess'])->name('payment.tamara.success');
Route::get('/tamara/failure/{order_number}', [PaymentController::class, 'tamaraFailure'])->name('payment.tamara.failure');
Route::get('/tamara/cancel/{order_number}', [PaymentController::class, 'tamaraCancel'])->name('payment.tamara.cancel');
Route::match(['get', 'post'], '/webhook/tamara', [PaymentController::class, 'tamaraWebhook'])->name('webhook.tamara');
Route::get('/tamara/mock/{order_number}', [PaymentController::class, 'tamaraMockView'])->name('payment.tamara.mock');
Route::post('/tamara/mock/process/{order_number}', [PaymentController::class, 'tamaraSuccess'])->name('payment.tamara.mock.process');

// --- Authentication ---
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit')->middleware('throttle:10,1');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.submit')->middleware('throttle:10,1');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/my-orders', [AuthController::class, 'orders'])->name('profile.orders')->middleware('auth');

// --- Customer Portal / Luxury Dashboard ---
Route::middleware('auth')->prefix('my-account')->name('customer.')->group(function () {
    Route::get('/', [CustomerDashboardController::class, 'index'])->name('dashboard');
    Route::get('/orders/{order_number}', [CustomerDashboardController::class, 'orderShow'])->name('orders.show');
    Route::post('/addresses', [CustomerDashboardController::class, 'addressStore'])->name('addresses.store');
    Route::delete('/addresses/{id}', [CustomerDashboardController::class, 'addressDestroy'])->name('addresses.destroy');
    Route::patch('/addresses/{id}/default', [CustomerDashboardController::class, 'addressSetDefault'])->name('addresses.default');
    Route::post('/payout-request', [CustomerDashboardController::class, 'payoutRequest'])->name('payout.request')->middleware('throttle:5,1');
    Route::post('/profile', [CustomerDashboardController::class, 'profileUpdate'])->name('profile.update');
});

// --- Admin Panel ---
Route::middleware(['auth', EnsureAdmin::class])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');

    // Products
    Route::get('/products', [AdminController::class, 'productsIndex'])->name('products.index');
    Route::get('/products/template/download', [AdminController::class, 'productsTemplate'])->name('products.template');
    Route::post('/products/import', [AdminController::class, 'productsImport'])->name('products.import');
    Route::get('/products/create', [AdminController::class, 'productsCreate'])->name('products.create');
    Route::post('/products', [AdminController::class, 'productsStore'])->name('products.store');
    Route::get('/products/{id}/edit', [AdminController::class, 'productsEdit'])->name('products.edit');
    Route::put('/products/{id}', [AdminController::class, 'productsUpdate'])->name('products.update');
    Route::delete('/products/{id}', [AdminController::class, 'productsDestroy'])->name('products.destroy');

    // Categories
    Route::get('/categories', [AdminController::class, 'categoriesIndex'])->name('categories.index');
    Route::post('/categories', [AdminController::class, 'categoriesStore'])->name('categories.store');
    Route::put('/categories/{id}', [AdminController::class, 'categoriesUpdate'])->name('categories.update');
    Route::delete('/categories/{id}', [AdminController::class, 'categoriesDestroy'])->name('categories.destroy');

    // Orders
    Route::get('/orders', [AdminController::class, 'ordersIndex'])->name('orders.index');
    Route::get('/orders/export', [AdminController::class, 'ordersExport'])->name('orders.export');
    Route::get('/orders/{id}', [AdminController::class, 'ordersShow'])->name('orders.show');
    Route::get('/orders/{id}/invoice', [AdminController::class, 'ordersInvoice'])->name('orders.invoice');
    Route::patch('/orders/{id}/status', [AdminController::class, 'ordersUpdateStatus'])->name('orders.status');
    Route::post('/orders/{id}/ship-mapit', [AdminController::class, 'ordersShipMapit'])->name('orders.ship.mapit');
    Route::delete('/orders/{id}', [AdminController::class, 'ordersDestroy'])->name('orders.destroy');

    // Coupons & Discounts
    Route::get('/coupons', [AdminController::class, 'couponsIndex'])->name('coupons.index');
    Route::post('/coupons', [AdminController::class, 'couponsStore'])->name('coupons.store');
    Route::delete('/coupons/{id}', [AdminController::class, 'couponsDestroy'])->name('coupons.destroy');

    // Homepage Banners & Section Management
    Route::get('/banners', [AdminController::class, 'bannersIndex'])->name('banners.index');
    Route::post('/banners', [AdminController::class, 'bannersStore'])->name('banners.store');
    Route::put('/banners/{id}', [AdminController::class, 'bannersUpdate'])->name('banners.update');
    Route::delete('/banners/{id}', [AdminController::class, 'bannersDestroy'])->name('banners.destroy');
    Route::patch('/banners/{id}/toggle', [AdminController::class, 'bannersToggle'])->name('banners.toggle');
    Route::post('/banners/reorder', [AdminController::class, 'bannersReorder'])->name('banners.reorder');
    Route::post('/homepage/sections', [AdminController::class, 'homepageSectionsUpdate'])->name('homepage.sections');

    // Flash Offer Popup Ads (Multiple Surprise Popups Support)
    Route::get('/offers-popup', [AdminController::class, 'popupAdIndex'])->name('popup.index');
    Route::post('/offers-popup', [AdminController::class, 'popupStore'])->name('popup.store');
    Route::put('/offers-popup/{id}', [AdminController::class, 'popupUpdate'])->name('popup.update');
    Route::delete('/offers-popup/{id}', [AdminController::class, 'popupDestroy'])->name('popup.destroy');
    Route::patch('/offers-popup/{id}/toggle', [AdminController::class, 'popupToggle'])->name('popup.toggle');

    // Shipping Zones & Rates
    Route::get('/shipping', [AdminController::class, 'shippingIndex'])->name('shipping.index');
    Route::post('/shipping', [AdminController::class, 'shippingStore'])->name('shipping.store');
    Route::put('/shipping/{id}', [AdminController::class, 'shippingUpdate'])->name('shipping.update');
    Route::delete('/shipping/{id}', [AdminController::class, 'shippingDestroy'])->name('shipping.destroy');

    // Settings
    Route::get('/settings', [AdminController::class, 'settingsIndex'])->name('settings.index');
    Route::post('/settings', [AdminController::class, 'settingsUpdate'])->name('settings.update');
    Route::post('/settings/seo-ping', [AdminController::class, 'pingSearchEngines'])->name('settings.seo.ping');

    // Customer Contact Inquiries
    Route::get('/messages', [AdminController::class, 'messagesIndex'])->name('messages.index');
    Route::delete('/messages/{id}', [AdminController::class, 'messagesDestroy'])->name('messages.destroy');

    // Affiliate & Payout Management
    Route::get('/affiliates', [AdminController::class, 'affiliatesIndex'])->name('affiliates.index');
    Route::put('/affiliates/{id}/rate', [AdminController::class, 'affiliateUpdateRate'])->name('affiliates.rate');
    Route::post('/affiliates/{id}/adjust-balance', [AdminController::class, 'affiliateAdjustBalance'])->name('affiliates.adjust-balance');
    Route::post('/affiliates/settings', [AdminController::class, 'affiliateUpdateSettings'])->name('affiliates.settings');
    Route::patch('/payouts/{id}/status', [AdminController::class, 'payoutUpdateStatus'])->name('payouts.status');
    // Admin Profile & Password Management
    Route::get('/profile', [AdminController::class, 'profileIndex'])->name('profile');
    Route::put('/profile', [AdminController::class, 'profileUpdate'])->name('profile.update');
});
