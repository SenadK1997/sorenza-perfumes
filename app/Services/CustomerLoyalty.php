<?php

namespace App\Services;

use App\Models\Customer;
use App\Models\Order;
use App\Models\SoldPerfume;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class CustomerLoyalty
{
    /**
     * Loyalty tiers by SPEND-THIS-YEAR (KM), ordered ascending.
     * Every calendar year on Dec 31 at midnight the effective spend resets
     * (we simply count spend from Jan 1 of the current year to now).
     *
     * discount = automatic % applied at checkout for logged-in customers.
     */
    public const TIERS = [
        ['key' => 'bronze',   'name' => 'Bronze',   'min' => 0,    'next' => 200,  'discount' => 3,  'accent' => '#b06a3b'],
        ['key' => 'silver',   'name' => 'Silver',   'min' => 200,  'next' => 500,  'discount' => 5,  'accent' => '#8a8f97'],
        ['key' => 'gold',     'name' => 'Gold',     'min' => 500,  'next' => 1000, 'discount' => 10, 'accent' => '#c8a24a'],
        ['key' => 'platinum', 'name' => 'Platinum', 'min' => 1000, 'next' => 2000, 'discount' => 15, 'accent' => '#5a3fb0'],
        ['key' => 'sorenza',  'name' => 'Sorenza',  'min' => 2000, 'next' => null, 'discount' => 20, 'accent' => '#c026d3'],
    ];

    /**
     * Returns tier info for the given (this-year) spend.
     * Shape: ['key', 'name', 'min', 'next', 'discount', 'accent',
     *         'spent', 'to_next', 'progress_pct', 'resets_at']
     */
    public static function forSpend(float $spent): array
    {
        $tier = self::TIERS[0];
        foreach (self::TIERS as $t) {
            if ($spent >= $t['min']) $tier = $t;
        }

        $toNext = $tier['next'] !== null ? max(0, (float) $tier['next'] - $spent) : 0;

        $progressPct = 100;
        if ($tier['next'] !== null) {
            $span   = (float) $tier['next'] - (float) $tier['min'];
            $filled = max(0, $spent - (float) $tier['min']);
            $progressPct = $span > 0 ? min(100, ($filled / $span) * 100) : 0;
        }

        return array_merge($tier, [
            'spent'        => (float) $spent,
            'to_next'      => (float) $toNext,
            'progress_pct' => $progressPct,
            'resets_at'    => self::yearlyResetAt(),
        ]);
    }

    public static function yearStart(): Carbon
    {
        return Carbon::create(Carbon::now()->year, 1, 1, 0, 0, 0);
    }

    /**
     * Reset moment: Dec 31 at midnight (i.e. the start of Jan 1 next year).
     */
    public static function yearlyResetAt(): Carbon
    {
        return Carbon::create(Carbon::now()->year + 1, 1, 1, 0, 0, 0);
    }

    /**
     * Total the customer has actually paid us THIS YEAR:
     *   - completed online orders (orders.amount)
     *   - manual direct sales (is_manual=true, cancelled=false) × customer_price
     *   Excludes is_manual=false rows (they mirror completed orders for vault
     *   accounting and would double-count).
     */
    /**
     * Real spending in KM this year (orders + manual direct sales).
     * Does NOT include the admin bonus — that's tracked separately.
     */
    public static function realSpentThisYear(Customer $customer): float
    {
        $yearStart = self::yearStart();

        $ordersSum = (float) Order::where('email', $customer->email)
            ->where('status', 'completed')
            ->where('created_at', '>=', $yearStart)
            ->sum('amount');

        $salesSum = (float) SoldPerfume::where('customer_id', $customer->id)
            ->where('is_manual', true)
            ->where('cancelled', false)
            ->where('created_at', '>=', $yearStart)
            ->sum(DB::raw('ABS(quantity) * COALESCE(customer_price, 0)'));

        return $ordersSum + $salesSum;
    }

    /**
     * Bonus loyalty poeni (admin-set). Not a KM figure — semantically "points".
     * Kept as a lifetime knob; doesn't auto-reset yearly.
     */
    public static function bonusPoints(Customer $customer): float
    {
        return (float) ($customer->loyalty_adjustment ?? 0);
    }

    /**
     * Total score used to determine the tier =
     *   real KM spent this year + bonus poeni.
     * Displayed to the admin/customer as their "loyalty score", NOT as extra KM.
     */
    public static function spentThisYear(Customer $customer): float
    {
        return max(0, self::realSpentThisYear($customer) + self::bonusPoints($customer));
    }

    /**
     * Convenience: tier info for a customer, using this-year spend.
     */
    public static function forCustomer(Customer $customer): array
    {
        return self::forSpend(self::spentThisYear($customer));
    }

    /**
     * Automatic checkout discount % this customer is entitled to right now
     * based on their current tier.
     */
    public static function discountPercentFor(?Customer $customer): float
    {
        if (!$customer) return 0.0;
        $tier = self::forCustomer($customer);
        return (float) $tier['discount'];
    }

    /**
     * Apply the tier discount to a subtotal (in KM).
     */
    public static function discountAmountFor(?Customer $customer, float $subtotal): float
    {
        if (!$customer || $subtotal <= 0) return 0.0;
        $pct = self::discountPercentFor($customer);
        if ($pct <= 0) return 0.0;
        return round($subtotal * ($pct / 100), 2);
    }
}
