<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'role',
        'referral_code',
        'referred_by',
        'wallet_balance',
        'custom_commission_type',
        'custom_commission_rate',
        'is_affiliate_active',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'wallet_balance' => 'decimal:2',
            'password' => 'hashed',
        ];
    }

    protected static function booted()
    {
        static::creating(function ($user) {
            if (empty($user->referral_code)) {
                $user->referral_code = strtoupper(Str::random(8));
            }
        });
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function wishlists(): HasMany
    {
        return $this->hasMany(Wishlist::class);
    }

    public function addresses(): HasMany
    {
        return $this->hasMany(UserAddress::class);
    }

    public function referralCommissions(): HasMany
    {
        return $this->hasMany(ReferralCommission::class, 'referrer_id');
    }

    public function payoutRequests(): HasMany
    {
        return $this->hasMany(PayoutRequest::class);
    }

    public function referredUsers(): HasMany
    {
        return $this->hasMany(User::class, 'referred_by', 'referral_code');
    }

    public function getReferralLinkAttribute(): string
    {
        return url('/?ref=' . ($this->referral_code ?: $this->id));
    }

    public function getEffectiveCommissionDescriptionAttribute(): string
    {
        if ($this->custom_commission_type === 'percent' && $this->custom_commission_rate !== null) {
            return $this->custom_commission_rate . '% (خاص)';
        } elseif ($this->custom_commission_type === 'fixed' && $this->custom_commission_rate !== null) {
            return $this->custom_commission_rate . ' ر.س ثابت (خاص)';
        }

        $defaultType = StoreSetting::get('affiliate_commission_type', 'percent');
        $defaultRate = StoreSetting::get('affiliate_commission_rate', '10.00');
        return $defaultType === 'fixed' ? ($defaultRate . ' ر.س (افتراضي)') : ($defaultRate . '% (افتراضي)');
    }
}
