<?php

namespace App\Filament\Resources;

use App\Enums\PerfumeGender;
use App\Filament\Resources\PerfumeResource\Pages;
use App\Models\Perfume;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Grid;

class PerfumeResource extends Resource
{
    protected static ?string $model = Perfume::class;
    protected static ?string $navigationIcon = 'heroicon-o-sparkles';
    protected static ?string $navigationLabel = 'Parfemi';

    public static function form(Form $form): Form
    {
        return $form->schema([
            // SEKCIJA 1: OSNOVNI PODACI
            Section::make('Osnovne Informacije')
                ->schema([
                    Grid::make(2)->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Naziv Parfema')
                            ->required(),

                        Forms\Components\TextInput::make('inspired_by')
                            ->label('Inspirisan sa')
                            ->placeholder('npr. Armani Si'),
                    ]),

                    Grid::make(3)->schema([
                        Forms\Components\Select::make('gender')
                            ->label('Pol')
                            ->options(PerfumeGender::class)
                            ->required()
                            ->native(false),

                        Forms\Components\TextInput::make('tag')
                            ->label('Tag')
                            ->placeholder('npr. Novo'),

                        Forms\Components\Toggle::make('availability')
                            ->label('Dostupno')
                            ->default(true),

                        Forms\Components\Toggle::make('is_bestseller')
                            ->label('Najprodavaniji (Bestseller)')
                            ->helperText('Prikaži ovaj parfem u sekciji "Najprodavaniji" na naslovnoj strani.')
                            ->default(false)
                            ->visible(fn () => auth()->user()?->hasRole('admin')),
                    ]),

                    Forms\Components\RichEditor::make('description')
                        ->label('Opis')
                        ->columnSpanFull(),
                ])->collapsible(),

            // SEKCIJA 2: CIJENE (LOGIKA)
            Section::make('Cijene i Popust')
                ->description('Nabavna je interna cijena. Regularna je precrtana na sajtu. Akcijska je prodajna cijena — postavi jednaku kao regularna ako nema popusta.')
                ->schema([
                    Grid::make(2)->schema([
                        Forms\Components\TextInput::make('base_price')
                            ->label('Nabavna cijena (interno)')
                            ->numeric()
                            ->prefix('KM')
                            ->required(),

                        Forms\Components\DatePicker::make('restock_date')
                            ->label('Datum dopune')
                            ->native(false),
                    ]),

                    Grid::make(3)->schema([
                        Forms\Components\TextInput::make('original_price')
                            ->label('Regularna cijena')
                            ->helperText('Precrtana cijena na sajtu kad ima akcije.')
                            ->numeric()
                            ->prefix('KM')
                            ->required()
                            ->live(onBlur: true)
                            ->afterStateUpdated(function (Get $get, Set $set) {
                                self::syncDiscountPercentage($get, $set);
                            }),

                        Forms\Components\TextInput::make('price')
                            ->label('Akcijska cijena')
                            ->helperText('Konačna cijena koju kupac plaća.')
                            ->numeric()
                            ->prefix('KM')
                            ->required()
                            ->live(onBlur: true)
                            ->afterStateUpdated(function (Get $get, Set $set) {
                                self::syncDiscountPercentage($get, $set);
                            }),

                        Forms\Components\TextInput::make('discount_percentage')
                            ->label('Popust %')
                            ->helperText('Automatski se računa. Ostavi 0 ako nema popusta.')
                            ->numeric()
                            ->suffix('%')
                            ->default(0)
                            ->live(onBlur: true)
                            ->afterStateUpdated(function (Get $get, Set $set) {
                                self::syncPriceFromDiscount($get, $set);
                            }),
                    ]),
                ])->collapsible(),

            // SEKCIJA 3: SLIKE I NOTE
            Section::make('Slike i Note')
                ->schema([
                    Grid::make(2)->schema([
                        Forms\Components\FileUpload::make('main_image')
                            ->label('Glavna Slika')
                            ->image()
                            ->directory('perfumes'),

                        Forms\Components\FileUpload::make('secondary_image')
                            ->label('Druga Slika')
                            ->image()
                            ->directory('perfumes'),
                    ]),

                    Forms\Components\Repeater::make('accords')
    ->label('Mirisni Akordi')
    ->schema([
        Forms\Components\Select::make('name')
            ->label('Nota')
            // Transform the config array so index is the key, name is the value
            ->options(array_combine(
                range(0, count(config('accords')) - 1), 
                array_keys(config('accords'))
            ))
            ->required()
            ->searchable(), // Recommended since the list is long

        Forms\Components\TextInput::make('percentage')
            ->label('Procenat %')
            ->numeric()
            ->required(),
    ])
    ->columns(2),
                ])->collapsible(),
        ]);
    }

    /**
     * Kad admin promijeni Regularnu ili Akcijsku cijenu — izračunaj popust %.
     */
    public static function syncDiscountPercentage(Get $get, Set $set): void
    {
        $original = (float) ($get('original_price') ?? 0);
        $price    = (float) ($get('price') ?? 0);

        if ($original <= 0 || $price <= 0 || $price >= $original) {
            $set('discount_percentage', 0);
            return;
        }

        $set('discount_percentage', (int) round((1 - $price / $original) * 100));
    }

    /**
     * Kad admin promijeni Popust % — izračunaj Akcijsku cijenu iz Regularne.
     */
    public static function syncPriceFromDiscount(Get $get, Set $set): void
    {
        $original = (float) ($get('original_price') ?? 0);
        $discount = (float) ($get('discount_percentage') ?? 0);

        if ($original <= 0) {
            return;
        }

        if ($discount <= 0) {
            $set('price', round($original, 2));
            $set('discount_percentage', 0);
            return;
        }

        if ($discount >= 100) {
            $discount = 100;
            $set('discount_percentage', 100);
        }

        $set('price', round($original * (1 - $discount / 100), 2));
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('main_image')->label('Slika')->circular(),
                Tables\Columns\TextColumn::make('name')->label('Naziv')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('gender')
                    ->label('Pol')
                    ->badge()
                    ->formatStateUsing(fn ($state) => $state instanceof PerfumeGender ? $state->label() : $state),
                Tables\Columns\TextColumn::make('base_price')->label('Nabavna')->money('bam'),
                Tables\Columns\TextColumn::make('original_price')
                    ->label('Regularna')
                    ->money('bam'),
                Tables\Columns\TextColumn::make('price')
                    ->label('Akcijska')
                    ->money('bam')
                    ->color('success')
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('discount_percentage')
                    ->label('Popust')
                    ->suffix('%')
                    ->color(fn ($state) => $state > 0 ? 'danger' : 'gray'),
                Tables\Columns\IconColumn::make('availability')->label('Dostupno')->boolean(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('gender')->options(PerfumeGender::class),
                Tables\Filters\Filter::make('on_sale')
                    ->label('Na akciji')
                    ->query(fn ($query) => $query->onSale()),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ]);
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