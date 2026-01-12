<?php

namespace App\Filament\Seller\Resources\CancelledPerfumeResource\Pages;

use App\Filament\Seller\Resources\CancelledPerfumeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCancelledPerfume extends EditRecord
{
    protected static string $resource = CancelledPerfumeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
