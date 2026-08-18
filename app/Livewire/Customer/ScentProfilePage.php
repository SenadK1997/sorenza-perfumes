<?php

namespace App\Livewire\Customer;

use App\Models\Order;
use App\Models\Perfume;
use App\Models\SoldPerfume;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

class ScentProfilePage extends Component
{
    public function addToCart(int $productId): void
    {
        $cart = session()->get('cart', []);
        $cart[$productId] = ($cart[$productId] ?? 0) + 1;
        session()->put('cart', $cart);
        $this->dispatch('cartUpdated');
        $this->dispatch('notify', 'Proizvod dodan u korpu!');
    }

    public function openQuickPreview(int $perfumeId): void
    {
        // Quick preview is a shop-page concern; on the profile page we just navigate.
        $this->redirect(route('products.show', $perfumeId), navigate: true);
    }

    #[Layout('layouts.app')]
    public function render()
    {
        $customer = Auth::guard('customer')->user();

        // Build a perfume_id => total_quantity map from BOTH sources, respecting
        // quantity (2× perfume A + 1× perfume B counts as 3 items, not 2).
        $freq = [];

        // Online completed orders — pivot carries the quantity per line.
        Order::where('email', $customer->email)
            ->where('status', 'completed')
            ->with(['perfumes' => fn ($q) => $q->select('perfumes.id')])
            ->get()
            ->each(function ($o) use (&$freq) {
                foreach ($o->perfumes as $p) {
                    $qty = (int) ($p->pivot->quantity ?? 1);
                    $freq[$p->id] = ($freq[$p->id] ?? 0) + $qty;
                }
            });

        // Manual direct sales only (is_manual=true). is_manual=false rows mirror
        // completed orders for vault accounting and would double-count.
        SoldPerfume::where('customer_id', $customer->id)
            ->where('is_manual', true)
            ->where('cancelled', false)
            ->whereNotNull('perfume_id')
            ->get(['perfume_id', 'quantity'])
            ->each(function ($s) use (&$freq) {
                $qty = (int) abs($s->quantity);
                if ($qty === 0) return;
                $freq[$s->perfume_id] = ($freq[$s->perfume_id] ?? 0) + $qty;
            });

        $perfumes = Perfume::whereIn('id', array_keys($freq))->get()->keyBy('id');

        // Accord tally weighted by quantity purchased
        $accordConfig = config('accords', []);
        $accordKeys   = array_keys($accordConfig);
        $accordScores = [];

        // Top inspirations
        $inspirationCount = [];

        foreach ($freq as $pid => $count) {
            $perfume = $perfumes->get($pid);
            if (!$perfume) continue;

            if ($perfume->inspired_by) {
                $inspirationCount[$perfume->inspired_by] = ($inspirationCount[$perfume->inspired_by] ?? 0) + $count;
            }

            foreach ((array) ($perfume->accords ?? []) as $accord) {
                $idx = $accord['name'] ?? null;
                if ($idx === null || !isset($accordKeys[$idx])) continue;
                $name = $accordKeys[$idx];
                $weight = ((float) ($accord['percentage'] ?? 0)) * $count;
                $accordScores[$name] = ($accordScores[$name] ?? 0) + $weight;
            }
        }

        // Normalize accord scores to 0-100
        $accordMax = !empty($accordScores) ? max($accordScores) : 0;
        arsort($accordScores);
        $topAccords = [];
        foreach (array_slice($accordScores, 0, 8, true) as $name => $score) {
            $topAccords[] = [
                'name'  => $name,
                'color' => $accordConfig[$name] ?? '#6366F1',
                'pct'   => $accordMax > 0 ? min(100, ($score / $accordMax) * 100) : 0,
            ];
        }

        arsort($inspirationCount);
        $topInspirations = array_slice($inspirationCount, 0, 5, true);

        // Recommend perfumes: same top accord, not yet purchased
        $recommended = collect();
        if (!empty($topAccords)) {
            $topAccordName = $topAccords[0]['name'];
            $topAccordIdx  = array_search($topAccordName, $accordKeys, true);
            if ($topAccordIdx !== false) {
                $recommended = Perfume::query()
                    ->whereNotIn('id', array_keys($freq) ?: [0])
                    ->visibleInShop()
                    ->get()
                    ->filter(function ($p) use ($topAccordIdx) {
                        foreach ((array) ($p->accords ?? []) as $a) {
                            if (($a['name'] ?? null) == $topAccordIdx) return true;
                        }
                        return false;
                    })
                    ->take(4)
                    ->values();
            }
        }

        $totalItems = array_sum($freq);

        return view('livewire.customer.scent-profile', [
            'customer'        => $customer,
            'totalItems'      => $totalItems,
            'uniquePerfumes'  => count($freq),
            'topAccords'      => $topAccords,
            'topInspirations' => $topInspirations,
            'recommended'     => $recommended,
        ]);
    }
}
