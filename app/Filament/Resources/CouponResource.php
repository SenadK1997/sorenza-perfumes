<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CouponResource\Pages;
use App\Models\Coupon;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class CouponResource extends Resource
{
    protected static ?string $model = Coupon::class;

    protected static ?string $navigationIcon = 'heroicon-o-ticket';
    protected static ?string $navigationLabel = 'Kuponi';
    protected static ?string $navigationGroup = 'Marketing';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Osnovne Informacije')
                    ->description('Definišite kod i tip popusta. Admini su ograničeni na 10% ili 20 BAM.')
                    ->schema([
                        Forms\Components\TextInput::make('code')
                            ->label('Kod kupona')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->default(fn () => strtoupper(Str::random(8))),

                        Forms\Components\Select::make('user_id')
                            ->label('Vlasnik kupona (Prodavač)')
                            ->relationship('user', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),

                        Forms\Components\Select::make('type')
                            ->label('Tip popusta')
                            ->options([
                                'fixed' => 'Fiksni iznos (BAM)',
                                'percent' => 'Procenat (%)',
                            ])
                            ->required()
                            ->live() // Omogućava instant promjenu prefiksa i validacije
                            ->native(false),

                        Forms\Components\TextInput::make('value')
                            ->label('Vrijednost')
                            ->numeric()
                            ->required()
                            ->prefix(fn (Forms\Get $get) => $get('type') === 'percent' ? '%' : 'BAM')
                            ->helperText(fn () => auth()->user()->email !== 'senad.okt97@gmail.com' 
                                ? 'Limit: Max 10% ili 20 BAM.' 
                                : 'SuperAdmin mod: Nema limita.')
                            ->rules([
                                fn (Forms\Get $get): \Closure => function (string $attribute, $value, \Closure $fail) use ($get) {
                                    $isSenad = auth()->user()->email === 'senad.okt97@gmail.com';
                                    
                                    if (!$isSenad) {
                                        // Provjera za Procenat
                                        if ($get('type') === 'percent' && $value > 10) {
                                            $fail("Kao admin ne možete dodijeliti više od 10% popusta.");
                                        }
                                        
                                        // Provjera za Fiksni iznos
                                        if ($get('type') === 'fixed' && $value > 20) {
                                            $fail("Kao admin ne možete dodijeliti više od 20 BAM popusta.");
                                        }
                                    }

                                    // Sigurnosna kočnica za sve (niko ne može dati više od 100%)
                                    if ($get('type') === 'percent' && $value > 100) {
                                        $fail("Popust ne može biti veći od 100%.");
                                    }
                                },
                            ]),
                    ])->columns(2),

                Forms\Components\Section::make('Ograničenja i Validnost')
                    ->schema([
                        Forms\Components\TextInput::make('min_total')
                            ->label('Minimalni iznos narudžbe')
                            ->numeric()
                            ->prefix('BAM'),

                        Forms\Components\TextInput::make('usage_limit')
                            ->label('Limit korištenja (Ukupno)')
                            ->numeric(),

                        Forms\Components\DateTimePicker::make('starts_at')
                            ->label('Počinje od')
                            ->default(now()),

                        Forms\Components\DateTimePicker::make('expires_at')
                            ->label('Ističe')
                            ->after('starts_at'),

                        Forms\Components\Toggle::make('is_active')
                            ->label('Kupon je aktivan')
                            ->default(true)
                            ->columnSpanFull(),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('code')
                    ->label('Kod')
                    ->searchable()
                    ->copyable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('user.name')
                    ->label('Prodavač')
                    ->sortable(),

                Tables\Columns\TextColumn::make('value')
                    ->label('Popust')
                    ->formatStateUsing(fn ($state, $record) => $record->type === 'percent' ? $state . '%' : $state . ' BAM')
                    ->color('success')
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('used_count')
                    ->label('Iskorišteno')
                    ->description(fn ($record) => "Limit: " . ($record->usage_limit ?? '∞'))
                    ->badge()
                    ->color(fn ($state, $record) => $record->usage_limit && $state >= $record->usage_limit ? 'danger' : 'info'),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Status')
                    ->boolean(),

                Tables\Columns\TextColumn::make('expires_at')
                    ->label('Ističe')
                    ->dateTime('d.m.Y H:i')
                    ->sortable()
                    ->color(fn ($state) => $state && $state->isPast() ? 'danger' : 'gray'),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')->label('Status kupona'),
                Tables\Filters\SelectFilter::make('user_id')
                    ->label('Po prodavaču')
                    ->relationship('user', 'name'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCoupons::route('/'),
            'create' => Pages\CreateCoupon::route('/create'),
            'edit' => Pages\EditCoupon::route('/{record}/edit'),
        ];
    }
}