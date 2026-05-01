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

        // 1. GLOBAL VISIBILITY: Use the scope we just created
        $query->visibleInShop();

        // 2. FILTERS
        $query->when($this->gender, fn($q) => $q->whereIn('gender', $this->gender))
            ->when($this->price, fn($q) => $q->whereIn('price', $this->price))
            
            // If "Prikaži dostupne" is checked, show ONLY items currently in stock
            ->when($this->onlyAvailable, fn($q) => $q->where('availability', true));

        // 3. FILTER CHECKBOX VISIBILITY
        // Determine if we show the "Only Available" toggle
        $hasComingSoon = Perfume::where('availability', false)
            ->whereNotNull('restock_date')
            ->where('restock_date', '>', now())
            ->exists();

        return view('livewire.shop-livewire', [
            'perfumes' => $query->paginate(12),
            'showAvailabilityFilter' => $hasComingSoon,
        ]);
    }
}