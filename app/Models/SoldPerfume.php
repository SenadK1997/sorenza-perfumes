<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SoldPerfume extends Model
{
    protected $fillable = [
        'user_id',
        'perfume_id',
        'customer_id',
        'quantity',
        'base_price',
        'customer_price',
        'is_manual',
        'cancelled',
        'cancellation_reason',
    ];

    protected $casts = [
        'is_manual' => 'boolean',
        'cancelled' => 'boolean',
        'base_price' => 'decimal:2',
        'customer_price' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Relacija prema parfemu
    public function perfume(): BelongsTo
    {
        return $this->belongsTo(Perfume::class);
    }

    // Relacija prema kupcu
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }
}
