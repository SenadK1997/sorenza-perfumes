<?php

namespace App\Filament\Seller\Resources\SoldPerfumeResource\Pages;

use App\Filament\Seller\Resources\SoldPerfumeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSoldPerfumes extends ListRecords
{
    protected static string $resource = SoldPerfumeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
