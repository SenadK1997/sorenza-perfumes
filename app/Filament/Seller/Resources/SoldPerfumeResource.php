<?php

namespace App\Filament\Seller\Resources;

use App\Filament\Seller\Resources\SoldPerfumeResource\Pages;
use App\Filament\Seller\Resources\SoldPerfumeResource\RelationManagers;
use App\Models\SoldPerfume;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SoldPerfumeResource extends Resource
{
    protected static ?string $model = SoldPerfume::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'My Sold Perfumes';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('perfume.name')
                    ->label('Perfume Name')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('quantity')
                    ->label('Quantity')
                    ->sortable(),

                Tables\Columns\TextColumn::make('base_price')
                    ->label('Base Price')
                    ->money('bam', true),
                Tables\Columns\TextColumn::make('is_manual')
                    ->label('Order')
                    ->formatStateUsing(fn ($state) => $state ? 'Manual' : 'Taken'),

                // Tables\Columns\BooleanColumn::make('cancelled')
                //     ->label('Cancelled'),

                // Tables\Columns\TextColumn::make('cancellation_reason')
                //     ->label('Cancellation Reason'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Sold Date')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            // ->filters([
            //     Tables\Filters\Filter::make('cancelled')
            //         ->label('Cancelled')
            //         ->query(fn (Builder $query) => $query->where('cancelled', true)),
            // ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }


    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('user_id', auth()->id());
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSoldPerfumes::route('/'),
            'create' => Pages\CreateSoldPerfume::route('/create'),
            'edit' => Pages\EditSoldPerfume::route('/{record}/edit'),
        ];
    }
}
