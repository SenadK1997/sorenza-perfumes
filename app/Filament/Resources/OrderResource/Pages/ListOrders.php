<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListOrders extends ListRecords
{
    protected static string $resource = OrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    // IZBRISALI SMO getTableQuery() JER NAM VIŠE NE TREBA OVDE

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('Sve narudžbe'),
            
            'available' => Tab::make('Slobodno')
                ->modifyQueryUsing(fn (Builder $query) => $query->whereNull('user_id'))
                ->icon('heroicon-m-hand-raised')
                ->badge(fn () => OrderResource::getEloquentQuery()->whereNull('user_id')->count()),

            'taken' => Tab::make('Preuzeto')
                ->modifyQueryUsing(fn (Builder $query) => $query->whereNotNull('user_id'))
                ->icon('heroicon-m-check-badge')
                ->badge(fn () => OrderResource::getEloquentQuery()->whereNotNull('user_id')->count())
                ->badgeColor('warning'),
        ];
    }
}