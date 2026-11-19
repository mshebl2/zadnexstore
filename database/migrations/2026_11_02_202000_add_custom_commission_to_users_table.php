<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('custom_commission_type')->nullable()->after('wallet_balance'); // null (default), 'percent', 'fixed'
            $table->decimal('custom_commission_rate', 10, 2)->nullable()->after('custom_commission_type');
            $table->boolean('is_affiliate_active')->default(true)->after('custom_commission_rate');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['custom_commission_type', 'custom_commission_rate', 'is_affiliate_active']);
        });
    }
};

