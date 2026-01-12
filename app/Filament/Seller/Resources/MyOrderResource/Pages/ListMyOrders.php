<?php

namespace App\Filament\Seller\Resources\MyOrderResource\Pages;

use App\Filament\Seller\Resources\MyOrderResource;
use Filament\Resources\Pages\ListRecords;

class ListMyOrders extends ListRecords
{
    protected static string $resource = MyOrderResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}