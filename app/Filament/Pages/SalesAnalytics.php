<?php

namespace App\Filament\Pages;

use App\Enums\Canton;
use App\Models\Order;
use App\Models\Perfume;
use App\Models\SoldPerfume;
use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SalesAnalytics extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-sparkles';
    protected static ?string $navigationLabel = 'Analitika Prodaje';
    protected static ?string $navigationGroup = 'Izvještaji';
    protected static ?string $title = 'Analitika Prodaje';
    protected static ?int $navigationSort = -20;
    protected static string $view = 'filament.pages.sales-analytics';

    /** Preset range key: today | 7d | 30d | mtd | ytd | all | custom */
    public string $range = '30d';
    public ?string $customFrom = null;
    public ?string $customTo   = null;

    /** When true, all admin users are merged into a single "Sorénza (tim)" row. */
    public bool $groupTeam = true;

    /** Optional filters — null = no filter. */
    public ?int    $filterPerfumeId = null;
    public ?int    $filterSellerId  = null;
    public ?string $filterCanton    = null;

    public static function canAccess(): bool
    {
        return Auth::user()?->hasRole('admin') ?? false;
    }

    public function mount(): void
    {
        $this->customFrom = now()->subDays(30)->toDateString();
        $this->customTo   = now()->toDateString();
    }

    public function setRange(string $key): void
    {
        $this->range = $key;
    }

    public function toggleGroupTeam(): void
    {
        $this->groupTeam = ! $this->groupTeam;
    }

    /** IDs of users considered part of the Sorénza team (currently: everyone with 'admin' role). */
    protected function teamUserIds(): array
    {
        return \App\Models\User::role('admin')->pluck('id')->all();
    }

    // ─── Date-range helpers ──────────────────────────────────────────────

    /** @return array{from: Carbon, to: Carbon, label: string} */
    protected function currentRange(): array
    {
        return match ($this->range) {
            'today' => ['from' => now()->startOfDay(),            'to' => now()->endOfDay(), 'label' => 'Danas'],
            '7d'    => ['from' => now()->subDays(6)->startOfDay(),'to' => now()->endOfDay(), 'label' => 'Zadnjih 7 dana'],
            'mtd'   => ['from' => now()->startOfMonth(),          'to' => now()->endOfDay(), 'label' => 'Ovaj mjesec'],
            'ytd'   => ['from' => now()->startOfYear(),           'to' => now()->endOfDay(), 'label' => 'Ova godina'],
            'all'   => [
                'from'  => SoldPerfume::min('created_at')
                    ? Carbon::parse(SoldPerfume::min('created_at'))->startOfDay()
                    : now()->subYears(5)->startOfDay(),
                'to'    => now()->endOfDay(),
                'label' => 'Sve vrijeme',
            ],
            'custom' => [
                'from'  => Carbon::parse($this->customFrom ?: now()->subDays(30))->startOfDay(),
                'to'    => Carbon::parse($this->customTo   ?: now())->endOfDay(),
                'label' => 'Prilagođen period',
            ],
            default => ['from' => now()->subDays(29)->startOfDay(),'to' => now()->endOfDay(), 'label' => 'Zadnjih 30 dana'],
        };
    }

    /** Same-length range immediately preceding current, for delta calculation. */
    protected function previousRange(): array
    {
        $current = $this->currentRange();
        $diff = $current['from']->diffInSeconds($current['to']);
        $to   = $current['from']->copy()->subSecond();
        $from = $to->copy()->subSeconds($diff);
        return ['from' => $from, 'to' => $to];
    }

    // ─── Base query (non-cancelled sales) ────────────────────────────────

    protected function baseQuery(Carbon $from, Carbon $to)
    {
        $q = SoldPerfume::query()
            ->where('sold_perfumes.cancelled', false)
            ->whereBetween('sold_perfumes.created_at', [$from, $to]);

        if ($this->filterPerfumeId) {
            $q->where('sold_perfumes.perfume_id', $this->filterPerfumeId);
        }
        if ($this->filterSellerId) {
            $q->where('sold_perfumes.user_id', $this->filterSellerId);
        }
        if ($this->filterCanton) {
            // Filter through customer.canton (only rows with a linked customer will match)
            $q->whereExists(function ($sub) {
                $sub->select(DB::raw(1))
                    ->from('customers')
                    ->whereColumn('customers.id', 'sold_perfumes.customer_id')
                    ->where('customers.canton', $this->filterCanton);
            });
        }

        return $q;
    }

    public function clearFilters(): void
    {
        $this->filterPerfumeId = null;
        $this->filterSellerId  = null;
        $this->filterCanton    = null;
    }

    public function hasAnyFilter(): bool
    {
        return $this->filterPerfumeId || $this->filterSellerId || $this->filterCanton;
    }

    // ─── Aggregates ──────────────────────────────────────────────────────

    protected function totals(Carbon $from, Carbon $to): array
    {
        $row = $this->baseQuery($from, $to)
            ->selectRaw('COALESCE(SUM(quantity * base_price), 0) as revenue')
            ->selectRaw('COALESCE(SUM(quantity), 0) as units')
            ->selectRaw('COUNT(*) as sales')
            ->first();

        $customers = (int) $this->baseQuery($from, $to)
            ->whereNotNull('customer_id')
            ->distinct('customer_id')
            ->count('customer_id');

        return [
            'revenue'   => (float) $row->revenue,
            'units'     => (int)   $row->units,
            'sales'     => (int)   $row->sales,
            'customers' => $customers,
        ];
    }

    protected static function delta(float $current, float $previous): ?float
    {
        if ($previous <= 0) return $current > 0 ? 100.0 : null;
        return (($current - $previous) / $previous) * 100.0;
    }

    // ─── Time series for the chart (daily buckets) ───────────────────────

    protected function dailySeries(Carbon $from, Carbon $to): array
    {
        $rows = $this->baseQuery($from, $to)
            ->selectRaw('DATE(sold_perfumes.created_at) as d, SUM(sold_perfumes.quantity * sold_perfumes.base_price) as revenue')
            ->groupBy('d')
            ->pluck('revenue', 'd');

        $out = [];
        foreach (CarbonPeriod::create($from->copy()->startOfDay(), $to->copy()->startOfDay()) as $d) {
            $key = $d->toDateString();
            $out[] = [
                'date'    => $key,
                'label'   => $d->translatedFormat('d.m'),
                'revenue' => (float) ($rows[$key] ?? 0),
            ];
        }
        return $out;
    }

    // ─── Leaderboards ────────────────────────────────────────────────────

    protected function topPerfumes(Carbon $from, Carbon $to, int $limit = 5): array
    {
        return $this->baseQuery($from, $to)
            ->join('perfumes', 'sold_perfumes.perfume_id', '=', 'perfumes.id')
            ->selectRaw('perfumes.id, perfumes.name, perfumes.inspired_by')
            ->selectRaw('SUM(sold_perfumes.quantity) as units')
            ->selectRaw('SUM(sold_perfumes.quantity * sold_perfumes.base_price) as revenue')
            ->groupBy('perfumes.id', 'perfumes.name', 'perfumes.inspired_by')
            ->orderByDesc('revenue')
            ->limit($limit)
            ->get()
            ->map(fn ($r) => [
                'id'          => $r->id,
                'name'        => $r->name,
                'inspired_by' => $r->inspired_by,
                'units'       => (int) $r->units,
                'revenue'     => (float) $r->revenue,
            ])
            ->toArray();
    }

    protected function topSellers(Carbon $from, Carbon $to, int $limit = 5): array
    {
        $rows = $this->baseQuery($from, $to)
            ->join('users', 'sold_perfumes.user_id', '=', 'users.id')
            ->selectRaw('users.id, users.name')
            ->selectRaw('SUM(sold_perfumes.quantity) as units')
            ->selectRaw('SUM(sold_perfumes.quantity * sold_perfumes.base_price) as revenue')
            ->groupBy('users.id', 'users.name')
            ->get();

        if ($this->groupTeam) {
            $teamIds = $this->teamUserIds();
            $teamRow = $rows->whereIn('id', $teamIds);
            $external = $rows->whereNotIn('id', $teamIds);

            $merged = collect();
            if ($teamRow->count() > 0 && (float) $teamRow->sum('revenue') > 0) {
                $merged->push([
                    'id'      => 0,
                    'name'    => 'Sorénza (tim)',
                    'units'   => (int) $teamRow->sum('units'),
                    'revenue' => (float) $teamRow->sum('revenue'),
                    'is_team' => true,
                    'members' => $teamRow->count(),
                ]);
            }

            foreach ($external as $r) {
                $merged->push([
                    'id'      => $r->id,
                    'name'    => $r->name,
                    'units'   => (int) $r->units,
                    'revenue' => (float) $r->revenue,
                    'is_team' => false,
                ]);
            }

            return $merged->sortByDesc('revenue')->take($limit)->values()->toArray();
        }

        return $rows->map(fn ($r) => [
            'id'      => $r->id,
            'name'    => $r->name,
            'units'   => (int) $r->units,
            'revenue' => (float) $r->revenue,
            'is_team' => in_array($r->id, $this->teamUserIds(), true),
        ])->sortByDesc('revenue')->take($limit)->values()->toArray();
    }

    // ─── Cohorts (new vs returning by month) ─────────────────────────────

    /**
     * For each month in the current range, count how many orders were placed
     * by first-time buyers vs. returning buyers. "First time" is defined by
     * the first ever order for that email in the whole DB history.
     */
    protected function monthlyCohorts(Carbon $from, Carbon $to): array
    {
        // Build a map: email → first-order timestamp (across all history)
        $firstOrders = Order::query()
            ->selectRaw('LOWER(email) as em, MIN(created_at) as first_at')
            ->whereNotNull('email')
            ->groupBy('em')
            ->pluck('first_at', 'em');

        // Fetch orders in range with just what we need
        $orders = Order::query()
            ->whereBetween('created_at', [$from, $to])
            ->whereNotNull('email')
            ->get(['email', 'amount', 'created_at']);

        // Bucket by year-month
        $buckets = [];
        foreach (CarbonPeriod::create($from->copy()->startOfMonth(), '1 month', $to->copy()->endOfMonth()) as $m) {
            $key = $m->format('Y-m');
            $buckets[$key] = [
                'label'         => $m->translatedFormat('M Y'),
                'new'           => 0,
                'returning'     => 0,
                'new_rev'       => 0.0,
                'returning_rev' => 0.0,
            ];
        }

        foreach ($orders as $o) {
            $key   = $o->created_at->format('Y-m');
            if (! isset($buckets[$key])) continue;
            $em    = strtolower($o->email);
            $first = $firstOrders[$em] ?? null;
            $isNew = $first && Carbon::parse($first)->isSameDay($o->created_at)
                && Carbon::parse($first)->format('Y-m-d H:i:s') === $o->created_at->format('Y-m-d H:i:s')
                || ($first && Carbon::parse($first)->eq($o->created_at));

            // Simpler & stable: "new" if this order IS the first one for that email.
            $isNew = $first && Carbon::parse($first)->equalTo($o->created_at);

            if ($isNew) {
                $buckets[$key]['new']++;
                $buckets[$key]['new_rev'] += (float) $o->amount;
            } else {
                $buckets[$key]['returning']++;
                $buckets[$key]['returning_rev'] += (float) $o->amount;
            }
        }

        return array_values($buckets);
    }

    // ─── View data ───────────────────────────────────────────────────────

    public function getViewData(): array
    {
        $current = $this->currentRange();
        $previous = $this->previousRange();

        $tCurrent  = $this->totals($current['from'], $current['to']);
        $tPrevious = $this->totals($previous['from'], $previous['to']);

        $series = $this->dailySeries($current['from'], $current['to']);
        $maxRev = collect($series)->max('revenue') ?: 1;
        $avgRev = collect($series)->avg('revenue') ?: 0;

        $kpis = [
            [
                'label' => 'Ukupan promet',
                'value' => number_format($tCurrent['revenue'], 2, ',', '.') . ' KM',
                'delta' => static::delta($tCurrent['revenue'], $tPrevious['revenue']),
                'icon'  => 'heroicon-o-banknotes',
                'tone'  => 'primary',
            ],
            [
                'label' => 'Prodanih komada',
                'value' => number_format($tCurrent['units'], 0, ',', '.'),
                'delta' => static::delta($tCurrent['units'], $tPrevious['units']),
                'icon'  => 'heroicon-o-cube',
                'tone'  => 'emerald',
            ],
            [
                'label' => 'Broj prodaja',
                'value' => number_format($tCurrent['sales'], 0, ',', '.'),
                'delta' => static::delta($tCurrent['sales'], $tPrevious['sales']),
                'icon'  => 'heroicon-o-shopping-bag',
                'tone'  => 'indigo',
            ],
            [
                'label' => 'Prosječna prodaja',
                'value' => $tCurrent['sales'] > 0
                    ? number_format($tCurrent['revenue'] / $tCurrent['sales'], 2, ',', '.') . ' KM'
                    : '0,00 KM',
                'delta' => static::delta(
                    $tCurrent['sales']  > 0 ? $tCurrent['revenue']  / $tCurrent['sales']  : 0,
                    $tPrevious['sales'] > 0 ? $tPrevious['revenue'] / $tPrevious['sales'] : 0
                ),
                'icon'  => 'heroicon-o-calculator',
                'tone'  => 'rose',
            ],
        ];

        return [
            'currentRange'  => $current,
            'previousRange' => $previous,
            'kpis'          => $kpis,
            'series'        => $series,
            'maxRev'        => $maxRev,
            'avgRev'        => $avgRev,
            'topPerfumes'   => $this->topPerfumes($current['from'], $current['to']),
            'topSellers'    => $this->topSellers($current['from'], $current['to']),
            'cohorts'       => $this->monthlyCohorts($current['from'], $current['to']),
            'presets'       => [
                'today'  => 'Danas',
                '7d'     => '7 dana',
                '30d'    => '30 dana',
                'mtd'    => 'Mjesec',
                'ytd'    => 'Godina',
                'all'    => 'Sve vrijeme',
                'custom' => 'Prilagođen',
            ],
            'perfumeOptions' => Perfume::orderBy('name')->pluck('name', 'id')->toArray(),
            'sellerOptions'  => User::orderBy('name')->pluck('name', 'id')->toArray(),
            'cantonOptions'  => collect(Canton::cases())->mapWithKeys(fn ($c) => [$c->value => $c->name])->toArray(),
            'anyFilter'      => $this->hasAnyFilter(),
        ];
    }
}
