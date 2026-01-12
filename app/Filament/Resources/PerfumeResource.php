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
                    ]),

                    Forms\Components\RichEditor::make('description')
                        ->label('Opis')
                        ->columnSpanFull(),
                ])->collapsible(),

            // SEKCIJA 2: CIJENE (LOGIKA)
            Section::make('Cijene i Popust')
                ->description('Base Price je fiksna. Unosom popusta mijenja se Price (prodajna cijena).')
                ->schema([
                    Grid::make(3)->schema([
                        // FIXNA NABAVNA CIJENA
                        Forms\Components\TextInput::make('base_price')
                            ->label('Nabavna cijena (Base)')
                            ->numeric()
                            ->prefix('KM')
                            ->required()
                            ->live() 
                            ->afterStateUpdated(fn (Get $get, Set $set) => self::calculateSellingPrice($get, $set)),

                        // POPUST KOJI MIJENJA PRODAJNU CIJENU
                        Forms\Components\TextInput::make('discount_percentage')
                            ->label('Popust %')
                            ->numeric()
                            ->suffix('%')
                            ->default(0)
                            ->live()
                            ->afterStateUpdated(fn (Get $get, Set $set) => self::calculateSellingPrice($get, $set)),

                        // PRODAJNA CIJENA (Ono što kupac vidi)
                        Forms\Components\TextInput::make('price')
                            ->label('Prodajna cijena (Price)')
                            ->numeric()
                            ->prefix('KM')
                            ->required()
                            ->helperText('Ova cijena se automatski računa, ali je možete i ručno korigovati.'),
                    ]),

                    Forms\Components\DatePicker::make('restock_date')
                        ->label('Datum dopune')
                        ->native(false),
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
                                ->options(config('accords'))
                                ->required(),

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
     * LOGIKA: Mijenjamo isključivo 'price' na osnovu 'base_price' i popusta.
     */
    public static function calculateSellingPrice(Get $get, Set $set): void
    {
        // Uzimamo trenutnu prodajnu cijenu (npr. 60)
        $currentPrice = (float) ($get('price') ?? 0);
        $discount = (float) ($get('discount_percentage') ?? 0);

        if ($discount > 0 && $currentPrice > 0) {
            // 60 - (60 * 0.10) = 54
            $newPrice = $currentPrice * (1 - ($discount / 100));
            $set('price', round($newPrice, 2));
        }
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
                Tables\Columns\TextColumn::make('base_price')->label('Nabavna (Base)')->money('bam'),
                Tables\Columns\TextColumn::make('price')
                    ->label('Prodajna (Price)')
                    ->money('bam')
                    ->color('success')
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('discount_percentage')->label('Popust')->suffix('%'),
                Tables\Columns\IconColumn::make('availability')->label('Dostupno')->boolean(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('gender')->options(PerfumeGender::class),
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