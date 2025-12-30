<?php
namespace App\Livewire;

use App\Models\Perfume;
use Livewire\Component;
use Livewire\Attributes\Layout;

class ProductShow extends Component
{
    #[Layout('layouts.shop')]
    public Perfume $perfume;

    public function mount(Perfume $perfume)
    {
        $this->perfume = $perfume;
    }

    public function render()
    {
        return view('livewire.product-show');
    }
}

