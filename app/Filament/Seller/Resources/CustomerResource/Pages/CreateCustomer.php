<?php

namespace App\Filament\Seller\Resources\CustomerResource\Pages;

use App\Filament\Seller\Resources\CustomerResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateCustomer extends CreateRecord
{
    protected static string $resource = CustomerResource::class;
}
