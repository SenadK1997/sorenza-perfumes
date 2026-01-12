<?php

namespace App\Filament\Seller\Resources\SellerRankingResource\Pages;

use App\Filament\Seller\Resources\SellerRankingResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSellerRanking extends EditRecord
{
    protected static string $resource = SellerRankingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
