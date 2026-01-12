<?php

namespace App\Filament\Seller\Resources\CustomerResource\Pages;

use App\Filament\Seller\Resources\CustomerResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCustomer extends EditRecord
{
    protected static string $resource = CustomerResource::class;


    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\DeleteAction::make()
                ->visible(fn () => auth()->user()->hasRole('admin')),
        ];
    }
}
