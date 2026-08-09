<?php

namespace App\Filament\Resources\RefundRequestResource\Pages;

use App\Filament\Resources\RefundRequestResource;
use Filament\Resources\Pages\EditRecord;

class EditRefundRequest extends EditRecord
{
    protected static string $resource = RefundRequestResource::class;

    protected function mutateFormDataBeforeSave(array $data): array
    {
        if (in_array(($data['status'] ?? null), ['approved', 'rejected', 'refunded'])) {
            $data['resolved_at'] = $data['resolved_at'] ?? now();
        }
        return $data;
    }
}
