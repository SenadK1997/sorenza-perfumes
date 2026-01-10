<?php
namespace App\Livewire;

use Livewire\Component;
use App\Models\Perfume;
use Livewire\Attributes\Layout;
use Livewire\WithPagination;
use Livewire\Attributes\Url; // Import this

class ShopLivewire extends Component
{
    use WithPagination;
    #[Layout('layouts.app')]
    
    // The #[Url] attribute handles the syncing automatically
    #[Url(as: 'gender', history: true, keep: false)]
    public $gender = []; 

    #[Url(as: 'price', history: true, keep: false)]
    public $price = [];

    #[Url(as: 'dostupno', history: true, keep: false)]
    public bool $onlyAvailable = false;

    public ?Perfume $selectedPerfume = null;
    public bool $showModal = false;

    // Remove the old $queryString protected property
    // Remove the updatedGender() and updatedPrice() logic entirely

    public function mount()
{
    // Force gender to be an array if it comes in as a string from the URL
    if (is_string($this->gender)) {
        $this->gender = explode(',', $this->gender);
    }

    // Force price to be an array
    if (is_string($this->price)) {
        $this->price = explode(',', $this->price);
    }
}

    public function openQuickPreview($perfumeId)
    {
        $this->selectedPerfume = Perfume::find($perfumeId);
        $this->showModal = true;
    }

    public function closeQuickPreview()
    {
        $this->showModal = false;
        $this->selectedPerfume = null;
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
    }

    public function render()
    {
        $query = Perfume::query();

        $query->when($this->gender, fn($q) => $q->whereIn('gender', $this->gender))
              ->when($this->price, fn($q) => $q->whereIn('price', $this->price))
              // Apply your smart scope if the toggle is active
              ->when($this->onlyAvailable, fn($q) => $q->available());

        // We check if any perfumes are currently "Coming Soon" or "Out of Stock"
        // to decide whether to show the filter checkbox in the Blade.
        $hasUnavailable = Perfume::where('availability', false)
            ->where(function($q) {
                $q->whereNull('restock_date')->orWhere('restock_date', '>', now());
            })->exists();

        return view('livewire.shop-livewire', [
            'perfumes' => $query->paginate(12),
            'showAvailabilityFilter' => $hasUnavailable,
        ]);
    }
}