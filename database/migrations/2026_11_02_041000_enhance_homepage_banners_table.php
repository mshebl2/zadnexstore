<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('homepage_banners', function (Blueprint $table) {
            $table->string('placement')->default('hero')->after('id'); // hero, secondary, offers, promo
            $table->string('mobile_image_url')->nullable()->after('image_url');
            $table->string('link_type')->default('url')->after('button_url'); // url, product, category
            $table->string('link_target')->nullable()->after('link_type');
            $table->timestamp('starts_at')->nullable()->after('is_active');
            $table->timestamp('ends_at')->nullable()->after('starts_at');

            $table->index(['placement', 'is_active', 'sort_order']);
        });
    }

    public function down(): void
    {
        Schema::table('homepage_banners', function (Blueprint $table) {
            $table->dropIndex(['placement', 'is_active', 'sort_order']);
            $table->dropColumn(['placement', 'mobile_image_url', 'link_type', 'link_target', 'starts_at', 'ends_at']);
        });
    }
};

