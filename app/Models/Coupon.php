<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Collection;
use App\Models\User;
use App\Models\Perfume;

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

    public function perfumes(): BelongsToMany
    {
        return $this->belongsToMany(Perfume::class);
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
     * Check validity against the cart items, taking bound perfumes into account.
     * If the coupon is bound to specific perfumes, at least one of them must be
     * in the cart AND the sum of those bound items must meet min_total.
     *
     * $items: Collection of items each with ->id, ->price, ->quantity (Perfume-like).
     */
    public function isValidForCart(Collection $items): bool
    {
        $eligibleTotal = $this->eligibleSubtotal($items);

        if ($this->hasPerfumeRestriction() && $eligibleTotal <= 0) {
            return false;
        }

        return $this->isValidFor($eligibleTotal);
    }

    public function hasPerfumeRestriction(): bool
    {
        return $this->perfumes()->exists();
    }

    /**
     * Return the subtotal of cart items that this coupon actually applies to.
     * If the coupon is not restricted, that's the whole cart subtotal.
     */
    public function eligibleSubtotal(Collection $items): float
    {
        if (!$this->hasPerfumeRestriction()) {
            return (float) $items->sum(fn ($i) => (float) $i->price * (int) $i->quantity);
        }

        $allowedIds = $this->perfumes()->pluck('perfumes.id')->all();

        return (float) $items
            ->filter(fn ($i) => in_array((int) $i->id, $allowedIds, true))
            ->sum(fn ($i) => (float) $i->price * (int) $i->quantity);
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

    /**
     * Calculate the discount for a cart, respecting perfume restrictions.
     */
    public function calculateCartDiscount(Collection $items): float
    {
        $base = $this->eligibleSubtotal($items);
        if ($base <= 0) return 0;
        return $this->calculateDiscount($base);
    }
}