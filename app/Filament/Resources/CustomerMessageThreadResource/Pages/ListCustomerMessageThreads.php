<?php

namespace App\Filament\Resources\CustomerMessageThreadResource\Pages;

use App\Filament\Resources\CustomerMessageThreadResource;
use Filament\Resources\Pages\ListRecords;

class ListCustomerMessageThreads extends ListRecords
{
    protected static string $resource = CustomerMessageThreadResource::class;
}
