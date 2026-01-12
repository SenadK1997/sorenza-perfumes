<?php

namespace App\Filament\Seller\Resources\SellerRankingResource\Pages;

use App\Filament\Seller\Resources\SellerRankingResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSellerRankings extends ListRecords
{
    protected static string $resource = SellerRankingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
    protected function isTablePaginationEnabled(): bool
    {
        return false;
    }
}
