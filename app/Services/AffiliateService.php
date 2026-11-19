<?php

namespace App\Services;

use App\Models\Order;
use App\Models\User;
use App\Models\ReferralCommission;
use App\Models\StoreSetting;
use Illuminate\Support\Facades\Log;

class AffiliateService
{
    public static function processCommission(Order $order): void
    {
        try {
            // Avoid duplicate commissions for the same order
            if (ReferralCommission::where('order_id', $order->id)->exists()) {
                return;
            }

            // Determine referral code
            $referralCode = null;
            if ($order->user && !empty($order->user->referred_by)) {
                $referralCode = $order->user->referred_by;
            } elseif (session()->has('referred_by')) {
                $referralCode = session('referred_by');
            } elseif (request()->hasCookie('referred_by')) {
                $referralCode = request()->cookie('referred_by');
            }

            if (!$referralCode) {
                return;
            }

            $referrer = User::where('referral_code', $referralCode)->first();
            if (!$referrer || !$referrer->is_affiliate_active) {
                return;
            }

            // A user cannot refer themselves
            if ($order->user_id && $referrer->id === $order->user_id) {
                return;
            }

            // Calculate commission based on user custom override or global store setting
            $rate = 0.0;
            $commissionAmount = 0.0;

            if ($referrer->custom_commission_type === 'fixed' && $referrer->custom_commission_rate !== null) {
                $commissionAmount = round((float) $referrer->custom_commission_rate, 2);
                $rate = (float) $referrer->custom_commission_rate;
            } elseif ($referrer->custom_commission_type === 'percent' && $referrer->custom_commission_rate !== null) {
                $rate = (float) $referrer->custom_commission_rate;
                $commissionAmount = round(($order->subtotal * $rate) / 100, 2);
            } else {
                $defaultType = StoreSetting::get('affiliate_commission_type', 'percent');
                $defaultRate = (float) StoreSetting::get('affiliate_commission_rate', '10.00');
                $rate = $defaultRate;

                if ($defaultType === 'fixed') {
                    $commissionAmount = round($defaultRate, 2);
                } else {
                    $commissionAmount = round(($order->subtotal * $defaultRate) / 100, 2);
                }
            }

            if ($commissionAmount <= 0) {
                return;
            }

            ReferralCommission::create([
                'referrer_id' => $referrer->id,
                'buyer_id' => $order->user_id,
                'order_id' => $order->id,
                'order_total' => $order->total,
                'commission_rate' => $rate,
                'commission_amount' => $commissionAmount,
                'status' => 'approved',
            ]);

            $referrer->increment('wallet_balance', $commissionAmount);

            Log::info("Affiliate commission of {$commissionAmount} SAR credited to User #{$referrer->id} ({$referrer->name}) for Order #{$order->order_number}");
        } catch (\Throwable $e) {
            Log::error('Failed to process affiliate commission: ' . $e->getMessage());
        }
    }
}

