<?php

namespace App\Filament\Seller\Resources\NoteResource\Pages;

use App\Filament\Seller\Resources\NoteResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateNote extends CreateRecord
{
    protected static string $resource = NoteResource::class;
}
