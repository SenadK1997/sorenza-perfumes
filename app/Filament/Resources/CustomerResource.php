<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CustomerResource\Pages;
use App\Models\Customer;
use App\Models\Order;
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
                            ->label('Telefon')
                            ->tel()
                            ->helperText('Bilo koji format prolazi (060…, +38760…, 0038760…) — automatski se pretvara u +387… oblik.'),
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

                Forms\Components\Section::make('Nalog kupca (/nalog)')
                    ->description('Postavi ili resetuj lozinku za kupčev nalog. Ostavi prazno da ne mijenjaš postojeću.')
                    ->schema([
                        Forms\Components\TextInput::make('password')
                            ->label('Nova lozinka')
                            ->password()
                            ->revealable()
                            ->minLength(8)
                            ->autocomplete('new-password')
                            ->dehydrated(fn ($state) => filled($state))
                            ->helperText('Kad se sačuva, kupac se može prijaviti sa email + lozinkom na /nalog.'),
                        Forms\Components\Placeholder::make('last_login_at_display')
                            ->label('Zadnja prijava')
                            ->content(fn (?Customer $record) => $record?->last_login_at?->format('d.m.Y H:i') ?? 'Nikad'),
                    ])->columns(2),

                Forms\Components\Section::make('Loyalty program')
                    ->description('Bonus poeni za nivo — čista poena vrijednost (NIJE novac). Ovo je "set" polje: šta upišeš, to bude. 1500 = 1500 poena. Poeni se dodaju stvarnoj potrošnji kupca (u KM) samo pri izračunu nivoa; kupcu se u nalogu prikazuju odvojeno kao "bonus poeni", ne kao KM.')
                    ->schema([
                        Forms\Components\TextInput::make('loyalty_adjustment')
                            ->label('Loyalty poeni (bonus)')
                            ->numeric()
                            ->step(1)
                            ->default(0)
                            ->suffix('poena')
                            ->helperText('Set vrijednost, ne akumulira. Upisano 1500 = kupac ima 1500 bonus poena (može biti negativno da ga spustiš).'),
                        Forms\Components\TextInput::make('loyalty_adjustment_note')
                            ->label('Razlog (interno)')
                            ->maxLength(255)
                            ->placeholder('npr. VIP kupac, kompenzacija za problem…'),
                        Forms\Components\Placeholder::make('current_tier')
                            ->label('Trenutni pregled')
                            ->content(function (?Customer $record) {
                                if (!$record) return '—';
                                $real  = \App\Services\CustomerLoyalty::realSpentThisYear($record);
                                $bonus = \App\Services\CustomerLoyalty::bonusPoints($record);
                                $t     = \App\Services\CustomerLoyalty::forCustomer($record);
                                return sprintf(
                                    'Stvarna potrošnja ove godine: %s KM · Bonus poena: %s · Ukupno za nivo: %s · Nivo: %s (−%d%%)',
                                    number_format($real, 2),
                                    number_format($bonus, 0),
                                    number_format($real + $bonus, 2),
                                    $t['name'],
                                    (int) $t['discount']
                                );
                            })
                            ->columnSpanFull(),
                    ])->columns(2),

                Forms\Components\Section::make('Sigurnost')
                    ->description('Blokirani kupac ne može zatražiti magic link, prijaviti se lozinkom, niti napraviti narudžbu.')
                    ->schema([
                        Forms\Components\Toggle::make('is_blocked')
                            ->label('Blokiran')
                            ->inline(false)
                            ->live()
                            ->afterStateUpdated(function ($state, Forms\Set $set) {
                                if ($state) {
                                    $set('blocked_at', now());
                                } else {
                                    $set('blocked_at', null);
                                    $set('blocked_reason', null);
                                }
                            }),
                        Forms\Components\TextInput::make('blocked_reason')
                            ->label('Razlog blokade')
                            ->placeholder('npr. Više puta otkazivao/la narudžbu')
                            ->visible(fn (Forms\Get $get) => (bool) $get('is_blocked')),
                        Forms\Components\Placeholder::make('blocked_at_display')
                            ->label('Blokiran od')
                            ->content(fn (?Customer $record) => $record?->blocked_at?->format('d.m.Y H:i') ?? '—')
                            ->visible(fn (Forms\Get $get) => (bool) $get('is_blocked')),
                    ])->columns(2),
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

                // Ukupno potrošeno = zbir svih neotkazanih narudžbi (final amount, uključuje popuste)
                Tables\Columns\TextColumn::make('orders_total_spent')
                    ->label('Ukupno Potrošeno')
                    ->money('bam')
                    ->sortable()
                    ->color('success')
                    ->weight('bold'),

                Tables\Columns\IconColumn::make('is_blocked')
                    ->label('Blokiran')
                    ->boolean()
                    ->trueIcon('heroicon-o-no-symbol')
                    ->falseIcon('heroicon-o-check')
                    ->trueColor('danger')
                    ->falseColor('success'),

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

                Tables\Filters\TernaryFilter::make('is_blocked')
                    ->label('Blokada')
                    ->trueLabel('Blokirani')
                    ->falseLabel('Aktivni'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
                Tables\Actions\Action::make('sendMessage')
                    ->label('Pošalji poruku')
                    ->icon('heroicon-o-chat-bubble-left-right')
                    ->color('primary')
                    ->form([
                        Forms\Components\Textarea::make('body')
                            ->label('Poruka')
                            ->required()
                            ->rows(5)
                            ->helperText('Kupac vidi identitet "Sorenza" u svom nalogu.'),
                    ])
                    ->action(function (Customer $r, array $data) {
                        \App\Services\Messaging::adminSendToCustomer(
                            \Illuminate\Support\Facades\Auth::user(),
                            $r,
                            $data['body']
                        );
                        \Filament\Notifications\Notification::make()->title('Poruka poslata')->success()->send();
                    }),

                Tables\Actions\Action::make('toggleBlock')
                    ->label(fn (Customer $r) => $r->is_blocked ? 'Odblokiraj' : 'Blokiraj')
                    ->icon(fn (Customer $r) => $r->is_blocked ? 'heroicon-o-check' : 'heroicon-o-no-symbol')
                    ->color(fn (Customer $r) => $r->is_blocked ? 'success' : 'danger')
                    ->requiresConfirmation()
                    ->form(fn (Customer $r) => $r->is_blocked ? [] : [
                        Forms\Components\TextInput::make('reason')
                            ->label('Razlog (opciono)')
                            ->placeholder('npr. Više puta otkazivao/la narudžbu'),
                    ])
                    ->action(function (Customer $r, array $data) {
                        $r->is_blocked ? $r->unblock() : $r->block($data['reason'] ?? null);
                    }),
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
            ->select('customers.*')
            // Ukupno potrošeno = ono što je KUPAC platio.
            //   Online narudžbe: orders.amount (već sadrži popuste + dostavu, kompletno).
            //   Manualna direktna prodaja: quantity × customer_price
            //     (is_manual=false redovi su interno vault knjigovodstvo koje se
            //      auto-kreira kad narudžba postane 'completed' — MORAJU se
            //      izuzeti da ne bi duplirali online narudžbu.)
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
            ) as orders_total_spent");
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