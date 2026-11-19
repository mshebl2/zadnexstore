<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HomepageBanner extends Model
{
    use HasFactory;

    protected $fillable = [
        'placement', // hero, secondary, offers, promo
        'badge_ar',
        'title_ar',
        'title_en',
        'subtitle_ar',
        'button_text_ar',
        'button_url',
        'link_type', // url, product, category
        'link_target',
        'image_url', // Desktop artwork
        'mobile_image_url', // Mobile portrait artwork
        'sort_order',
        'is_active',
        'starts_at',
        'ends_at',
    ];

    protected $casts = [
        'sort_order' => 'integer',
        'is_active' => 'boolean',
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('starts_at')->orWhere('starts_at', '<=', now());
            })
            ->where(function ($q) {
                $q->whereNull('ends_at')->orWhere('ends_at', '>=', now());
            })
            ->orderBy('sort_order', 'asc');
    }

    public function scopeHero($query)
    {
        return $query->where('placement', 'hero')->active();
    }

    public function scopeSecondary($query)
    {
        return $query->where('placement', 'secondary')->active();
    }

    public function scopeOffers($query)
    {
        return $query->where('placement', 'offers')->active();
    }

    public function scopePromo($query)
    {
        return $query->where('placement', 'promo')->active();
    }

    /**
     * Get the destination URL for clicking the banner.
     */
    public function getDestinationUrlAttribute(): string
    {
        if ($this->link_target && filter_var($this->link_target, FILTER_VALIDATE_URL)) {
            return $this->link_target;
        }

        if ($this->link_target && str_starts_with($this->link_target, '/')) {
            return url($this->link_target);
        }

        if ($this->button_url) {
            return str_starts_with($this->button_url, '/') ? url($this->button_url) : $this->button_url;
        }

        return route('products.index');
    }
}
