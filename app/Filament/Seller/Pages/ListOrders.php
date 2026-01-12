<?php
namespace App\Filament\Seller\Pages;

use Filament\Resources\Pages\ListRecords;
use App\Filament\Seller\Resources\OrderResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;

class ListOrders extends ListRecords
{
    protected static string $resource = OrderResource::class;

    protected function getTableQuery(): Builder
    {
        $query = parent::getTableQuery();

        if (Auth::user()->hasAnyRole('seller')) {
            $query->whereNull('user_id');  // only unassigned orders
        }

        return $query;
    }
}
