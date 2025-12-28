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


// TODO: customers table i dugovanja
class MyCancelledOrders extends ListRecords
{
    protected static string $resource = OrderResource::class;

    protected function getTableQuery():? Builder
    {
        $query = parent::getTableQuery();

        // Seller only sees orders assigned to them
        if (Auth::user()->hasRole('seller')) {
            $query->where('user_id', Auth::id())
                ->where('status', OrderStatus::CANCELLED->value);
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
                ->action(function ($record) {
                    $record->status = OrderStatus::COMPLETED->value;
                    $record->save();

                    Notification::make()
                        ->title('Order marked as completed')
                        ->success()
                        ->send();
                }),
            Tables\Actions\Action::make('complete')
                ->label('Cancel')
                ->requiresConfirmation()
                ->color('warning')
                ->visible(fn ($record) => Auth::user()->hasRole('seller'))
                ->action(function ($record) {
                    $record->status = OrderStatus::CANCELLED->value;
                    $record->save();

                    Notification::make()
                        ->title('Order marked as cancelled')
                        ->success()
                        ->send();
            }),
        ]);
}
}
