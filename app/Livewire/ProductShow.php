<?php
namespace App\Livewire;

use App\Models\Perfume;
use Livewire\Component;
use Livewire\Attributes\Layout;

class ProductShow extends Component
{
    #[Layout('layouts.app')]
    public Perfume $perfume;

    public function mount(Perfume $perfume)
    {
        $this->perfume = $perfume;
    }
    public function addToCart($productId)
    {
        $cart = session()->get('cart', []);

        // If item exists, increment; otherwise set to 1
        if(isset($cart[$productId])) {
            $cart[$productId]++;
        } else {
            $cart[$productId] = 1;
        }

        session()->put('cart', $cart);
        $this->dispatch('cartUpdated');

        $this->dispatch('notify', 'Proizvod dodan u korpu!');
        
        // Optional: redirect to cart or show a notification
        // return redirect()->to('/cart');
    }

    public function render()
    {
        return view('livewire.product-show');
    }
}

