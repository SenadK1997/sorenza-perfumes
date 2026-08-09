<?php

namespace App\Services;

use App\Models\SiteSetting;

class ShippingCalculator
{
    /**
     * Shipping fee for a given subtotal.
     * - When "free shipping" toggle is ON → shipping is always 0.
     * - When OFF → charge flat fee, unless subtotal ≥ threshold (if threshold > 0).
     */
    public static function fee(float $subtotal): float
    {
        if ($subtotal <= 0) {
            return 0.0;
        }

        if (self::alwaysFree()) {
            return 0.0;
        }

        $threshold = self::threshold();
        if ($threshold > 0 && $subtotal >= $threshold) {
            return 0.0;
        }

        return self::flatFee();
    }

    /** Admin toggle: shipping is always free (no fee, no threshold). */
    public static function alwaysFree(): bool
    {
        return SiteSetting::bool('free_shipping_enabled', true);
    }

    /** Legacy alias for the frontend/view code. */
    public static function freeShippingEnabled(): bool
    {
        return self::alwaysFree();
    }

    public static function threshold(): float
    {
        return SiteSetting::float('free_shipping_threshold', 120);
    }

    public static function flatFee(): float
    {
        return SiteSetting::float('shipping_fee', 10);
    }

    /** What we tell customers — shown in cart, product page, footer, email, etc. */
    public static function refundDays(): int
    {
        return SiteSetting::int('refund_days', 7);
    }

    /**
     * What we actually enforce when deciding if a customer can still submit a refund
     * request. Silently longer than the displayed number so that delivery time doesn't
     * eat into the customer's usable window. Never shorter than the displayed value.
     */
    public static function refundDaysEffective(): int
    {
        return max(15, self::refundDays());
    }

    /** How much more the customer needs to spend to reach free shipping. 0 if already free or impossible. */
    public static function amountToFreeShipping(float $subtotal): float
    {
        if (self::alwaysFree()) return 0.0;
        $threshold = self::threshold();
        if ($threshold <= 0) return 0.0;
        return max(0.0, $threshold - $subtotal);
    }

    /** True if the current subtotal (or the mode) grants free shipping. */
    public static function qualifiesForFree(float $subtotal): bool
    {
        if (self::alwaysFree()) return true;
        $threshold = self::threshold();
        return $threshold > 0 && $subtotal >= $threshold;
    }

    /** Human-readable summary for trust strips ("Besplatna dostava" / "Besplatna dostava iznad X KM" / "Dostava X KM"). */
    public static function summaryLabel(): string
    {
        if (self::alwaysFree()) {
            return 'Besplatna dostava';
        }

        $threshold = self::threshold();
        if ($threshold > 0) {
            return 'Besplatna dostava iznad ' . rtrim(rtrim(number_format($threshold, 2, '.', ''), '0'), '.') . ' KM';
        }

        $fee = self::flatFee();
        return 'Dostava ' . number_format($fee, 2, '.', '') . ' KM';
    }
}
