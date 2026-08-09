<?php

namespace App\Filament\Seller\Resources\PerfumeRequestResource\Pages;

use App\Filament\Seller\Resources\PerfumeRequestResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPerfumeRequests extends ListRecords
{
    protected static string $resource = PerfumeRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Novi zahtjev')
                ->icon('heroicon-o-plus'),
        ];
    }
}
