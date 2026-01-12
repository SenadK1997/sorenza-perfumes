<?php

namespace App\Filament\Resources\ApprovedPaymentResource\Pages;

use App\Filament\Resources\ApprovedPaymentResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateApprovedPayment extends CreateRecord
{
    protected static string $resource = ApprovedPaymentResource::class;
}
