<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'user_id',
        'customer_name',
        'customer_phone',
        'customer_email',
        'city',
        'district',
        'street_address',
        'building_info',
        'notes',
        'subtotal',
        'shipping_cost',
        'discount_amount',
        'total',
        'coupon_code',
        'status',
        'payment_status',
        'payment_method',
        'shipping_company',
        'shipping_tracking_number',
        'shipping_label_url',
        'shipping_status',
        'shipping_response',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'shipping_cost' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'total' => 'decimal:2',
        'shipping_response' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function latestPayment(): HasOne
    {
        return $this->hasOne(Payment::class)->latestOfMany();
    }

    public function getStatusLabelArAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'قيد المراجعة',
            'processing' => 'قيد التجهيز',
            'shipped' => 'تم الشحن',
            'delivered' => 'تم التوصيل',
            'cancelled' => 'ملغي',
            default => $this->status,
        };
    }

    public function getPaymentStatusLabelArAttribute(): string
    {
        return match ($this->payment_status) {
            'paid' => 'مدفوع',
            'unpaid' => 'غير مدفوع',
            'failed' => 'فشلت عملية الدفع',
            'refunded' => 'مسترد',
            default => $this->payment_status,
        };
    }
}

