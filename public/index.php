<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Auto-create .env from .env.production if missing on fresh server deployment
if (!file_exists(__DIR__ . '/../.env') && file_exists(__DIR__ . '/../.env.production')) {
    @copy(__DIR__ . '/../.env.production', __DIR__ . '/../.env');
}

// Ensure required framework directories exist with write permissions
$frameworkDirs = [
    __DIR__ . '/../storage/framework/views',
    __DIR__ . '/../storage/framework/sessions',
    __DIR__ . '/../storage/framework/cache',
    __DIR__ . '/../storage/logs',
    __DIR__ . '/../bootstrap/cache',
];
foreach ($frameworkDirs as $dir) {
    if (!is_dir($dir)) {
        @mkdir($dir, 0775, true);
    }
}

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register the Composer autoloader...
require __DIR__.'/../vendor/autoload.php';

// Bootstrap Laravel and handle the request...
/** @var Application $app */
$app = require_once __DIR__.'/../bootstrap/app.php';

$app->handleRequest(Request::capture());
