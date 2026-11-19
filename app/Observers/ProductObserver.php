<?php

namespace App\Observers;

use App\Models\Product;
use App\Services\AutoIndexService;

class ProductObserver
{
    /**
     * Handle the Product "saved" event (covers created and updated).
     */
    public function saved(Product $product): void
    {
        AutoIndexService::pingProduct($product);
    }

    /**
     * Handle the Product "deleted" event.
     */
    public function deleted(Product $product): void
    {
        AutoIndexService::pingUrls([url('/sitemap.xml'), url('/')]);
    }
}

