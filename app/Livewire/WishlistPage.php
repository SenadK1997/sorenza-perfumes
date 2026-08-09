<?php

namespace App\Livewire;

use App\Services\WishlistService;
use Livewire\Attributes\Layout;
use Livewire\Component;

class WishlistPage extends Component
{
    #[Layout('layouts.app')]

    public function remove(int $perfumeId): void
    {
        WishlistService::remove($perfumeId);
        $this->dispatch('wishlist-updated');
    }

    public function addToCart(int $perfumeId): void
    {
        $cart = session()->get('cart', []);
        $cart[$perfumeId] = ($cart[$perfumeId] ?? 0) + 1;
        session()->put('cart', $cart);
        $this->dispatch('cartUpdated');

        session()->flash('wishlist_msg', 'Dodano u korpu.');
    }

    public function render()
    {
        return view('livewire.wishlist-page', [
            'items' => WishlistService::perfumes(),
        ]);
    }
}
