<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\Perfume;
use App\Models\Coupon; // Import Coupon model

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
        $subtotal = $this->cartItems->sum(fn($i) => $i->price * $i->quantity);
        $coupon = Coupon::where('code', $this->couponCode)->first();

        if (!$coupon || !$coupon->isValidFor($subtotal)) {
            $this->addError('couponCode', 'Kupon nije validan ili ne ispunjava uslove.');
            return;
        }

        session()->put('coupon', [
            'code' => $coupon->code,
            'discount' => $coupon->calculateDiscount($subtotal)
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
        $subtotal = $this->cartItems->sum(fn($i) => $i->price * $i->quantity);
        $coupon = Coupon::where('code', session('coupon')['code'])->first();
        
        if ($coupon && $coupon->isValidFor($subtotal)) {
            session()->put('coupon.discount', $coupon->calculateDiscount($subtotal));
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
        
        $discount = session()->get('coupon')['discount'] ?? 0;
        
        $shipping = ($subtotal == 0 || $subtotal >= 120) ? 0 : 10;
        $total = ($subtotal - $discount) + $shipping;

        return view('livewire.cart-page', [
            'items' => $items,
            'subtotal' => $subtotal,
            'discount' => $discount,
            'shipping' => $shipping,
            'total' => max(0, $total) // Ensure total isn't negative
        ]);
    }
}