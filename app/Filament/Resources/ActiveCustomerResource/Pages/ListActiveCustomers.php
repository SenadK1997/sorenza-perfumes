<?php

namespace App\Filament\Resources\ActiveCustomerResource\Pages;

use App\Filament\Resources\ActiveCustomerResource;
use Filament\Resources\Pages\ListRecords;

class ListActiveCustomers extends ListRecords
{
    protected static string $resource = ActiveCustomerResource::class;
}
