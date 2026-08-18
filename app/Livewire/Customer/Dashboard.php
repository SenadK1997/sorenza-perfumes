<?php

namespace App\Livewire\Customer;

use App\Models\Order;
use App\Models\SoldPerfume;
use App\Services\CustomerLoyalty;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Dashboard extends Component
{
    #[Layout('layouts.app')]
    public function render()
    {
        $customer = Auth::guard('customer')->user();

        $orders = Order::where('email', $customer->email)
            ->latest('id')
            ->limit(5)
            ->get();

        $ordersBase = Order::where('email', $customer->email);

        // Only manual direct sales count for the customer view.
        // is_manual=false rows are auto-generated when an order completes (vault
        // accounting) and would double-count the online order.
        $manualSalesBase = SoldPerfume::where('customer_id', $customer->id)
            ->where('is_manual', true);

        $counts = [
            // Active only comes from online orders (direct sales are one-shot).
            'active'    => (clone $ordersBase)->whereIn('status', ['pending', 'taken'])->count(),
            'completed' => (clone $ordersBase)->where('status', 'completed')->count()
                        + (clone $manualSalesBase)->where('cancelled', false)->count(),
            'cancelled' => (clone $ordersBase)->where('status', 'cancelled')->count()
                        + (clone $manualSalesBase)->where('cancelled', true)->count(),
        ];

        // Total customer-paid: orders.amount + manual direct sales × customer_price.
        // ABS(quantity) because storno rows are stored with negative quantity for
        // internal accounting but the refund itself represents money that went out
        // — we simply exclude cancelled ones from "spent" instead of subtracting.
        // Lifetime spent (for display context)
        $ordersSpent = (float) (clone $ordersBase)->where('status', 'completed')->sum('amount');
        $salesSpent  = (float) (clone $manualSalesBase)->where('cancelled', false)
            ->sum(\Illuminate\Support\Facades\DB::raw('ABS(quantity) * COALESCE(customer_price, 0)'));
        $totalSpent = $ordersSpent + $salesSpent;

        // Tier is calculated on THIS YEAR's spend + bonus poeni.
        // We show real spend and bonus separately so nobody thinks the bonus is money.
        $realSpentThisYear = CustomerLoyalty::realSpentThisYear($customer);
        $bonusPoints       = CustomerLoyalty::bonusPoints($customer);
        $tier              = CustomerLoyalty::forSpend($realSpentThisYear + $bonusPoints);

        return view('livewire.customer.dashboard', [
            'customer'          => $customer,
            'orders'            => $orders,
            'counts'            => $counts,
            'totalSpent'        => $totalSpent,
            'realSpentThisYear' => $realSpentThisYear,
            'bonusPoints'       => $bonusPoints,
            'tier'              => $tier,
            'allTiers'          => CustomerLoyalty::TIERS,
        ]);
    }
}
