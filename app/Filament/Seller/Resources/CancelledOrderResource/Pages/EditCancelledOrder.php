<?php

namespace App\Filament\Seller\Resources\CancelledOrderResource\Pages;

use App\Filament\Seller\Resources\CancelledOrderResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCancelledOrder extends EditRecord
{
    protected static string $resource = CancelledOrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
