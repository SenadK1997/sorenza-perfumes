<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\Perfume;
use App\Models\Coupon;
use App\Models\SiteSetting;
use App\Services\ShippingCalculator;
use App\Services\CartTierDiscount;
use App\Services\CustomerLoyalty;

class CartPage extends Component
{
    #[Layout('layouts.app')] 

    public $showCheckout = false;
    public $couponCode = ''; // Input field binding

    public function getCartItemsProperty()
    {
        $cart = session()->get('cart', []);
        $productIds = array_keys($cart);
        
        return Perfume::whereIn('id', $productIds)->get()->map(function ($product) use ($cart) {
            $product->quantity = $cart[$product->id];
            return $product;
        });
    }

    public function applyCoupon()
    {
        $items = $this->cartItems;
        $coupon = Coupon::where('code', $this->couponCode)->first();

        if (!$coupon || !$coupon->isValidForCart($items)) {
            $msg = 'Kupon nije validan ili ne ispunjava uslove.';
            if ($coupon && $coupon->hasPerfumeRestriction() && $coupon->eligibleSubtotal($items) <= 0) {
                $msg = 'Ovaj kupon važi samo za određene parfeme kojih trenutno nema u vašoj korpi.';
            }
            $this->addError('couponCode', $msg);
            return;
        }

        session()->put('coupon', [
            'code' => $coupon->code,
            'discount' => $coupon->calculateCartDiscount($items),
        ]);

        $this->couponCode = ''; // Clear input
    }

    public function removeCoupon()
    {
        session()->forget('coupon');
    }

    public function updateQuantity($id, $qty)
    {
        $cart = session()->get('cart', []);
        if (isset($cart[$id])) {
            $cart[$id] = (int) $qty;
            session()->put('cart', $cart);
            
            // Re-calculate coupon if one is applied
            if (session()->has('coupon')) {
                $this->applyCouponOnUpdate();
            }
        }
    }

    protected function applyCouponOnUpdate()
    {
        $items = $this->cartItems;
        $coupon = Coupon::where('code', session('coupon')['code'])->first();

        if ($coupon && $coupon->isValidForCart($items)) {
            session()->put('coupon.discount', $coupon->calculateCartDiscount($items));
        } else {
            session()->forget('coupon');
        }
    }

    public function removeItem($id)
    {
        $cart = session()->get('cart', []);
        unset($cart[$id]);
        session()->put('cart', $cart);
        
        if (session()->has('coupon')) {
            $this->applyCouponOnUpdate();
        }

        $this->dispatch('cartUpdated');
    }

    public function render()
    {
        $items = $this->cartItems;
        $subtotal = $items->sum(fn($i) => $i->price * $i->quantity);

        $couponDiscount = session()->get('coupon')['discount'] ?? 0;
        $tierDiscount   = CartTierDiscount::discount((float) $subtotal);

        // Loyalty discount for logged-in customers (% of subtotal by tier)
        $customer         = auth('customer')->user();
        $loyaltyTier      = $customer ? CustomerLoyalty::forCustomer($customer) : null;
        $loyaltyDiscount  = $customer ? CustomerLoyalty::discountAmountFor($customer, (float) $subtotal) : 0.0;

        $discount = $couponDiscount + $tierDiscount + $loyaltyDiscount;

        $shipping = ShippingCalculator::fee($subtotal);
        $total = ($subtotal - $discount) + $shipping;

        return view('livewire.cart-page', [
            'items' => $items,
            'subtotal' => $subtotal,
            'discount' => $discount,
            'couponDiscount' => $couponDiscount,
            'tierDiscount' => $tierDiscount,
            'loyaltyDiscount' => $loyaltyDiscount,
            'loyaltyTier' => $loyaltyTier,
            'tierEnabled' => CartTierDiscount::enabled(),
            'tierEarned' => CartTierDiscount::earned((float) $subtotal),
            'tierNext' => CartTierDiscount::next((float) $subtotal),
            'tierAll' => CartTierDiscount::tiers(),
            'shipping' => $shipping,
            'total' => max(0, $total),
            'alwaysFree' => ShippingCalculator::alwaysFree(),
            'freeShippingEnabled' => ShippingCalculator::freeShippingEnabled(),
            'freeShippingThreshold' => ShippingCalculator::threshold(),
            'amountToFree' => ShippingCalculator::amountToFreeShipping($subtotal),
            'qualifiesForFree' => ShippingCalculator::qualifiesForFree($subtotal),
            'shippingLabel' => ShippingCalculator::summaryLabel(),
            'refundDays' => ShippingCalculator::refundDays(),
        ]);
    }
}