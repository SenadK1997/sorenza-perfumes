<?php

namespace App\Filament\Seller\Resources\CompletedOrderResource\Pages;

use App\Filament\Seller\Resources\CompletedOrderResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCompletedOrder extends EditRecord
{
    protected static string $resource = CompletedOrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
