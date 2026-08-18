<?php

namespace App\Filament\Resources\CancelledCustomerResource\Pages;

use App\Filament\Resources\CancelledCustomerResource;
use Filament\Resources\Pages\ListRecords;

class ListCancelledCustomers extends ListRecords
{
    protected static string $resource = CancelledCustomerResource::class;
}
