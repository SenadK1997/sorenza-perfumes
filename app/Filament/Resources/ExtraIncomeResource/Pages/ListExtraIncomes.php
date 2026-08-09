<?php

namespace App\Filament\Resources\ExtraIncomeResource\Pages;

use App\Filament\Resources\ExtraIncomeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListExtraIncomes extends ListRecords
{
    protected static string $resource = ExtraIncomeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('Novi prihod'),
        ];
    }
}
