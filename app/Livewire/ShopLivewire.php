<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Perfume;
use Livewire\Attributes\Layout;

class ShopLivewire extends Component
{
    #[Layout('layouts.shop')]
    public $gender = []; // selected genders
    public $price = [];  // selected prices

    // Query string bound to string, not array
    public $genderString = '';
    public $priceString = '';

    protected $queryString = [
        'genderString' => ['except' => ''],
        'priceString'  => ['except' => ''],
    ];

    public function mount()
    {
        // Initialize arrays from query string
        $this->gender = $this->genderString ? explode(',', $this->genderString) : [];
        $this->price  = $this->priceString ? explode(',', $this->priceString) : [];
    }

    public function updatedGender()
    {
        $this->genderString = implode(',', $this->gender);
    }

    public function updatedPrice()
    {
        $this->priceString = implode(',', $this->price);
    }
    public function render()
    {
        $query = Perfume::query();

        if (!empty($this->gender)) {
            $query->whereIn('gender', $this->gender);
        }

        if (!empty($this->price)) {
            $query->whereIn('price', $this->price);
        }

        $perfumes = $query->get();

        return view('livewire.shop-livewire', [
            'perfumes' => $perfumes
        ]);
    }
}
