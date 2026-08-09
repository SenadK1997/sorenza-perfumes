<?php

namespace App\Livewire;

use App\Services\WishlistService;
use Livewire\Component;

class WishlistButton extends Component
{
    public int $perfumeId;
    public bool $inWishlist = false;
    public string $size = 'md'; // sm | md | lg

    public function mount(int $perfumeId, string $size = 'md'): void
    {
        $this->perfumeId  = $perfumeId;
        $this->size       = $size;
        $this->inWishlist = WishlistService::has($perfumeId);
    }

    public function toggle(): void
    {
        $this->inWishlist = WishlistService::toggle($this->perfumeId);
        $this->dispatch('wishlist-updated');
    }

    public function render()
    {
        return view('livewire.wishlist-button');
    }
}
