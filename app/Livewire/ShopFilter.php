<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Perfume;

class ShopFilter extends Component
{
    public array $gender = [];
    public array $price = [];

    public function render()
    {
        $query = Perfume::query();

        if (!empty($this->gender)) {
            $query->whereIn('gender', $this->gender);
        }

        if (!empty($this->price)) {
            $query->whereIn('price', $this->price);
        }

        return view('livewire.shop-filter', [
            'perfumes' => $query->get(),
        ]);
    }
}
