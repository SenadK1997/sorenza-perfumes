<?php

namespace App\Filament\Resources\ExtraIncomeResource\Pages;

use App\Filament\Resources\ExtraIncomeResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateExtraIncome extends CreateRecord
{
    protected static string $resource = ExtraIncomeResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = Auth::id();
        return $data;
    }
}
