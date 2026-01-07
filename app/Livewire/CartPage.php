<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\Perfume;

class CartPage extends Component
{
    #[Layout('layouts.app')] 

    public $showCheckout = false;

    public function getCartItemsProperty()
    {
        $cart = session()->get('cart', []);
        $productIds = array_keys($cart);
        
        // Fetch products and attach quantity from session
        return Perfume::whereIn('id', $productIds)->get()->map(function ($product) use ($cart) {
            $product->quantity = $cart[$product->id];
            return $product;
        });
    }

    public function updateQuantity($id, $qty)
    {
        $cart = session()->get('cart', []);
        if (isset($cart[$id])) {
            $cart[$id] = (int) $qty;
            session()->put('cart', $cart);
        }
    }
    public function goToCheckout()
    {
        if (count($this->items) > 0) {
            $this->showCheckout = true;
        }
    }

    public function removeItem($id)
    {
        $cart = session()->get('cart', []);
        unset($cart[$id]);
        session()->put('cart', $cart);
        
        $this->dispatch('cartUpdated'); // Notify other components (like a navbar counter)
    }

    public function render()
    {
        $items = $this->cartItems;
        $subtotal = $items->sum(fn($i) => $i->price * $i->quantity);

        // FIX: If subtotal is 0 (empty cart), shipping is 0. 
        // Otherwise, check for the 120 KM threshold.
        $shipping = ($subtotal == 0 || $subtotal >= 120) ? 0 : 10;
        
        $total = $subtotal + $shipping;

        return view('livewire.cart-page', [
            'items' => $items,
            'subtotal' => $subtotal,
            'shipping' => $shipping,
            'total' => $total
        ]);
    }
}