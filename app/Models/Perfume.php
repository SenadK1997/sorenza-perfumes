<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Enums\PerfumeGender;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Carbon\Carbon;

class Perfume extends Model
{
    // Allow mass assignment for these fields
    protected $fillable = [
        'name',
        'gender',       // Added this
        'inspired_by', // Added this
        'base_price',  // Added this
        'original_price',
        'price',
        'discount_percentage',
        'main_image',
        'secondary_image',
        'description',  // Good to have if you use it later
        'tag',
        'accords',
        'availability',
        'is_bestseller',
        'restock_date',
    ];

    // Cast 'accords' JSON column to array automatically
    protected $casts = [
        'accords' => 'array',
        'gender' => PerfumeGender::class,
        'availability' => 'boolean',
        'is_bestseller' => 'boolean',
        'restock_date' => 'date',
    ];

    protected function isAvailable(): Attribute
    {
        return Attribute::get(function () {
            // 1. If explicitly set to true in DB, it's available.
            if ($this->availability) {
                return true;
            }

            // 2. If false, check if we have a restock date and if that date has passed.
            // isPast() returns true if the date is earlier than "now".
            return $this->restock_date && $this->restock_date->isPast();
        });
    }

    public function __toString()
    {
        return $this->name;
    }

    public function orders()
    {
        return $this->belongsToMany(Order::class)
            ->withPivot(['quantity', 'price'])
            ->withTimestamps();
    }

    public function sellers()
    {
        return $this->belongsToMany(User::class, 'perfume_seller')
            ->withPivot('stock')
            ->withTimestamps();
    }

    public function scopeVisibleInShop($query)
    {
        return $query->where(function ($q) {
            // Show if explicitly in stock
            $q->where('availability', true)
            // OR show if out of stock but has a future restock date
            ->orWhere(function ($sub) {
                $sub->where('availability', false)
                    ->whereNotNull('restock_date')
                    ->where('restock_date', '>', now());
            });
        });
    }

    public function scopeOnSale($query)
    {
        return $query->whereNotNull('original_price')
            ->whereColumn('original_price', '>', 'price');
    }

    public function getIsOnSaleAttribute(): bool
    {
        return $this->original_price !== null
            && (float) $this->original_price > (float) $this->price;
    }

    protected static function booted(): void
    {
        static::saving(function (self $perfume) {
            $price    = (float) ($perfume->price ?? 0);
            $original = $perfume->original_price !== null
                ? (float) $perfume->original_price
                : null;

            // If original isn't set, mirror the price so display logic stays consistent
            // and no crossed-out price is shown.
            if ($original === null || $original <= 0) {
                $perfume->original_price = $price > 0 ? $price : null;
                $perfume->discount_percentage = 0;
                return;
            }

            // Guard against inverted inputs.
            if ($price > $original) {
                $perfume->original_price = $price;
                $perfume->discount_percentage = 0;
                return;
            }

            if ($price <= 0 || $price == $original) {
                $perfume->discount_percentage = 0;
                return;
            }

            $perfume->discount_percentage = (int) round((1 - $price / $original) * 100);
        });
    }
}
