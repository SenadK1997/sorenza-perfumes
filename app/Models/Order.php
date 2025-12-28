<?php

namespace App\Models;

use App\Enums\OrderStatus;
use App\Enums\Canton;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $casts = [
        'status' => OrderStatus::class,
        'canton' => Canton::class,
    ];

    // Relationships (optional)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function perfumes()
    {
        return $this->belongsToMany(Perfume::class)
            ->withPivot(['quantity', 'price'])
            ->withTimestamps();
    }
}