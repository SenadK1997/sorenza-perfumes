<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Perfume;

class ShopFilter extends Component
{
    public array $gender = [];
    public array $price = [];
    public bool $onlyAvailable = false;

    public function render()
    {
        $query = Perfume::query();

        if ($this->onlyAvailable) {
            $query->available();
        }

        if (!empty($this->gender)) {
            $query->whereIn('gender', $this->gender);
        }

        if (!empty($this->price)) {
            $query->whereIn('price', $this->price);
        }

        $hasUnavailable = Perfume::where('availability', false)
            ->where(function($q) {
                $q->whereNull('restock_date')->orWhere('restock_date', '>', now());
            })->exists();

        return view('livewire.shop-filter', [
            'perfumes' => $query->get(),
            'showAvailabilityFilter' => $hasUnavailable,
        ]);
    }
}
