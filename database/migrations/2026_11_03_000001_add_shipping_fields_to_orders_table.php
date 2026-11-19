<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('shipping_company')->nullable()->after('payment_method');
            $table->string('shipping_tracking_number')->nullable()->after('shipping_company');
            $table->text('shipping_label_url')->nullable()->after('shipping_tracking_number');
            $table->string('shipping_status')->nullable()->default('pending')->after('shipping_label_url');
            $table->json('shipping_response')->nullable()->after('shipping_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'shipping_company',
                'shipping_tracking_number',
                'shipping_label_url',
                'shipping_status',
                'shipping_response',
            ]);
        });
    }
};

