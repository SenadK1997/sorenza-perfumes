<?php

namespace App\Filament\Seller\Resources\CompletedOrderResource\Pages;

use App\Filament\Seller\Resources\CompletedOrderResource;
use Filament\Resources\Pages\ListRecords;

class ListCompletedOrders extends ListRecords
{
    protected static string $resource = CompletedOrderResource::class;

    // Pošto su ovo završene narudžbe, ne želimo nikakve akcije osim View-a
    protected function getHeaderActions(): array { return []; }
}