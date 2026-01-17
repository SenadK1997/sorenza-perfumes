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
use App\Services\SellerService;

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
                    ->modalDescription('Označite narudžbu kao uspješno dostavljenu/završenu? Ovo će skinuti parfeme sa stanja i evidentirati vašu zaradu. Nemojte završiti ako niste primili novac od kupca.')
                    ->visible(fn ($record) => auth()->user()->hasAnyRole(['admin', 'seller']))
                    ->action(function (Order $record, \Filament\Actions\StaticAction $action) {
                        $user = auth()->user();

                        // 1. PROVJERA STANJA (Prije bilo kakve izmjene)
                        foreach ($record->perfumes as $perfume) {
                            $orderQty = $perfume->pivot->quantity;

                            // Pronalazimo stanje prodavača za ovaj specifični parfem
                            $pivot = $user->perfumes()
                                ->where('perfume_id', $perfume->id)
                                ->first()
                                ?->pivot;

                            if (! $pivot || $pivot->stock < $orderQty) {
                                Notification::make()
                                    ->title("Nedovoljno stanja za {$perfume->name}")
                                    ->body("Imate " . ($pivot->stock ?? 0) . " komada, a naručeno je {$orderQty}.")
                                    ->danger()
                                    ->send();

                                // Zaustavlja akciju i ostavlja modal otvorenim
                                $action->halt();
                                return;
                            }
                        }

                        // 2. IZVRŠAVANJE PRODAJE (DB Transaction osigurava da sve prođe ili ništa)
                        \Illuminate\Support\Facades\DB::transaction(function () use ($record, $user) {
                            foreach ($record->perfumes as $perfume) {
                                SellerService::recordPerfumeSold(
                                    user: $user,
                                    perfume: $perfume,
                                    quantity: $perfume->pivot->quantity,
                                    isManual: false,
                                    customerId: $record->customer_id
                                );
                            }

                            // Ažuriranje statusa narudžbe tek nakon što je servis odradio svoje
                            $record->update(['status' => OrderStatus::COMPLETED->value]);
                        });

                        Notification::make()
                            ->title('Narudžba uspješno završena!')
                            ->body('Parfemi su skinuti sa stanja i prodaja je evidentirana.')
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