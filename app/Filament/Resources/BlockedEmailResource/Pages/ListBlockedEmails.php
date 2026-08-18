<?php

namespace App\Filament\Resources\BlockedEmailResource\Pages;

use App\Filament\Resources\BlockedEmailResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBlockedEmails extends ListRecords
{
    protected static string $resource = BlockedEmailResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\CreateAction::make()->label('Dodaj email')];
    }
}
