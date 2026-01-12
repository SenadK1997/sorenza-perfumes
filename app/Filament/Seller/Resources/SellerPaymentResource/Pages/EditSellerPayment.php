<?php

namespace App\Filament\Seller\Resources\SellerPaymentResource\Pages;

use App\Filament\Seller\Resources\SellerPaymentResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSellerPayment extends EditRecord
{
    protected static string $resource = SellerPaymentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
