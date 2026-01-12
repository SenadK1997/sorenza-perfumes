<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;

class Coupon extends Model
{
    protected $fillable = [
        'user_id', 'code', 'type', 'value', 'min_total', 
        'usage_limit', 'used_count', 'starts_at', 'expires_at', 'is_active'
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'expires_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Check if the coupon is valid for a specific total
     */
    public function isValidFor($total): bool
    {
        if (!$this->is_active) return false;

        // Check if limit reached
        if ($this->usage_limit && $this->used_count >= $this->usage_limit) return false;

        // Check dates
        $now = Carbon::now();
        if ($this->starts_at && $now->lt($this->starts_at)) return false;
        if ($this->expires_at && $now->gt($this->expires_at)) return false;

        // Check minimum order amount
        if ($this->min_total && $total < $this->min_total) return false;

        return true;
    }

    /**
     * Calculate the discount amount based on a subtotal
     */
    public function calculateDiscount($subtotal): float
    {
        if ($this->type === 'percent') {
            return ($this->value / 100) * $subtotal;
        }

        return min($this->value, $subtotal); // Don't discount more than the subtotal
    }
}