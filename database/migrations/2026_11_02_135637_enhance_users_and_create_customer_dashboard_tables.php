<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Enhance users table with referral code and wallet
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'referral_code')) {
                $table->string('referral_code', 30)->nullable()->unique()->after('phone');
            }
            if (!Schema::hasColumn('users', 'referred_by')) {
                $table->string('referred_by', 30)->nullable()->after('referral_code');
            }
            if (!Schema::hasColumn('users', 'wallet_balance')) {
                $table->decimal('wallet_balance', 10, 2)->default(0)->after('referred_by');
            }
        });

        // 2. User delivery addresses table
        Schema::create('user_addresses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('title')->default('المنزل'); // المنزل، العمل، استراحة، أخرى
            $table->string('recipient_name');
            $table->string('recipient_phone');
            $table->string('city');
            $table->string('district')->nullable();
            $table->string('street_address');
            $table->string('building_no')->nullable();
            $table->boolean('is_default')->default(false);
            $table->timestamps();
        });

        // 3. Referral commissions table
        Schema::create('referral_commissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('referrer_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('buyer_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('order_id')->nullable()->constrained('orders')->nullOnDelete();
            $table->decimal('order_total', 10, 2)->default(0);
            $table->decimal('commission_rate', 5, 2)->default(10.00); // 10%
            $table->decimal('commission_amount', 10, 2);
            $table->string('status')->default('approved'); // pending, approved, paid
            $table->timestamps();
        });

        // 4. Affiliate Payout Requests
        Schema::create('payout_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->decimal('amount', 10, 2);
            $table->string('bank_name');
            $table->string('account_name');
            $table->string('iban', 34);
            $table->string('status')->default('pending'); // pending, completed, rejected
            $table->text('admin_notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payout_requests');
        Schema::dropIfExists('referral_commissions');
        Schema::dropIfExists('user_addresses');

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['referral_code', 'referred_by', 'wallet_balance']);
        });
    }
};
