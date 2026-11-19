<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Response;

class SeoController extends Controller
{
    /**
     * Dynamic XML Sitemap for automatic Google indexing.
     * Contains all active products, categories, home, and policy pages.
     */
    public function sitemap()
    {
        $products = Product::where('is_active', true)->latest('updated_at')->get();
        $categories = Category::where('is_active', true)->latest('updated_at')->get();

        $content = view('seo.sitemap', compact('products', 'categories'))->render();

        return response($content, 200)
            ->header('Content-Type', 'text/xml; charset=utf-8');
    }

    /**
     * Dynamic robots.txt pointing to the XML sitemap.
     */
    public function robots()
    {
        $sitemapUrl = url('/sitemap.xml');

        $content = "User-agent: *\n"
            . "Disallow: /admin/\n"
            . "Disallow: /checkout\n"
            . "Disallow: /customer/\n"
            . "Disallow: /my-account\n"
            . "Disallow: /paymob/\n"
            . "Disallow: /cart\n"
            . "Allow: /\n\n"
            . "Sitemap: {$sitemapUrl}\n";

        return response($content, 200)
            ->header('Content-Type', 'text/plain; charset=utf-8');
    }
}

