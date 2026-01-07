<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;

class CartCounter extends Component
{
    #[On('cartUpdated')] 
    public function render()
    {
        $count = count(session()->get('cart', []));
        return view('livewire.cart-counter', [
            'count' => $count
        ]);
    }
}