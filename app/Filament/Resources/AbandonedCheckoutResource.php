<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AbandonedCheckoutResource\Pages;
use App\Models\AbandonedCheckout;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class AbandonedCheckoutResource extends Resource
{
    protected static ?string $model = AbandonedCheckout::class;
    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';
    protected static ?string $navigationLabel = 'Napuštene korpe';
    protected static ?string $modelLabel = 'Napuštena korpa';
    protected static ?string $pluralModelLabel = 'Napuštene korpe';
    protected static ?string $navigationGroup = 'Marketing';
    protected static ?int $navigationSort = 10;

    public static function getNavigationBadge(): ?string
    {
        $c = static::getModel()::whereNull('recovered_at')->count();
        return $c > 0 ? (string) $c : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('updated_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->copyable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('item_count')
                    ->label('Artikli')
                    ->badge()
                    ->color('info')
                    ->formatStateUsing(fn ($state) => $state . ' kom'),

                Tables\Columns\TextColumn::make('subtotal')
                    ->label('Iznos korpe')
                    ->money('BAM')
                    ->sortable(),

                Tables\Columns\TextColumn::make('recovered_at')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn ($state) => $state ? 'Vratio se' : 'Nije naručio')
                    ->color(fn ($state) => $state ? 'success' : 'warning'),

                Tables\Columns\TextColumn::make('order.pretty_id')
                    ->label('Narudžba')
                    ->placeholder('—')
                    ->url(fn ($record) => $record->order_id ? route('filament.admin.resources.orders.edit', ['record' => $record->order_id]) : null),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Zadnja aktivnost')
                    ->since()
                    ->sortable(),
            ])
            ->filters([
                TernaryFilter::make('recovered_at')
                    ->label('Status')
                    ->placeholder('Sve')
                    ->trueLabel('Vratio se i naručio')
                    ->falseLabel('Nije naručio')
                    ->queries(
                        true:  fn (Builder $q) => $q->whereNotNull('recovered_at'),
                        false: fn (Builder $q) => $q->whereNull('recovered_at'),
                        blank: fn (Builder $q) => $q,
                    ),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ])
            ->emptyStateHeading('Nema napuštenih korpi')
            ->emptyStateDescription('Kada kupac unese email a ne završi narudžbu, evidencija se pojavljuje ovdje.')
            ->emptyStateIcon('heroicon-o-shopping-cart');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAbandonedCheckouts::route('/'),
            'view'  => Pages\ViewAbandonedCheckout::route('/{record}'),
        ];
    }

    public static function canCreate(): bool { return false; }
    public static function canEdit($record): bool { return false; }
}
