<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PerfumeResource\Pages;
use App\Filament\Resources\PerfumeResource\RelationManagers;
use App\Models\Perfume;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PerfumeResource extends Resource
{
    protected static ?string $model = Perfume::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('name')
                ->required(),

            Forms\Components\TextInput::make('base_price')
                ->required()
                ->numeric(),

            Forms\Components\TextInput::make('price')
                ->required()
                ->numeric(),

            Forms\Components\TextInput::make('discount_percentage')
                ->numeric()
                ->minValue(0)
                ->maxValue(100)
                ->nullable(),

            Forms\Components\Textarea::make('description')
                ->nullable(),

            Forms\Components\FileUpload::make('main_image')
                ->label('Main Image')
                ->image()
                ->directory('perfumes')
                ->disk('public')
                ->visibility('public')
                ->preserveFilenames()
                ->nullable(),

            Forms\Components\FileUpload::make('secondary_image')
                ->label('Secondary Image')
                ->image()
                ->directory('perfumes')
                ->disk('public')
                ->visibility('public')
                ->preserveFilenames()
                ->nullable(),

            Forms\Components\TextInput::make('tag')
                ->nullable(),

            Forms\Components\Repeater::make('accords')
                ->label('Accords')
                ->schema([
                    Forms\Components\Select::make('name')
                        ->label('Accord')
                        ->options(array_keys(config('accords')))
                        ->required(),

                    Forms\Components\TextInput::make('percentage')
                        ->numeric()
                        ->required()
                        ->minValue(0)
                        ->maxValue(100),
                ])
                ->columns(2)
                ->nullable(),
        ]);
    }


    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->sortable(),
                Tables\Columns\TextColumn::make('name')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('price')->money('eur', true),
                Tables\Columns\TextColumn::make('discount_percentage')->label('Discount %'),
                Tables\Columns\TextColumn::make('tag')->limit(20),
                Tables\Columns\ImageColumn::make('main_image')->label('Image')->circular(),
                Tables\Columns\TextColumn::make('created_at')->dateTime()->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(), // Optional: adds "view" button
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPerfumes::route('/'),
            'create' => Pages\CreatePerfume::route('/create'),
            'edit' => Pages\EditPerfume::route('/{record}/edit'),
        ];
    }
}
