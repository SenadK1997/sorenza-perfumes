<?php

namespace App\Filament\Seller\Resources\SellerRankingResource\Pages;

use App\Filament\Seller\Resources\SellerRankingResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewSellerRanking extends ViewRecord
{
    protected static string $resource = SellerRankingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
