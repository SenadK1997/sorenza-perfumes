<?php

namespace App\Filament\Seller\Resources\CancelledOrderResource\Pages;

use App\Filament\Seller\Resources\CancelledOrderResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateCancelledOrder extends CreateRecord
{
    protected static string $resource = CancelledOrderResource::class;
}
