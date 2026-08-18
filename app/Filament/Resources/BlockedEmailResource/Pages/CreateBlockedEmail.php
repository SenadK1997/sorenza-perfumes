<?php

namespace App\Filament\Resources\BlockedEmailResource\Pages;

use App\Filament\Resources\BlockedEmailResource;
use Filament\Resources\Pages\CreateRecord;

class CreateBlockedEmail extends CreateRecord
{
    protected static string $resource = BlockedEmailResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['blocked_at'] = $data['blocked_at'] ?? now();
        return $data;
    }
}
