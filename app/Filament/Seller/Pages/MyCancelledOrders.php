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


class MyCancelledOrders extends ListRecords
{
    protected static string $resource = OrderResource::class;

    protected function getTableQuery(): ?Builder
    {
        // Koristimo model direktno da izbjegnemo filtere iz Resource-a
        return \App\Models\Order::query()
            ->where('user_id', Auth::id())
            ->where('status', OrderStatus::CANCELLED->value);
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
                ->action(function ($record) {
                    $record->status = OrderStatus::COMPLETED->value;
                    $record->save();

                    Notification::make()
                        ->title('Order marked as completed')
                        ->success()
                        ->send();
                }),
        ]);
}
}
