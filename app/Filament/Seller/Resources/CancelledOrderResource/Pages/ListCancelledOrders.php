<?php

namespace App\Filament\Seller\Resources\CancelledOrderResource\Pages;

use App\Filament\Seller\Resources\CancelledOrderResource;
use Filament\Resources\Pages\ListRecords;

class ListCancelledOrders extends ListRecords
{
    protected static string $resource = CancelledOrderResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}