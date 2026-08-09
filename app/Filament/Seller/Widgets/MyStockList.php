<?php

namespace App\Filament\Seller\Widgets;

use App\Models\Perfume;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class MyStockList extends BaseWidget
{
    protected static ?string $heading = '💎 Moj lager parfema';
    protected static ?int $sort = -5;
    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        $userId = Auth::id();

        return $table
            ->query(
                Perfume::query()
                    ->select('perfumes.*')
                    ->join('perfume_seller', 'perfume_seller.perfume_id', '=', 'perfumes.id')
                    ->where('perfume_seller.user_id', $userId)
                    ->where('perfume_seller.stock', '>', 0)
                    ->where('perfumes.availability', true)
                    ->orderBy('perfume_seller.stock', 'asc')
            )
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Parfem')
                    ->weight('bold')
                    ->description(fn (Perfume $r) => $r->inspired_by)
                    ->searchable(['name', 'inspired_by']),

                Tables\Columns\TextColumn::make('stock')
                    ->label('Na stanju')
                    ->alignEnd()
                    ->badge()
                    ->getStateUsing(function (Perfume $r) use ($userId) {
                        $pivot = $r->sellers()->where('user_id', $userId)->first()?->pivot;
                        return (int) ($pivot->stock ?? 0);
                    })
                    ->formatStateUsing(fn ($state) => $state . ' kom')
                    ->color(fn ($state) => match (true) {
                        $state <= 2 => 'danger',
                        $state <= 5 => 'warning',
                        default     => 'success',
                    }),
            ])
            ->paginated([10, 25, 50, 'all'])
            ->defaultPaginationPageOption(10)
            ->emptyStateHeading('Nemate parfema na stanju')
            ->emptyStateDescription('Kada Vam se dodijeli lager, pojaviti će se ovdje.')
            ->emptyStateIcon('heroicon-o-cube');
    }
}
