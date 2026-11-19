<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FlashPopup extends Model
{
    use HasFactory;

    protected $fillable = [
        'title_ar',
        'subtitle_ar',
        'badge_ar',
        'coupon_code',
        'button_text_ar',
        'button_url',
        'image_url',
        'delay_seconds',
        'duration_seconds',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'delay_seconds' => 'integer',
        'duration_seconds' => 'integer',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->latest();
    }
}

