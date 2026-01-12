<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SoldPerfumeResource\Pages;
use App\Models\SoldPerfume;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Columns\Summarizers\Sum;
use Illuminate\Support\Facades\DB;

class SoldPerfumeResource extends Resource
{
    protected static ?string $model = SoldPerfume::class;
    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';
    protected static ?string $navigationLabel = 'Statistika prodaje';
    protected static ?string $pluralModelLabel = 'Prodani Parfemi';
    protected static ?string $navigationGroup = 'Izvještaji';

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('perfume.name')
                    ->label('Parfem')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('quantity')
                    ->label('Količina')
                    ->sortable()
                    ->alignCenter()
                    ->summarize(Sum::make()->label('Ukupno komada')),

                TextColumn::make('user.name')
                    ->label('Prodavač')
                    ->sortable(),

                TextColumn::make('customer.full_name')
                    ->label('Kupac')
                    ->placeholder('Direktna prodaja')
                    ->searchable(),

                TextColumn::make('total_price')
                    ->label('Ukupna Vrijednost')
                    ->money('BAM')
                    ->state(fn ($record) => $record->quantity * $record->base_price)
                    ->summarize(
                        Tables\Columns\Summarizers\Summarizer::make()
                            ->label('Ukupni promet')
                            // UKLONILI SMO "Builder" ispred $query da izbjegnemo Type Mismatch
                            ->using(fn ($query) => $query->sum(DB::raw('quantity * base_price')))
                    ),

                TextColumn::make('created_at')
                    ->label('Datum prodaje')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),

                TextColumn::make('is_manual')
                    ->label('Tip prodaje')
                    ->formatStateUsing(fn ($state) => $state ? 'Ručno' : 'Narudžba')
                    ->badge()
                    ->color(fn ($state) => $state ? 'gray' : 'info'),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('perfume_id')
                    ->relationship('perfume', 'name')
                    ->label('Filtriraj po parfemu')
                    ->searchable()
                    ->preload(),
                SelectFilter::make('customer_id')
                    ->relationship('customer', 'full_name')
                    ->label('Kupac')
                    ->searchable(),

                SelectFilter::make('user_id')
                    ->relationship('user', 'name')
                    ->label('Prodavač'),

                Filter::make('created_at')
                    ->form([
                        DatePicker::make('from')->label('Od datuma'),
                        DatePicker::make('until')->label('Do datuma'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when($data['from'], fn ($query, $date) => $query->whereDate('created_at', '>=', $date))
                            ->when($data['until'], fn ($query, $date) => $query->whereDate('created_at', '<=', $date));
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];
                        if ($data['from'] ?? null) $indicators[] = 'Od: ' . \Carbon\Carbon::parse($data['from'])->format('d/m/Y');
                        if ($data['until'] ?? null) $indicators[] = 'Do: ' . \Carbon\Carbon::parse($data['until'])->format('d/m/Y');
                        return $indicators;
                    }),
            ])
            ->actions([
                // Isključeno jer je ovo report, ali možeš dodati ViewAction ako želiš detalje
            ])
            ->bulkActions([]);
    }

    public static function getEloquentQuery(): Builder
    {
        // Prikazujemo samo one prodaje koje nisu otkazane
        return parent::getEloquentQuery()
            ->with(['perfume', 'user', 'customer']) // Eager loading za bolje performanse
            ->where('cancelled', false);
    }

    // Onemogućavamo kreiranje, brisanje i editovanje jer ovo služi kao arhiva/izvještaj
    public static function canCreate(): bool { return false; }
    public static function canEdit($record): bool { return false; }
    public static function canDelete($record): bool { return false; }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSoldPerfumes::route('/'),
        ];
    }
}