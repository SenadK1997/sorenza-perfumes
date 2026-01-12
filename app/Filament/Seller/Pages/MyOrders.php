<?php

namespace App\Filament\Seller\Pages;

use App\Enums\OrderStatus;
use App\Filament\Seller\Resources\OrderResource;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables;
use App\Services\SellerService;
use Filament\Forms;
use App\Models\Customer;

class MyOrders extends ListRecords
{
    protected static string $resource = OrderResource::class;

    protected function getTableQuery(): ?Builder
    {
        // Koristimo model direktno da izbjegnemo filtere iz Resource-a
        return \App\Models\Order::query()
            ->where('user_id', Auth::id())
            ->where('status', OrderStatus::TAKEN->value);
    }

    public function table(Tables\Table $table): Tables\Table
    {
        return parent::table($table)
            ->actions([
                Tables\Actions\Action::make('leave')
                    ->label('Leave')
                    ->requiresConfirmation()
                    ->color('warning')
                    ->visible(fn ($record) => Auth::user()->hasAnyRole(['admin', 'seller']))
                    ->action(function ($record) {
                        $record->user_id = null;
                        $record->status = OrderStatus::PENDING->value;
                        $record->save();

                        Notification::make()
                            ->title('Order has been left/unassigned')
                            ->success()
                            ->send();
                    }),

                Tables\Actions\Action::make('complete')
                    ->label('Complete')
                    ->requiresConfirmation()
                    ->color('success')
                    ->visible(fn ($record) => Auth::user()->hasAnyRole(['admin', 'seller']))
                    ->before(function ($record) {
                        $user = Auth::user();

                        foreach ($record->perfumes as $perfume) {
                            $orderQty = $perfume->pivot->quantity;

                            // Find seller stock in pivot
                            $pivot = $user->perfumes()
                                ->where('perfume_id', $perfume->id)
                                ->first()
                                ?->pivot;

                            if (! $pivot || $pivot->stock < $orderQty) {
                                Notification::make()
                                    ->title("Not enough stock for {$perfume->name}")
                                    ->danger()
                                    ->send();

                                // Stop the action
                                $this->halt();
                            }
                        }
                    })
                    ->action(function ($record) {
                        $user = Auth::user();

                        // 1. Logika za Kupca: Mapiranje prema tvojoj 'orders' tabeli
                        $customer = Customer::updateOrCreate(
                            ['email' => $record->email], // Koristi 'email' iz tvoje Orders šeme
                            [
                                'full_name'      => $record->full_name,      // Mapirano sa $table->string('full_name')
                                'phone'          => $record->phone,          // Mapirano sa $table->string('phone')
                                'city'           => $record->city,           // Mapirano sa $table->string('city')
                                'canton'         => $record->canton,         // Mapirano sa $table->string('canton')
                                'address_line_1' => $record->address_line_1, // Mapirano sa $table->string('address_line_1')
                                'zipcode'        => $record->zipcode,        // Dodajemo i zipcode za kompletan profil
                                'user_id'        => $user->id,               // Povezujemo kupca sa ovim prodavačem
                            ]
                        );

                        // 2. Markiraj narudžbu kao završenu
                        $record->status = OrderStatus::COMPLETED->value;
                        $record->save();

                        // 3. Skidanje sa stanja i snimanje prodaje (SoldPerfume)
                        foreach ($record->perfumes as $perfume) {
                            SellerService::recordPerfumeSold(
                                $user,
                                $perfume,
                                $perfume->pivot->quantity, // Uzimamo količinu iz pivot tabele narudžbe
                                false,                     // isManual = false (Narudžba sa sajta)
                                $customer->id              // ID upravo kreiranog/nađenog kupca
                            );
                        }

                        Notification::make()
                            ->title('Narudžba uspješno završena')
                            ->success()
                            ->send();
                    }),
                Tables\Actions\Action::make('cancel')
                    ->label('Cancel')
                    ->color('warning')
                    ->visible(fn ($record) => Auth::user()->hasAnyRole(['admin', 'seller']))
                    ->form([
                        Forms\Components\Textarea::make('cancellation_reason')
                            ->label('Cancellation Reason')
                            ->required()
                            ->maxLength(500),
                    ])
                    ->action(function (array $data, $record) {
                        $record->status = OrderStatus::CANCELLED->value;
                        $record->cancellation_reason = $data['cancellation_reason'];
                        $record->save();

                        Notification::make()
                            ->title('Order marked as cancelled')
                            ->success()
                            ->send();
                    }),
            ]);
    }
}
