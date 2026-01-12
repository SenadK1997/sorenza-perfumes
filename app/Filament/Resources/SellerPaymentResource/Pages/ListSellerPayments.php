<?php

namespace App\Filament\Resources\SellerPaymentResource\Pages;

use App\Filament\Resources\SellerPaymentResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSellerPayments extends ListRecords
{
    protected static string $resource = SellerPaymentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
