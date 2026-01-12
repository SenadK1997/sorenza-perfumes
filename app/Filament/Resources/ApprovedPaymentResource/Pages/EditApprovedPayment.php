<?php

namespace App\Filament\Resources\ApprovedPaymentResource\Pages;

use App\Filament\Resources\ApprovedPaymentResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditApprovedPayment extends EditRecord
{
    protected static string $resource = ApprovedPaymentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
