<?php

namespace App\Filament\Seller\Resources\CancelledPerfumeResource\Pages;

use App\Filament\Seller\Resources\CancelledPerfumeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCancelledPerfumes extends ListRecords
{
    protected static string $resource = CancelledPerfumeResource::class;

    protected function getHeaderActions(): array
    {
        return []; // Prazno polje uklanja "New" dugme
    }
}
