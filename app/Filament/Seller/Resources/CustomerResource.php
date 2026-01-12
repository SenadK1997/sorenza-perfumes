<?php

namespace App\Filament\Seller\Resources;

use App\Enums\Canton;
use App\Filament\Seller\Resources\CustomerResource\Pages;
use App\Models\Customer;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class CustomerResource extends Resource
{
    protected static ?string $model = Customer::class;
    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    protected static ?string $navigationLabel = 'Kupci';
    protected static ?string $modelLabel = 'Kupac';
    protected static ?string $pluralModelLabel = 'Kupci';
    protected static bool $shouldRegisterNavigation = true;

    /**
     * Ograničavamo upit tako da prodavači vide samo svoje kupce, a admini sve.
     */
    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();
        
        if (Auth::user()->hasRole('admin')) {
            return $query;
        }

        return $query->where('user_id', Auth::id());
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Osnovne Informacije')
                ->schema([
                    Forms\Components\TextInput::make('full_name')
                        ->required()
                        ->label('Ime i Prezime'),
                    Forms\Components\TextInput::make('email')
                        ->email()
                        ->unique(ignoreRecord: true)
                        ->label('Email'),
                    Forms\Components\TextInput::make('phone')
                        ->tel()
                        ->required()
                        ->label('Telefon'),
                ])->columns(2),

            Forms\Components\Section::make('Lokacija')
                ->schema([
                    Forms\Components\Select::make('canton')
                        ->options(Canton::class)
                        ->required()
                        ->label('Kanton'),
                    Forms\Components\TextInput::make('city')
                        ->required()
                        ->label('Grad'),
                    Forms\Components\TextInput::make('address_line_1')
                        ->required()
                        ->label('Adresa'),
                    Forms\Components\TextInput::make('zipcode')
                        ->required()
                        ->label('Zip'),
                ])->columns(4),
            
            Forms\Components\Section::make('Dodatno')
                ->schema([
                    Forms\Components\Select::make('user_id')
                        ->relationship('seller', 'name')
                        ->label('Zaduženi prodavač')
                        ->default(Auth::id())
                        ->disabled(!Auth::user()->hasRole('admin')) // Samo admin može mijenjati vlasnika kupca
                        ->dehydrated() // Osigurava da se ID pošalje u bazu iako je polje disabled
                        ->required(),
                    // Forms\Components\TagsInput::make('interests')
                    //     ->label('Interesovanja'),
                    Forms\Components\Textarea::make('suggestions')
                        ->label('Sugestije')
                        ->columnSpanFull(),
                ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('full_name')
                    ->searchable()
                    ->label('Kupac'),
                Tables\Columns\TextColumn::make('city')
                    ->label('Grad'),
                Tables\Columns\TextColumn::make('canton')
                    ->label('Kanton')
                    ->badge(),
                Tables\Columns\TextColumn::make('phone')
                    ->label('Telefon'),
                Tables\Columns\TextColumn::make('seller.name')
                    ->label('Prodavač')
                    ->placeholder('Nema prodavača')
                    ->visible(fn () => Auth::user()->hasRole('admin')), // Samo admin vidi kolonu prodavača
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->label('Datum unosa')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('canton')->options(Canton::class)->label('Kanton'),

                Filter::make('created_at')
                    ->form([
                        Forms\Components\Select::make('period')
                            ->label('Vremenski period')
                            ->options([
                                'today' => 'Danas',
                                'this_week' => 'Ove sedmice',
                                'this_month' => 'Ovaj mjesec',
                                'last_month' => 'Prošli mjesec',
                                'this_year' => 'Ove godine',
                            ]),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when($data['period'] === 'today', fn ($q) => $q->whereDate('created_at', Carbon::today()))
                            ->when($data['period'] === 'this_week', fn ($q) => $q->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]))
                            ->when($data['period'] === 'this_month', fn ($q) => $q->whereMonth('created_at', Carbon::now()->month))
                            ->when($data['period'] === 'last_month', fn ($q) => $q->whereMonth('created_at', Carbon::now()->subMonth()->month))
                            ->when($data['period'] === 'this_year', fn ($q) => $q->whereYear('created_at', Carbon::now()->year));
                    })->label('Period'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                // Brisanje je vidljivo samo adminima
                Tables\Actions\DeleteAction::make()
                    ->visible(fn () => Auth::user()->hasRole('admin')),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->visible(fn () => Auth::user()->hasRole('admin')),
                ]),
            ]);
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