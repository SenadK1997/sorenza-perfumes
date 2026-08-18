<?php

namespace App\Livewire\Customer;

use App\Models\Order;
use App\Models\SoldPerfume;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class OrdersPage extends Component
{
    use WithPagination;

    public const PAGE_SIZE = 10;

    #[Url(as: 'tab', history: true)]
    public string $tab = 'all';

    public function mount(): void
    {
        if (!in_array($this->tab, ['all', 'active', 'completed', 'cancelled'], true)) {
            $this->tab = 'all';
        }
    }

    public function setTab(string $tab): void
    {
        $this->tab = $tab;
        $this->resetPage();
    }

    public function reorderOrder(int $orderId): void
    {
        $customer = Auth::guard('customer')->user();
        $order = Order::with('perfumes')
            ->where('id', $orderId)
            ->where('email', $customer->email)
            ->first();

        if (!$order) return;

        $cart = session()->get('cart', []);
        foreach ($order->perfumes as $perfume) {
            $qty = (int) ($perfume->pivot->quantity ?? 1);
            $cart[$perfume->id] = ($cart[$perfume->id] ?? 0) + $qty;
        }
        session()->put('cart', $cart);
        $this->dispatch('cartUpdated');
        session()->flash('status', 'Artikli iz narudžbe #' . $order->pretty_id . ' su dodani u vašu korpu.');

        $this->redirect('/cart', navigate: true);
    }

    public function reorderSale(int $saleId): void
    {
        $customer = Auth::guard('customer')->user();
        $sale = SoldPerfume::where('id', $saleId)
            ->where('customer_id', $customer->id)
            ->where('is_manual', true)
            ->first();

        if (!$sale || !$sale->perfume_id) return;

        $qty = (int) abs($sale->quantity);
        if ($qty === 0) return;

        $cart = session()->get('cart', []);
        $cart[$sale->perfume_id] = ($cart[$sale->perfume_id] ?? 0) + $qty;
        session()->put('cart', $cart);
        $this->dispatch('cartUpdated');
        session()->flash('status', 'Parfem iz direktne kupovine je dodan u vašu korpu.');

        $this->redirect('/cart', navigate: true);
    }

    private function buildFeed($customer): Collection
    {
        // Online orders — item count is SUM of pivot quantities, not distinct perfumes.
        $orderRows = Order::with(['perfumes' => fn ($q) => $q->select('perfumes.id')])
            ->where('email', $customer->email)
            ->get()
            ->map(fn ($o) => (object) [
                'kind'         => 'order',
                'id'           => $o->id,
                'label'        => '#' . $o->pretty_id,
                'amount'       => (float) $o->amount,
                'items_count'  => (int) $o->perfumes->sum(fn ($p) => (int) $p->pivot->quantity),
                'status_key'   => $o->status->value,
                'status_label' => $o->status->translatedLabel(),
                'created_at'   => $o->created_at,
                'route_key'    => $o->pretty_id,
                'perfume'      => null,
            ]);

        // Direct seller-recorded sales ONLY (is_manual = true).
        // Order-derived rows (is_manual = false) are internal vault accounting and would
        // double-count the online order the customer already sees above.
        $saleRows = SoldPerfume::with('perfume:id,name,inspired_by,main_image,price')
            ->where('customer_id', $customer->id)
            ->where('is_manual', true)
            ->get()
            ->map(function ($s) {
                $qty = (int) abs($s->quantity);
                $unit = $s->customer_price !== null
                    ? (float) $s->customer_price
                    : (float) ($s->perfume?->price ?? 0);
                $amount = $qty * $unit;

                return (object) [
                    'kind'         => 'sale',
                    'id'           => $s->id,
                    'label'        => 'Direktna kupovina · ' . ($s->perfume?->name ?? 'Parfem'),
                    'perfume'      => $s->perfume,
                    'amount'       => $amount,
                    'items_count'  => $qty,
                    'status_key'   => $s->cancelled ? 'cancelled' : 'completed',
                    'status_label' => $s->cancelled ? 'Otkazano' : 'Završeno',
                    'created_at'   => $s->created_at,
                    'route_key'    => null,
                ];
            });

        return $orderRows->concat($saleRows)->sortByDesc('created_at')->values();
    }

    #[Layout('layouts.app')]
    public function render()
    {
        $customer = Auth::guard('customer')->user();
        $feed = $this->buildFeed($customer);

        $counts = [
            'all'       => $feed->count(),
            'active'    => $feed->whereIn('status_key', ['pending', 'taken'])->count(),
            'completed' => $feed->where('status_key', 'completed')->count(),
            'cancelled' => $feed->where('status_key', 'cancelled')->count(),
        ];

        $filtered = match ($this->tab) {
            'active'    => $feed->whereIn('status_key', ['pending', 'taken']),
            'completed' => $feed->where('status_key', 'completed'),
            'cancelled' => $feed->where('status_key', 'cancelled'),
            default     => $feed,
        };

        $page = max(1, (int) $this->getPage());
        $items = $filtered->forPage($page, self::PAGE_SIZE)->values();

        $paginator = new LengthAwarePaginator(
            $items,
            $filtered->count(),
            self::PAGE_SIZE,
            $page,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        return view('livewire.customer.orders', [
            'customer' => $customer,
            'rows'     => $paginator,
            'counts'   => $counts,
        ]);
    }
}
