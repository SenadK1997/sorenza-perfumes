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
    public function openQuickPreview($id)
    {
        // Related-perfume cards use this; on the product page, just navigate.
        return redirect()->route('products.show', $id);
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

    protected function relatedPerfumes()
    {
        $current = $this->perfume;

        // Prefer perfumes that share an accord AND same gender; fall back to same gender.
        $currentAccords = collect($current->accords ?? [])
            ->map(fn ($a) => is_array($a) ? ($a['name'] ?? null) : $a)
            ->filter()
            ->values();

        $sameGender = Perfume::query()
            ->where('id', '!=', $current->id)
            ->where('availability', true)
            ->where('gender', $current->gender)
            ->limit(30)
            ->get();

        // Score by accord overlap; if none, fall back to newest same-gender.
        $scored = $sameGender->map(function ($p) use ($currentAccords) {
            $names = collect($p->accords ?? [])
                ->map(fn ($a) => is_array($a) ? ($a['name'] ?? null) : $a)
                ->filter();
            $overlap = $names->intersect($currentAccords)->count();
            return [$p, $overlap];
        })->sortByDesc(fn ($t) => $t[1])
          ->pluck(0)
          ->take(4)
          ->values();

        // Backfill if <4 (small catalog): add newest available across all genders.
        if ($scored->count() < 4) {
            $need = 4 - $scored->count();
            $extra = Perfume::query()
                ->where('id', '!=', $current->id)
                ->whereNotIn('id', $scored->pluck('id')->all())
                ->where('availability', true)
                ->latest()
                ->limit($need)
                ->get();
            $scored = $scored->concat($extra)->values();
        }

        return $scored;
    }

    public function render()
    {
        return view('livewire.product-show', [
            'related' => $this->relatedPerfumes(),
        ]);
    }
}

