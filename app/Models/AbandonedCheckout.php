<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AbandonedCheckout extends Model
{
    protected $fillable = [
        'email', 'items', 'subtotal', 'item_count',
        'ip', 'user_agent', 'recovered_at', 'order_id',
    ];

    protected $casts = [
        'items'        => 'array',
        'subtotal'     => 'decimal:2',
        'recovered_at' => 'datetime',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function isRecovered(): bool
    {
        return $this->recovered_at !== null;
    }
}
