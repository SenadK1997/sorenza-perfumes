<?php

namespace App\Filament\Resources\OrderPerfumesResource\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Tables;
use Filament\Tables\Table;

class PerfumesRelationManager extends RelationManager
{
    protected static string $relationship = 'perfumes';
    protected static ?string $recordTitleAttribute = 'name';
    protected static ?string $title = 'Artikli u narudžbi';

    public function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('recordId')
                ->label('Parfem')
                ->relationship('perfume', 'name')
                ->searchable()
                ->preload()
                ->required()
                ->visibleOn('create'),

            Forms\Components\TextInput::make('quantity')
                ->label('Količina')
                ->numeric()
                ->minValue(1)
                ->required()
                ->default(1),

            Forms\Components\TextInput::make('price')
                ->label('Cijena po komadu (KM)')
                ->helperText('Cijena po jedinici koja je stvarno naplaćena kupcu za ovaj artikal.')
                ->numeric()
                ->step('any')
                ->minValue(0)
                ->prefix('KM')
                ->required(),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\ImageColumn::make('main_image')
                    ->label('')
                    ->square()
                    ->getStateUsing(fn ($record) => $record->main_image),

                Tables\Columns\TextColumn::make('name')
                    ->label('Parfem')
                    ->description(fn ($record) => $record->inspired_by ?? '')
                    ->searchable(),

                Tables\Columns\TextColumn::make('pivot.quantity')
                    ->label('Količina')
                    ->alignCenter(),

                Tables\Columns\TextColumn::make('pivot.price')
                    ->label('Cijena/kom')
                    ->money('bam'),

                Tables\Columns\TextColumn::make('line_total')
                    ->label('Ukupno linija')
                    ->money('bam')
                    ->state(fn ($record) => (float) $record->pivot->price * (int) $record->pivot->quantity)
                    ->weight('bold')
                    ->color('success'),
            ])
            ->headerActions([
                Tables\Actions\AttachAction::make()
                    ->preloadRecordSelect()
                    ->label('Dodaj parfem')
                    ->form(fn (Tables\Actions\AttachAction $action): array => [
                        $action->getRecordSelect(),
                        Forms\Components\TextInput::make('quantity')
                            ->label('Količina')
                            ->numeric()->minValue(1)->required()->default(1),
                        Forms\Components\TextInput::make('price')
                            ->label('Cijena po komadu (KM)')
                            ->numeric()->step('any')->minValue(0)->prefix('KM')->required(),
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->label('Uredi')
                    ->form([
                        Forms\Components\TextInput::make('quantity')
                            ->label('Količina')
                            ->numeric()->minValue(1)->required(),
                        Forms\Components\TextInput::make('price')
                            ->label('Cijena po komadu (KM)')
                            ->numeric()->step('any')->minValue(0)->prefix('KM')->required(),
                    ]),
                Tables\Actions\DetachAction::make()->label('Ukloni'),
            ])
            ->bulkActions([
                Tables\Actions\DetachBulkAction::make()->label('Ukloni označene'),
            ]);
    }
}
