<?php

namespace App\Filament\Seller\Resources;

use App\Filament\Seller\Resources\OrderResource;
use App\Models\Order;
use App\Enums\OrderStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Filament\Tables\Table;
use Filament\Tables\Actions\ViewAction;

class CancelledOrderResource extends OrderResource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-x-circle';
    protected static ?string $navigationLabel = 'Otkazane Narudžbe';
    protected static ?string $pluralModelLabel = 'Otkazane Narudžbe';
    protected static ?string $navigationGroup = 'Narudžbe';
    protected static ?int $navigationSort = 4;

    public static function getEloquentQuery(): Builder
    {
        // Koristimo čist upit da zaobiđemo "whereNull" iz roditelja
        return Order::query()
            ->where('user_id', Auth::id())
            ->where('status', OrderStatus::CANCELLED->value);
    }

    public static function canViewAny(): bool
    {
        return Auth::user()?->hasAnyRole(['admin', 'seller']) ?? false;
    }

    public static function table(Table $table): Table
    {
        return parent::table($table)
            ->actions([
                ViewAction::make()->slideOver(),
            ])
            ->bulkActions([]);
    }

    public static function getPages(): array
    {
        return [
            'index' => CancelledOrderResource\Pages\ListCancelledOrders::route('/'),
        ];
    }
}