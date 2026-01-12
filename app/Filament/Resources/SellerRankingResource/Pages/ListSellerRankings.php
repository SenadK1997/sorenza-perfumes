<?php

namespace App\Filament\Resources\SellerRankingResource\Pages;

use App\Filament\Resources\SellerRankingResource;
use Filament\Resources\Pages\ListRecords;

class ListSellerRankings extends ListRecords
{
    protected static string $resource = SellerRankingResource::class;

    // Isključujemo paginaciju jer groupBy i pagination često prave SQL greške 
    // osim ako ne koristiš kompleksne join-ove. Za rang listu je bolje vidjeti sve na jednoj strani.
    protected function isTablePaginationEnabled(): bool
    {
        return false;
    }
}