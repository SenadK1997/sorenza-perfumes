<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CancelledCustomerResource\Pages;
use App\Models\Customer;
use App\Models\Order;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class CancelledCustomerResource extends Resource
{
    protected static ?string $model = Customer::class;

    protected static ?string $navigationIcon = 'heroicon-o-x-circle';
    protected static ?string $navigationLabel = 'Otkazani kupci';
    protected static ?string $pluralModelLabel = 'Otkazani kupci';
    protected static ?string $modelLabel = 'Otkazani kupac';
    protected static ?string $slug = 'cancelled-customers';
    protected static ?string $navigationGroup = 'Administracija';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Kupac')
                ->schema([
                    Forms\Components\TextInput::make('full_name')->label('Ime i prezime')->required(),
                    Forms\Components\TextInput::make('email')->email()->label('Email')->required(),
                    Forms\Components\TextInput::make('phone')->label('Telefon'),
                ])->columns(2),

            Forms\Components\Section::make('Sigurnost')
                ->description('Blokirani kupac ne može zatražiti magic link, prijaviti se lozinkom, niti napraviti narudžbu.')
                ->schema([
                    Forms\Components\Toggle::make('is_blocked')
                        ->label('Blokiran')
                        ->inline(false)
                        ->live()
                        ->afterStateUpdated(function ($state, Forms\Set $set) {
                            if ($state) $set('blocked_at', now());
                            else { $set('blocked_at', null); $set('blocked_reason', null); }
                        }),
                    Forms\Components\TextInput::make('blocked_reason')
                        ->label('Razlog blokade')
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
                    ->description(fn (Customer $r) => $r->email ?? '')
                    ->searchable(['full_name', 'email'])
                    ->sortable(),

                Tables\Columns\TextColumn::make('phone')
                    ->label('Telefon')
                    ->searchable(),

                Tables\Columns\TextColumn::make('cancellations_count')
                    ->label('Puta otkazano')
                    ->badge()
                    ->color(fn ($state) => $state >= 3 ? 'danger' : ($state >= 2 ? 'warning' : 'gray'))
                    ->sortable(),

                Tables\Columns\TextColumn::make('last_cancelled_at')
                    ->label('Zadnje otkazivanje')
                    ->dateTime('d.m.Y H:i')
                    ->placeholder('—')
                    ->sortable(),

                Tables\Columns\TextColumn::make('completed_orders_count')
                    ->label('Uspješne narudžbe')
                    ->badge()
                    ->color('success'),

                Tables\Columns\IconColumn::make('is_blocked')
                    ->label('Blokiran')
                    ->boolean()
                    ->trueIcon('heroicon-o-no-symbol')
                    ->falseIcon('heroicon-o-check')
                    ->trueColor('danger')
                    ->falseColor('gray'),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_blocked')
                    ->label('Blokada'),
                Tables\Filters\Filter::make('spammers')
                    ->label('Najmanje 3 otkazivanja')
                    ->query(fn (Builder $q) => $q->having('cancellations_count', '>=', 3)),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
                Tables\Actions\BulkAction::make('block')
                    ->label('Blokiraj označene')
                    ->icon('heroicon-o-no-symbol')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->form([
                        Forms\Components\TextInput::make('reason')->label('Razlog (opciono)'),
                    ])
                    ->action(function ($records, array $data) {
                        foreach ($records as $r) $r->block($data['reason'] ?? null);
                    }),
            ])
            ->defaultSort('cancellations_count', 'desc');
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withCancellations()
            ->select('customers.*')
            ->selectSub(
                Order::selectRaw('COUNT(*)')
                    ->whereColumn('orders.email', 'customers.email')
                    ->where('status', 'cancelled'),
                'cancellations_count'
            )
            ->selectSub(
                Order::selectRaw('MAX(updated_at)')
                    ->whereColumn('orders.email', 'customers.email')
                    ->where('status', 'cancelled'),
                'last_cancelled_at'
            )
            ->selectSub(
                Order::selectRaw('COUNT(*)')
                    ->whereColumn('orders.email', 'customers.email')
                    ->where('status', 'completed'),
                'completed_orders_count'
            );
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCancelledCustomers::route('/'),
            'edit'  => Pages\EditCancelledCustomer::route('/{record}/edit'),
        ];
    }
}
