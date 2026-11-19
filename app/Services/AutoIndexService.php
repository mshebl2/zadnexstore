<?php

namespace App\Services;

use App\Models\Product;
use App\Models\StoreSetting;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AutoIndexService
{
    /**
     * Get or generate the IndexNow key.
     */
    public static function getIndexNowKey(): string
    {
        if (app()->runningUnitTests()) {
            return 'zadnexstore_test_key';
        }

        $key = StoreSetting::get('indexnow_key');
        if (!$key) {
            $key = 'zadnex_' . bin2hex(random_bytes(12));
            StoreSetting::set('indexnow_key', $key);
        }

        // Ensure key file exists in public directory
        $keyFilePath = public_path("{$key}.txt");
        if (!file_exists($keyFilePath)) {
            @file_put_contents($keyFilePath, $key);
        }

        // Also maintain indexnow-key.txt for reference
        $refFilePath = public_path('indexnow-key.txt');
        if (!file_exists($refFilePath)) {
            @file_put_contents($refFilePath, $key);
        }

        return $key;
    }

    /**
     * Ping search engines for a single product.
     */
    public static function pingProduct(Product $product): bool
    {
        if (!$product->is_active) {
            return false;
        }

        $url = route('product.show', $product->slug);
        return self::pingUrls([$url]);
    }

    /**
     * Ping search engines for a list of URLs via IndexNow + Search Engine Sitemaps.
     */
    public static function pingUrls(array $urls): bool
    {
        if (app()->runningUnitTests()) {
            return true;
        }

        if (empty($urls)) {
            return false;
        }

        $host = parse_url(config('app.url'), PHP_URL_HOST) ?: 'zadnexstore.com';
        $key = self::getIndexNowKey();

        try {
            // 1. IndexNow API (Bing, Yandex, Seznam, Naver & all participating search engines)
            Http::timeout(5)->post('https://api.indexnow.org/indexnow', [
                'host' => $host,
                'key' => $key,
                'keyLocation' => url("{$key}.txt"),
                'urlList' => array_values(array_unique($urls)),
            ]);

            // 2. Ping Google Sitemap
            $sitemapUrl = url('/sitemap.xml');
            Http::timeout(5)->get('https://www.google.com/ping', [
                'sitemap' => $sitemapUrl,
            ]);

            // 3. Ping Bing Sitemap
            Http::timeout(5)->get('https://www.bing.com/ping', [
                'sitemap' => $sitemapUrl,
            ]);

            Log::info('Auto-Indexing ping dispatched successfully for: ' . implode(', ', $urls));
            return true;
        } catch (\Throwable $e) {
            Log::warning('Auto-Indexing ping warning: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Ping the entire sitemap (for all products/categories).
     */
    public static function pingFullSitemap(): array
    {
        $urls = [
            url('/'),
            url('/sitemap.xml'),
        ];

        $productUrls = Product::where('is_active', true)
            ->limit(100)
            ->get()
            ->map(fn($p) => route('product.show', $p->slug))
            ->toArray();

        $allUrls = array_merge($urls, $productUrls);
        $success = self::pingUrls($allUrls);

        return [
            'success' => $success,
            'urls_count' => count($allUrls),
            'sitemap_url' => url('/sitemap.xml'),
            'indexnow_key' => self::getIndexNowKey(),
        ];
    }
}
