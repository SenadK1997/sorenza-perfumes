<?php

namespace App\Services;

use App\Models\SiteSetting;

class CartTierDiscount
{
    public const SETTING_ENABLED = 'cart_tier_discount_enabled';
    public const SETTING_TIERS   = 'cart_tier_discounts';

    public static function enabled(): bool
    {
        return SiteSetting::bool(self::SETTING_ENABLED, false);
    }

    /**
     * Returns tiers sorted by min_subtotal asc.
     * Each item: ['min_subtotal' => float, 'discount' => float]
     */
    public static function tiers(): array
    {
        $raw = SiteSetting::get(self::SETTING_TIERS, '[]');
        $decoded = is_array($raw) ? $raw : json_decode((string) $raw, true);
        if (!is_array($decoded)) $decoded = [];

        $tiers = [];
        foreach ($decoded as $t) {
            $min      = (float) ($t['min_subtotal'] ?? 0);
            $discount = (float) ($t['discount'] ?? 0);
            if ($min > 0 && $discount > 0) {
                $tiers[] = ['min_subtotal' => $min, 'discount' => $discount];
            }
        }

        usort($tiers, fn ($a, $b) => $a['min_subtotal'] <=> $b['min_subtotal']);
        return $tiers;
    }

    public static function setTiers(array $tiers): void
    {
        SiteSetting::set(self::SETTING_TIERS, json_encode(array_values($tiers)));
    }

    /**
     * The highest tier the customer has reached (or null).
     */
    public static function earned(float $subtotal): ?array
    {
        if (!self::enabled() || $subtotal <= 0) return null;

        $best = null;
        foreach (self::tiers() as $tier) {
            if ($subtotal >= $tier['min_subtotal']) $best = $tier;
        }
        return $best;
    }

    /**
     * The next tier not yet reached (or null if all reached / disabled).
     */
    public static function next(float $subtotal): ?array
    {
        if (!self::enabled()) return null;
        foreach (self::tiers() as $tier) {
            if ($subtotal < $tier['min_subtotal']) return $tier;
        }
        return null;
    }

    public static function discount(float $subtotal): float
    {
        $earned = self::earned($subtotal);
        if (!$earned) return 0.0;
        return min((float) $earned['discount'], $subtotal);
    }
}
