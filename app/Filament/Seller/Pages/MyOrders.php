<?php

namespace App\Filament\Seller\Pages;

use App\Enums\OrderStatus;
use App\Filament\Resources\OrderResource;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables;
use App\Services\SellerService;
use Filament\Forms;

class MyOrders extends ListRecords
{
    protected static string $resource = OrderResource::class;

    protected function getTableQuery():? Builder
    {
        $query = parent::getTableQuery();

        // Seller only sees orders assigned to them
        if (Auth::user()->hasRole('seller')) {
            $query->where('user_id', Auth::id())
                ->where('status', OrderStatus::TAKEN->value);
        }

        return $query;
    }

    public function table(Tables\Table $table): Tables\Table
    {
        return parent::table($table)
            ->actions([
                Tables\Actions\Action::make('leave')
                    ->label('Leave')
                    ->requiresConfirmation()
                    ->color('warning')
                    ->visible(fn ($record) => Auth::user()->hasRole('seller'))
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
                    ->visible(fn ($record) => Auth::user()->hasRole('seller'))
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
                        $record->status = OrderStatus::COMPLETED->value;
                        $record->save();

                        $user = Auth::user();

                        foreach ($record->perfumes as $perfume) {
                            SellerService::recordPerfumeSold(
                                $user,
                                $perfume,
                                $perfume->pivot->quantity, // pass actual quantity
                                false
                            );
                        }

                        Notification::make()
                            ->title('Order marked as completed')
                            ->success()
                            ->send();
                    }),
                Tables\Actions\Action::make('cancel')
                    ->label('Cancel')
                    ->color('warning')
                    ->visible(fn ($record) => Auth::user()->hasRole('seller'))
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
