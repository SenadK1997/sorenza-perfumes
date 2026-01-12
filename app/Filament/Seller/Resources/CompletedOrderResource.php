<?php

namespace App\Filament\Seller\Resources;

use App\Filament\Seller\Resources\OrderResource;
use App\Models\Order;
use App\Enums\OrderStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class CompletedOrderResource extends OrderResource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-check-circle';
    protected static ?string $navigationLabel = 'Završene Narudžbe';
    protected static ?string $pluralModelLabel = 'Završene Narudžbe';
    protected static ?string $navigationGroup = 'Narudžbe';
    protected static ?int $navigationSort = 3;

    public static function getEloquentQuery(): Builder
    {
        // VAŽNO: Koristimo Order::query() umjesto parent::getEloquentQuery()
        // da bismo izbjegli "whereNull('user_id')" i "status = PENDING" iz roditelja.
        return Order::query()
            ->where('user_id', Auth::id())
            ->where('status', OrderStatus::COMPLETED->value);
    }

    public static function canViewAny(): bool
    {
        return Auth::user()?->hasAnyRole(['admin', 'seller']) ?? false;
    }

    // Isključujemo sve akcije jer su narudžbe završene, ostavljamo samo View
    public static function table(\Filament\Tables\Table $table): \Filament\Tables\Table
    {
        return parent::table($table)
            ->actions([
                \Filament\Tables\Actions\ViewAction::make()->slideOver(),
            ])
            ->bulkActions([]);
    }

    public static function getPages(): array
    {
        return [
            'index' => CompletedOrderResource\Pages\ListCompletedOrders::route('/'),
        ];
    }
}