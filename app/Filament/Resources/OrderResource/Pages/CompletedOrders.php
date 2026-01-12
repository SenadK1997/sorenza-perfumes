<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use App\Enums\OrderStatus;

class CompletedOrders extends ListRecords
{
    protected static string $resource = OrderResource::class;
    protected static ?string $title = 'Završene Narudžbe';

    protected function getTableQuery(): ?Builder
    {
        return parent::getTableQuery()->where('status', OrderStatus::COMPLETED->value);
    }
}