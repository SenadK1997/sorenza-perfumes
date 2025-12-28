<?php
namespace App\Filament\Seller\Pages;

use Filament\Resources\Pages\ListRecords;
use App\Filament\Seller\Resources\OrderResource;
use Illuminate\Support\Facades\Auth;
use App\Enums\OrderStatus;
use Illuminate\Database\Eloquent\Builder;

class MyCompletedOrders extends ListRecords
{
    protected static string $resource = OrderResource::class;

    protected function getTableQuery(): ?Builder
    {
        $query = parent::getTableQuery();

        if (Auth::user()->hasRole('seller')) {
            $query->where('user_id', Auth::id())
                  ->where('status', OrderStatus::COMPLETED->value);
        }

        return $query;
    }
}
