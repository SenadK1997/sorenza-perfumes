<?php

namespace App\Filament\Resources\SellerRankingResource\Pages;

use App\Filament\Resources\SellerRankingResource;
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
