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
        'price',
        'discount_percentage',
        'main_image',
        'secondary_image',
        'description',  // Good to have if you use it later
        'tag',
        'accords',
        'availability', 
        'restock_date',
    ];

    // Cast 'accords' JSON column to array automatically
    protected $casts = [
        'accords' => 'array',
        'gender' => PerfumeGender::class,
        'availability' => 'boolean',
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

    public function scopeAvailable($query)
    {
        return $query->where('availability', true)
                    ->orWhere(function ($q) {
                        $q->where('availability', false)
                        ->whereNotNull('restock_date')
                        ->where('restock_date', '<=', now());
                    });
    }

}
