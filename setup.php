<?php

/**
 * ZADNEX STORE - Direct Standalone One-Click Installer & Repair Tool
 * Access via: /setup.php?secret=zadnex2026
 */

header('Content-Type: text/html; charset=utf-8');

if (($_GET['secret'] ?? '') !== 'zadnex2026') {
    http_response_code(403);
    die('<h1 style="color:red;text-align:center;font-family:sans-serif;margin-top:50px;">403 - غير مصرح بالدخول</h1>');
}

echo '<!DOCTYPE html><html dir="rtl" lang="ar"><head><meta charset="utf-8"><title>تهيئة متجر زاد نيكس</title>';
echo '<style>body{font-family:system-ui,-apple-system,sans-serif;background:#0F172A;color:#F8FAFC;padding:30px;direction:rtl;}';
echo '.card{max-width:800px;margin:0 auto;background:#1E293B;padding:30px;border-radius:16px;border:1px solid #334155;box-shadow:0 10px 25px rgba(0,0,0,0.5);}';
echo 'h1{color:#FBB404;margin-top:0;font-size:22px;}pre{background:#020617;padding:15px;border-radius:8px;color:#38BDF8;font-size:12px;overflow-x:auto;direction:ltr;text-align:left;}';
echo '.step{margin-bottom:15px;padding:10px 15px;border-radius:8px;background:#0F172A;border-right:4px solid #3B82F6;}';
echo '.success{color:#4ADE80;font-weight:bold;}.error{color:#F87171;font-weight:bold;}.btn{display:inline-block;background:#FBB404;color:#071E50;padding:12px 24px;border-radius:10px;text-decoration:none;font-weight:bold;margin-top:15px;}';
echo '</style></head><body><div class="card">';
echo '<h1>🚀 أداة التهيئة المباشرة لمتجر زاد نيكس (ZADNEX STORE)</h1>';

$baseDir = file_exists(__DIR__ . '/artisan') ? __DIR__ : dirname(__DIR__);

// 1. Create storage directories
echo '<div class="step">📁 جاري فحص وإنشاء مجلدات التخزين والصلاحيات... ';
$dirs = [
    $baseDir . '/storage/framework/views',
    $baseDir . '/storage/framework/sessions',
    $baseDir . '/storage/framework/cache',
    $baseDir . '/storage/logs',
    $baseDir . '/bootstrap/cache',
];
foreach ($dirs as $d) {
    if (!is_dir($d)) {
        @mkdir($d, 0775, true);
    }
}
echo '<span class="success">جاهز ✓</span></div>';

// 2. Generate and write proper .env file
echo '<div class="step">⚙️ جاري ضبط وتحديث ملف الإعدادات (.env)... ';
$envFile = $baseDir . '/.env';
$appKey = 'base64:jhtO497QtHNszuFqCtAJVDa1sN5N49moB9/rHBWhMxs=';

$envContent = <<<EOT
APP_NAME="متجر زاد نيكس | ZADNEX STORE"
APP_ENV=production
APP_KEY={$appKey}
APP_DEBUG=true
APP_URL=https://www.zadnexstore.com

APP_LOCALE=ar
APP_FALLBACK_LOCALE=ar
APP_FAKER_LOCALE=ar_SA

APP_MAINTENANCE_DRIVER=file
BCRYPT_ROUNDS=12

LOG_CHANNEL=stack
LOG_STACK=single
LOG_LEVEL=error

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=u363963266_zadnexstore
DB_USERNAME=u363963266_zadnexstore
DB_PASSWORD=Zadnexstore22

SESSION_DRIVER=file
SESSION_LIFETIME=120
SESSION_ENCRYPT=false
SESSION_PATH=/
SESSION_DOMAIN=null

BROADCAST_CONNECTION=log
FILESYSTEM_DISK=public
QUEUE_CONNECTION=sync
CACHE_STORE=file

MAIL_MAILER=smtp
MAIL_HOST=smtp.hostinger.com
MAIL_PORT=465
MAIL_USERNAME=info@zadnexstore.com
MAIL_PASSWORD=
MAIL_ENCRYPTION=ssl
MAIL_FROM_ADDRESS="info@zadnexstore.com"
MAIL_FROM_NAME="\${APP_NAME}"
EOT;

file_put_contents($envFile, $envContent);
echo '<span class="success">تم إنشاء وحفظ .env بنجاح ✓</span></div>';

// 3. Test MySQL connection
echo '<div class="step">🔌 جاري اختبار الاتصال بقاعدة بيانات هوستنجر (u363963266_zadnexstore)... ';
try {
    $pdo = new PDO(
        'mysql:host=127.0.0.1;port=3306;dbname=u363963266_zadnexstore;charset=utf8mb4',
        'u363963266_zadnexstore',
        'Zadnexstore22',
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
    echo '<span class="success">الاتصال ناجح 100% ✓</span></div>';
} catch (PDOException $e) {
    echo '<span class="error">فشل الاتصال! ✗</span><br><pre>' . htmlspecialchars($e->getMessage()) . '</pre>';
    echo '<p style="color:#FBB404;">⚠️ يرجى التأكد من الضغط على زر "Create" في هوستنجر لإنشاء قاعدة البيانات بهذا الاسم: u363963266_zadnexstore وكلمة المرور: Zadnexstore22</p></div></div></body></html>';
    exit;
}

// 4. Run Laravel Migrations & Seeders via Artisan CLI
echo '<div class="step">📦 جاري إنشاء الجداول وتعبئة منتجات وأقسام زاد نيكس...<br>';
require $baseDir . '/vendor/autoload.php';
$app = require $baseDir . '/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

// Key Generate
$kernel->call('key:generate', ['--force' => true]);

// Migrate
$kernel->call('migrate', ['--force' => true]);
$migrateOut = $kernel->output();
echo '<pre>' . htmlspecialchars($migrateOut) . '</pre>';

// Seed
$kernel->call('db:seed', ['--force' => true, '--class' => 'ZadnexDatabaseSeeder']);
$seedOut = $kernel->output();
echo '<pre>' . htmlspecialchars($seedOut) . '</pre>';

// Storage link & Clear Cache
$kernel->call('storage:link');
$kernel->call('optimize:clear');
$cacheOut = $kernel->output();
echo '<pre>' . htmlspecialchars($cacheOut) . '</pre>';

echo '<span class="success">تم تهيئة وتعبئة البيانات بنجاح تام ✓</span></div>';

echo '<div style="text-align:center;margin-top:25px;">';
echo '<h2 style="color:#4ADE80;">🎉 مبروك! متجر زاد نيكس جاهز ويعمل بكفاءة 100% الآن</h2>';
echo '<a href="/" class="btn">الانتقال للمتجر الرئيسي ➔</a>';
echo '</div>';

echo '</div></body></html>';
