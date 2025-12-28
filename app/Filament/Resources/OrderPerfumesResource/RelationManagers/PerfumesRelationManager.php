<?php

namespace App\Filament\Resources\OrderPerfumesResource\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PerfumesRelationManager extends RelationManager
{
    // The relationship method on Order model
    protected static string $relationship = 'perfumes';

    // The label shown in the Filament UI for this relation manager
    protected static ?string $recordTitleAttribute = 'name';

    // Form for creating/editing pivot records (perfume + quantity + price)
    public function form(Form $form): Form
    {
        return $form->schema([
            // Quantity input on pivot table
            Forms\Components\TextInput::make('pivot.quantity')
                ->label('Quantity')
                ->numeric()
                ->min(1)
                ->required(),

            // Price input on pivot table
            Forms\Components\TextInput::make('pivot.price')
                ->label('Price')
                ->numeric()
                ->required(),
        ]);
    }

    // Table to list related perfumes on the order with quantity and price
    public function table(Table $table): Table
    {
        return $table
            ->columns([
                // Display perfume name from related model
                Tables\Columns\TextColumn::make('name')->label('Perfume'),

                // Display quantity from pivot table
                Tables\Columns\TextColumn::make('pivot.quantity')->label('Quantity'),

                // Display price from pivot table formatted as money
                Tables\Columns\TextColumn::make('pivot.price')->label('Price')->money('BAM'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(), // Allow adding new perfume to order
            ])
            ->actions([
                Tables\Actions\EditAction::make(),  // Edit quantity/price
                Tables\Actions\DeleteAction::make(), // Remove perfume from order
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }
}
