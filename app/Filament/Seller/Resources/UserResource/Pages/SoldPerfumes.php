<?php

namespace App\Filament\Seller\Resources\UserResource\Pages;

use App\Filament\Seller\Resources\UserResource;
use Filament\Actions;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Pages\ManageRelatedRecords;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SoldPerfumes extends ManageRelatedRecords
{
    protected static string $resource = UserResource::class;

    protected static string $relationship = 'soldPerfumes';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function getNavigationLabel(): string
    {
        return 'Sold Perfumes';
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('perfume_id')
                    ->relationship('perfume', 'name')
                    ->required(),
                Forms\Components\TextInput::make('quantity')->required()->numeric()->min(1),
                Forms\Components\TextInput::make('base_price')->required()->numeric()->min(0),
                // Forms\Components\Toggle::make('cancelled')->label('Cancelled'),
                // Forms\Components\Select::make('cancellation_reason')
                //     ->options([
                //         'wrong_perfume' => 'Wrong Perfume',
                //         'broken' => 'Broken',
                //     ])
                //     ->visible(fn ($get) => $get('cancelled') === true),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns([
                Tables\Columns\TextColumn::make('perfume.name')->label('Perfume Name')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('quantity')->sortable(),
                Tables\Columns\TextColumn::make('base_price')->money('bam', true),
                Tables\Columns\TextColumn::make('total_price')->label('Total Price')->getStateUsing(fn($record) => $record->quantity * $record->base_price)->money('bam', true),
                // Tables\Columns\IconColumn::make('cancelled')->boolean(),
                // Tables\Columns\TextColumn::make('cancellation_reason')->sortable(),
                Tables\Columns\TextColumn::make('created_at')->dateTime()->sortable(),
            ])
            
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
                Tables\Actions\AssociateAction::make(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DissociateAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DissociateBulkAction::make(),
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
