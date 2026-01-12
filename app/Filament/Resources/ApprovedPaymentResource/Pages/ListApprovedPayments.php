<?php

namespace App\Filament\Resources\ApprovedPaymentResource\Pages;

use App\Filament\Resources\ApprovedPaymentResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListApprovedPayments extends ListRecords
{
    protected static string $resource = ApprovedPaymentResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
