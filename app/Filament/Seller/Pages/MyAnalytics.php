<?php

namespace App\Filament\Seller\Pages;

use App\Models\SoldPerfume;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;

class MyAnalytics extends Page
{
    protected static ?string $navigationIcon  = 'heroicon-o-sparkles';
    protected static ?string $navigationLabel = 'Moja Analitika';
    protected static ?string $navigationGroup = 'Parfemi';
    protected static ?string $title           = 'Moja Analitika';
    protected static ?int $navigationSort     = -20;
    protected static string $view             = 'filament.seller.pages.my-analytics';

    /** today | 7d | 30d | mtd | ytd | all */
    public string $range = '30d';

    public function setRange(string $key): void
    {
        $this->range = $key;
    }

    protected function currentRange(): array
    {
        $userId = Auth::id();
        return match ($this->range) {
            'today' => ['from' => now()->startOfDay(),             'to' => now()->endOfDay(), 'label' => 'Danas'],
            '7d'    => ['from' => now()->subDays(6)->startOfDay(), 'to' => now()->endOfDay(), 'label' => 'Zadnjih 7 dana'],
            'mtd'   => ['from' => now()->startOfMonth(),           'to' => now()->endOfDay(), 'label' => 'Ovaj mjesec'],
            'ytd'   => ['from' => now()->startOfYear(),            'to' => now()->endOfDay(), 'label' => 'Ova godina'],
            'all'   => [
                'from'  => SoldPerfume::where('user_id', $userId)->min('created_at')
                    ? Carbon::parse(SoldPerfume::where('user_id', $userId)->min('created_at'))->startOfDay()
                    : now()->subYears(3)->startOfDay(),
                'to'    => now()->endOfDay(),
                'label' => 'Sve vrijeme',
            ],
            default => ['from' => now()->subDays(29)->startOfDay(), 'to' => now()->endOfDay(), 'label' => 'Zadnjih 30 dana'],
        };
    }

    protected function previousRange(): array
    {
        $c = $this->currentRange();
        $diff = $c['from']->diffInSeconds($c['to']);
        $to   = $c['from']->copy()->subSecond();
        $from = $to->copy()->subSeconds($diff);
        return ['from' => $from, 'to' => $to];
    }

    protected function baseQuery(Carbon $from, Carbon $to)
    {
        return SoldPerfume::query()
            ->where('sold_perfumes.user_id', Auth::id())
            ->where('sold_perfumes.cancelled', false)
            ->whereBetween('sold_perfumes.created_at', [$from, $to]);
    }

    protected function totals(Carbon $from, Carbon $to): array
    {
        $r = $this->baseQuery($from, $to)
            ->selectRaw('COALESCE(SUM(quantity * base_price), 0) as revenue')
            ->selectRaw('COALESCE(SUM(quantity), 0) as units')
            ->selectRaw('COUNT(*) as sales')
            ->first();

        $customers = (int) $this->baseQuery($from, $to)
            ->whereNotNull('customer_id')
            ->distinct('customer_id')
            ->count('customer_id');

        return [
            'revenue'   => (float) $r->revenue,
            'units'     => (int)   $r->units,
            'sales'     => (int)   $r->sales,
            'customers' => $customers,
        ];
    }

    protected static function delta(float $c, float $p): ?float
    {
        if ($p <= 0) return $c > 0 ? 100.0 : null;
        return (($c - $p) / $p) * 100.0;
    }

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
                'label'   => $d->translatedFormat('d.m'),
                'revenue' => (float) ($rows[$key] ?? 0),
            ];
        }
        return $out;
    }

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
                'name'        => $r->name,
                'inspired_by' => $r->inspired_by,
                'units'       => (int) $r->units,
                'revenue'     => (float) $r->revenue,
            ])
            ->toArray();
    }

    protected function topCustomers(Carbon $from, Carbon $to, int $limit = 5): array
    {
        return $this->baseQuery($from, $to)
            ->join('customers', 'sold_perfumes.customer_id', '=', 'customers.id')
            ->selectRaw('customers.id, customers.full_name, customers.city')
            ->selectRaw('SUM(sold_perfumes.quantity) as units')
            ->selectRaw('SUM(sold_perfumes.quantity * sold_perfumes.base_price) as revenue')
            ->selectRaw('COUNT(*) as sales')
            ->groupBy('customers.id', 'customers.full_name', 'customers.city')
            ->orderByDesc('revenue')
            ->limit($limit)
            ->get()
            ->map(fn ($r) => [
                'name'    => $r->full_name,
                'city'    => $r->city,
                'sales'   => (int)   $r->sales,
                'units'   => (int)   $r->units,
                'revenue' => (float) $r->revenue,
            ])
            ->toArray();
    }

    public function getViewData(): array
    {
        $current  = $this->currentRange();
        $previous = $this->previousRange();
        $tc = $this->totals($current['from'], $current['to']);
        $tp = $this->totals($previous['from'], $previous['to']);
        $series = $this->dailySeries($current['from'], $current['to']);
        $maxRev = collect($series)->max('revenue') ?: 1;
        $avgRev = collect($series)->avg('revenue') ?: 0;

        $kpis = [
            [
                'label' => 'Vaš promet',
                'value' => number_format($tc['revenue'], 2, ',', '.') . ' KM',
                'delta' => static::delta($tc['revenue'], $tp['revenue']),
                'icon'  => 'heroicon-o-banknotes',
                'tone'  => 'primary',
            ],
            [
                'label' => 'Vaši komadi',
                'value' => number_format($tc['units'], 0, ',', '.'),
                'delta' => static::delta($tc['units'], $tp['units']),
                'icon'  => 'heroicon-o-cube',
                'tone'  => 'emerald',
            ],
            [
                'label' => 'Broj prodaja',
                'value' => number_format($tc['sales'], 0, ',', '.'),
                'delta' => static::delta($tc['sales'], $tp['sales']),
                'icon'  => 'heroicon-o-shopping-bag',
                'tone'  => 'indigo',
            ],
            [
                'label' => 'Kupaca',
                'value' => number_format($tc['customers'], 0, ',', '.'),
                'delta' => static::delta($tc['customers'], $tp['customers']),
                'icon'  => 'heroicon-o-user-group',
                'tone'  => 'rose',
            ],
        ];

        return [
            'currentRange' => $current,
            'kpis'         => $kpis,
            'series'       => $series,
            'maxRev'       => $maxRev,
            'avgRev'       => $avgRev,
            'topPerfumes'  => $this->topPerfumes($current['from'], $current['to']),
            'topCustomers' => $this->topCustomers($current['from'], $current['to']),
            'presets'      => [
                'today' => 'Danas',
                '7d'    => '7 dana',
                '30d'   => '30 dana',
                'mtd'   => 'Mjesec',
                'ytd'   => 'Godina',
                'all'   => 'Sve vrijeme',
            ],
        ];
    }
}
