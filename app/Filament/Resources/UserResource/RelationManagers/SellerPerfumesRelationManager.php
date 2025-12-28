<?php

namespace App\Filament\Resources\UserResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\AttachAction;
use Filament\Tables\Actions\DetachAction;
use Filament\Tables\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;

class SellerPerfumesRelationManager extends RelationManager
{
    protected static string $relationship = 'perfumes';
    protected static ?string $title = 'Assigned Perfumes';

    public function table(Tables\Table $table): Tables\Table
    {
        return $table
        ->columns([
            TextColumn::make('name')->label('Perfume'),  
            TextColumn::make('pivot.stock')->label('Stock'),
        ])
            ->headerActions([
                AttachAction::make()
                    ->form([
                        Forms\Components\Select::make('recordId')
                            ->label('Perfume')
                            ->options(\App\Models\Perfume::all()->pluck('name', 'id'))
                            ->searchable()
                            ->required(),
            
                        TextInput::make('stock')
                            ->label('Stock')
                            ->numeric()
                            ->required()
                            ->default(1),
                    ]),
            ])
            ->actions([
                EditAction::make(),
                DetachAction::make(),
            ]);
    }

}
