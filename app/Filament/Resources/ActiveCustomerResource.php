<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ActiveCustomerResource\Pages;
use App\Models\Customer;
use App\Models\Order;
use App\Models\SoldPerfume;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ActiveCustomerResource extends Resource
{
    protected static ?string $model = Customer::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-circle';
    protected static ?string $navigationLabel = 'Aktivni kupci';
    protected static ?string $pluralModelLabel = 'Aktivni kupci';
    protected static ?string $modelLabel = 'Aktivni kupac';
    protected static ?string $slug = 'active-customers';
    protected static ?string $navigationGroup = 'Administracija';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Kupac')
                    ->schema([
                        Forms\Components\TextInput::make('full_name')->label('Ime i prezime')->required(),
                        Forms\Components\TextInput::make('email')->email()->label('Email adresa')->required(),
                        Forms\Components\TextInput::make('phone')->label('Telefon'),
                    ])->columns(2),

                Forms\Components\Section::make('Loyalty program')
                    ->description('Bonus poeni za nivo (NIJE novac). Set polje — šta upišeš, to bude. Kupcu se u nalogu prikazuju odvojeno kao bonus poeni.')
                    ->schema([
                        Forms\Components\TextInput::make('loyalty_adjustment')
                            ->label('Loyalty poeni (bonus)')
                            ->numeric()->step(1)->default(0)->suffix('poena')
                            ->helperText('Set vrijednost, ne akumulira. 1500 = kupac ima 1500 bonus poena.'),
                        Forms\Components\TextInput::make('loyalty_adjustment_note')
                            ->label('Razlog (interno)')
                            ->maxLength(255),
                        Forms\Components\Placeholder::make('current_tier')
                            ->label('Trenutni pregled')
                            ->content(function (?Customer $record) {
                                if (!$record) return '—';
                                $real  = \App\Services\CustomerLoyalty::realSpentThisYear($record);
                                $bonus = \App\Services\CustomerLoyalty::bonusPoints($record);
                                $t     = \App\Services\CustomerLoyalty::forCustomer($record);
                                return sprintf(
                                    'Potrošnja: %s KM · Bonus: %s poena · Za nivo: %s · %s (−%d%%)',
                                    number_format($real, 2),
                                    number_format($bonus, 0),
                                    number_format($real + $bonus, 2),
                                    $t['name'],
                                    (int) $t['discount']
                                );
                            })
                            ->columnSpanFull(),
                    ])->columns(2),

                Forms\Components\Section::make('Nalog kupca (/nalog)')
                    ->description('Postavi ili resetuj lozinku. Kupac se može prijaviti i preko linka na email.')
                    ->schema([
                        Forms\Components\TextInput::make('password')
                            ->label('Nova lozinka')
                            ->password()
                            ->revealable()
                            ->minLength(8)
                            ->autocomplete('new-password')
                            ->dehydrated(fn ($state) => filled($state))
                            ->helperText('Ostavi prazno da ne mijenjaš.'),
                        Forms\Components\Placeholder::make('last_login_at_display')
                            ->label('Zadnja prijava')
                            ->content(fn (?Customer $record) => $record?->last_login_at?->format('d.m.Y H:i') ?? 'Nikad'),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('full_name')
                    ->label('Kupac')
                    ->description(fn (Customer $r) => $r->email ?? '')
                    ->searchable(['full_name', 'email'])
                    ->sortable(),

                Tables\Columns\TextColumn::make('phone')
                    ->label('Telefon')
                    ->searchable(),

                Tables\Columns\TextColumn::make('completed_orders_count')
                    ->label('Uspješne narudžbe')
                    ->badge()
                    ->color('info'),

                Tables\Columns\TextColumn::make('cancellations_count')
                    ->label('Otkazane')
                    ->badge()
                    ->color(fn ($state) => $state > 0 ? 'warning' : 'gray'),

                Tables\Columns\TextColumn::make('manual_sales_count')
                    ->label('Direktne prodaje')
                    ->badge()
                    ->color('info')
                    ->toggleable(),

                Tables\Columns\TextColumn::make('total_spent')
                    ->label('Ukupno potrošeno')
                    ->money('bam')
                    ->color('success')
                    ->weight('bold')
                    ->description(fn ($record) => 'Online: ' . number_format((float) $record->orders_total, 2) . ' KM · Direktno: ' . number_format((float) $record->sales_total, 2) . ' KM'),

                Tables\Columns\IconColumn::make('has_password')
                    ->label('Lozinka')
                    ->boolean()
                    ->getStateUsing(fn (Customer $r) => filled($r->password)),

                Tables\Columns\TextColumn::make('last_login_at')
                    ->label('Zadnja prijava')
                    ->dateTime('d.m.Y H:i')
                    ->placeholder('—')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('has_password')
                    ->label('Ima lozinku')
                    ->queries(
                        true:  fn (Builder $q) => $q->whereNotNull('password'),
                        false: fn (Builder $q) => $q->whereNull('password'),
                    ),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->defaultSort('last_login_at', 'desc');
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->active()
            ->select('customers.*')
            ->selectSub(
                Order::selectRaw('COUNT(*)')
                    ->whereColumn('orders.email', 'customers.email')
                    ->where('status', 'completed'),
                'completed_orders_count'
            )
            ->selectSub(
                Order::selectRaw('COUNT(*)')
                    ->whereColumn('orders.email', 'customers.email')
                    ->where('status', 'cancelled'),
                'cancellations_count'
            )
            ->selectSub(
                Order::selectRaw('COALESCE(SUM(amount), 0)')
                    ->whereColumn('orders.email', 'customers.email')
                    ->where('status', 'completed'),
                'orders_total'
            )
            ->selectSub(
                SoldPerfume::selectRaw('COUNT(*)')
                    ->whereColumn('sold_perfumes.customer_id', 'customers.id')
                    ->where('is_manual', true)
                    ->where('cancelled', false),
                'manual_sales_count'
            )
            ->selectSub(
                // What the customer paid for manual direct sales:
                //   quantity × customer_price. Storno rows carry negative quantity so
                //   ABS() normalizes the display; cancelled rows are filtered anyway.
                SoldPerfume::selectRaw('COALESCE(SUM(ABS(quantity) * COALESCE(customer_price, 0)), 0)')
                    ->whereColumn('sold_perfumes.customer_id', 'customers.id')
                    ->where('is_manual', true)
                    ->where('cancelled', false),
                'sales_total'
            )
            ->selectSub(
                Order::selectRaw('COALESCE(SUM(amount), 0)')
                    ->whereColumn('orders.email', 'customers.email')
                    ->where('status', 'completed'),
                'total_spent_orders'
            )
            // Grand total: what the customer actually paid us.
            //   Excludes is_manual=false sold_perfumes (those mirror completed orders for
            //   vault accounting only and would double-count the order amount).
            ->selectRaw("(
                COALESCE((
                    SELECT SUM(amount)
                    FROM orders
                    WHERE orders.email = customers.email
                      AND orders.status = 'completed'
                ), 0)
              + COALESCE((
                    SELECT SUM(ABS(quantity) * COALESCE(customer_price, 0))
                    FROM sold_perfumes
                    WHERE sold_perfumes.customer_id = customers.id
                      AND sold_perfumes.is_manual = 1
                      AND sold_perfumes.cancelled = 0
                ), 0)
            ) as total_spent");
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListActiveCustomers::route('/'),
            'edit'  => Pages\EditActiveCustomer::route('/{record}/edit'),
        ];
    }
}
