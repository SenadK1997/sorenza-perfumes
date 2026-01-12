<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CustomerResource\Pages;
use App\Models\Customer;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class CustomerResource extends Resource
{
    protected static ?string $model = Customer::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationLabel = 'Kupci';
    protected static ?string $pluralModelLabel = 'Kupci';
    protected static ?string $navigationGroup = 'Administracija';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informacije o kupcu')
                    ->schema([
                        Forms\Components\TextInput::make('full_name') // Ako je u bazi 'name', ostavi ovako
                            ->label('Ime i prezime')
                            ->required(),
                        Forms\Components\TextInput::make('phone')
                            ->label('Telefon'),
                        Forms\Components\TextInput::make('email')
                            ->email()
                            ->label('Email adresa'),
                    ])->columns(2),

                Forms\Components\Section::make('Pripadnost')
                    ->description('Dodijelite kupca određenom prodavaču')
                    ->schema([
                        Forms\Components\Select::make('user_id')
                            ->label('Prodavač')
                            ->relationship('seller', 'name') // Pretpostavka: Customer model ima seller() relaciju
                            ->searchable()
                            ->preload()
                            ->hint('Kupac pripada ovom prodavaču u sistemu.'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('full_name')
                    ->label('Kupac')
                    ->description(fn (Customer $record): string => $record->email ?? '')
                    ->searchable()
                    ->sortable(),

                // Kolona koja pokazuje prodavača
                Tables\Columns\TextColumn::make('seller.name')
                    ->label('Prodavač')
                    ->placeholder('Nije dodijeljen')
                    ->badge()
                    ->color(fn ($state) => $state ? 'info' : 'gray')
                    ->sortable(),

                Tables\Columns\TextColumn::make('phone')
                    ->label('Telefon')
                    ->searchable(),

                // Ukupno potrošeno (sumira neotkazane prodaje)
                Tables\Columns\TextColumn::make('valid_sales_sum_base_price')
                    ->label('Ukupno Potrošeno')
                    ->money('EUR')
                    ->sortable()
                    ->color('success')
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Registrovan')
                    ->dateTime('d.m.Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                // Filter po Prodavaču
                Tables\Filters\SelectFilter::make('user_id')
                    ->label('Po prodavaču')
                    ->relationship('seller', 'name')
                    ->searchable()
                    ->preload(),

                // Filter po Parfemu
                Tables\Filters\SelectFilter::make('perfume_id')
                    ->label('Kupio Parfem')
                    ->relationship('soldPerfumes.perfume', 'name')
                    ->multiple()
                    ->preload(),

                // Filter po datumu kupovine
                Tables\Filters\Filter::make('datum_kupovine')
                    ->form([
                        Forms\Components\DatePicker::make('od')->label('Kupljeno od'),
                        Forms\Components\DatePicker::make('do')->label('Kupljeno do'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when($data['od'], fn($q) => $q->whereHas('soldPerfumes', fn($sq) => $sq->whereDate('created_at', '>=', $data['od'])))
                            ->when($data['do'], fn($q) => $q->whereHas('soldPerfumes', fn($sq) => $sq->whereDate('created_at', '<=', $data['do'])));
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->with(['seller']) // Sprečava N+1 problem za prodavače
            ->withSum('validSales', 'base_price');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCustomers::route('/'),
            'create' => Pages\CreateCustomer::route('/create'),
            'edit' => Pages\EditCustomer::route('/{record}/edit'),
        ];
    }
}