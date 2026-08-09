<?php

namespace App\Filament\Seller\Widgets;

use App\Models\Perfume;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;

class MyStockStats extends BaseWidget
{
    protected static ?int $sort = -10;

    protected function getStats(): array
    {
        $userId = Auth::id();

        $inStock = Perfume::where('availability', true)
            ->whereHas('sellers', function ($q) use ($userId) {
                $q->where('user_id', $userId)->where('perfume_seller.stock', '>', 0);
            })
            ->with(['sellers' => fn ($q) => $q->where('user_id', $userId)])
            ->get();

        $uniqueInStock  = $inStock->count();
        $catalogTotal   = Perfume::where('availability', true)->count();
        $totalUnits     = $inStock->sum(fn ($p) => (int) ($p->sellers->first()?->pivot->stock ?? 0));
        $inventoryValue = $inStock->sum(fn ($p) => (float) ($p->base_price ?? 0) * (int) ($p->sellers->first()?->pivot->stock ?? 0));

        return [
            Stat::make('Različitih parfema', "{$uniqueInStock}/{$catalogTotal}")
                ->description('Vaš izbor od ukupno u katalogu')
                ->descriptionIcon('heroicon-m-sparkles')
                ->color('primary'),

            Stat::make('Ukupno komada', $totalUnits)
                ->description('Svi flakoni zajedno')
                ->descriptionIcon('heroicon-m-cube')
                ->color('success'),

            Stat::make('Vrijednost lagera', number_format($inventoryValue, 2, ',', '.') . ' KM')
                ->description('Ukupna nabavna vrijednost')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('warning'),
        ];
    }
}
