<?php

namespace App\Filament\Seller\Resources\CompletedOrderResource\Pages;

use App\Filament\Seller\Resources\CompletedOrderResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateCompletedOrder extends CreateRecord
{
    protected static string $resource = CompletedOrderResource::class;
}
