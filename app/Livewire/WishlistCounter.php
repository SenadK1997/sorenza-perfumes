<?php

namespace App\Livewire;

use App\Services\WishlistService;
use Livewire\Attributes\On;
use Livewire\Component;

class WishlistCounter extends Component
{
    #[On('wishlist-updated')]
    public function render()
    {
        return view('livewire.wishlist-counter', [
            'count' => WishlistService::count(),
        ]);
    }
}
