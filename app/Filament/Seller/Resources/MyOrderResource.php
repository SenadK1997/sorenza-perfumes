<?php

namespace App\Filament\Seller\Resources;

use App\Filament\Seller\Resources\OrderResource;
use App\Models\Order;
use App\Enums\OrderStatus;
use Filament\Tables\Table;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\ViewAction;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class MyOrderResource extends OrderResource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';
    protected static ?string $navigationLabel = 'Moje Aktivne Narudžbe';
    protected static ?string $pluralModelLabel = 'Moje Aktivne Narudžbe';
    protected static ?string $navigationGroup = 'Narudžbe';
    protected static ?int $navigationSort = 2;

    public static function getEloquentQuery(): Builder
    {
        // Prikazujemo samo narudžbe dodijeljene ulogovanom prodavaču koje su u radu
        return Order::query()
            ->where('user_id', Auth::id())
            ->where('status', OrderStatus::TAKEN->value);
    }

    public static function canViewAny(): bool
    {
        return Auth::user()?->hasAnyRole(['admin', 'seller']) ?? false;
    }

    public static function table(Table $table): Table
    {
        return parent::table($table)
            ->actions([
                Action::make('release')
                    ->label('Vrati na nove')
                    ->color('gray')
                    ->icon('heroicon-m-arrow-uturn-left')
                    ->requiresConfirmation()
                    ->modalHeading('Vrati narudžbu u nove?')
                    ->modalDescription('Ova narudžba će ponovo postati dostupna svim prodavačima.')
                    ->action(function (Order $record) {
                        $record->update([
                            'user_id' => null,
                            'status' => OrderStatus::PENDING->value,
                        ]);

                        Notification::make()
                            ->title('Narudžba vraćena na listu novih.')
                            ->info()
                            ->send();
                    }),
                // Akcija za prebacivanje u ZAVRŠENO
                Action::make('complete')
                    ->label('Završi')
                    ->color('success')
                    ->icon('heroicon-m-check-badge')
                    ->requiresConfirmation()
                    ->modalHeading('Završi narudžbu')
                    ->modalDescription('Označite narudžbu kao uspješno dostavljenu/završenu?')
                    ->action(function (Order $record) {
                        $record->update(['status' => OrderStatus::COMPLETED->value]);
                        Notification::make()
                            ->title('Narudžba uspješno završena!')
                            ->success()
                            ->send();
                    }),

                // Akcija za prebacivanje u OTKAZANO
                Action::make('cancel')
                    ->label('Otkaži')
                    ->color('danger')
                    ->icon('heroicon-m-x-circle')
                    ->requiresConfirmation()
                    ->modalHeading('Otkaži narudžbu')
                    ->modalDescription('Jeste li sigurni da želite otkazati ovu narudžbu?')
                    ->action(function (Order $record) {
                        $record->update(['status' => OrderStatus::CANCELLED->value]);
                        Notification::make()
                            ->title('Narudžba je otkazana.')
                            ->warning()
                            ->send();
                    }),

                ViewAction::make()->slideOver(),
            ])
            ->bulkActions([]);
    }

    public static function getPages(): array
    {
        return [
            'index' => MyOrderResource\Pages\ListMyOrders::route('/'),
        ];
    }
}