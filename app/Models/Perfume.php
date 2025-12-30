<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Enums\PerfumeGender;

class Perfume extends Model
{
    // Allow mass assignment for these fields
    protected $fillable = [
        'name',
        'price',
        'discount_percentage',
        'main_image',
        'secondary_image',
        'tag',
        'accords',
    ];

    // Cast 'accords' JSON column to array automatically
    protected $casts = [
        'accords' => 'array',
        'gender' => PerfumeGender::class,
    ];

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

}
