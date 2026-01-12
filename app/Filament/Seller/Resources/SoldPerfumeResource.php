<?php

namespace App\Filament\Seller\Resources;

use App\Filament\Seller\Resources\SoldPerfumeResource\Pages;
use App\Models\SoldPerfume;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SoldPerfumeResource extends Resource
{
    protected static ?string $model = SoldPerfume::class;
    protected static ?string $navigationIcon = 'heroicon-o-banknotes';
    protected static ?string $navigationLabel = 'Prodati Parfemi';
    protected static ?string $pluralModelLabel = 'Prodani Parfemi';
    protected static ?string $navigationGroup = 'Parfemi';

    // Potpuna zabrana bilo kakvih izmjena (Read-only za prodavača)
    public static function canEdit($record): bool { return false; }
    public static function canDelete($record): bool { return false; }
    public static function canCreate(): bool { return false; }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('perfume.name')
                    ->label('Parfem')
                    ->description(fn (SoldPerfume $record): string => $record->perfume->inspired_by ?? '')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('customer.full_name')
                    ->label('Kupac')
                    ->placeholder('Anonimni kupac')
                    ->searchable(),

                Tables\Columns\TextColumn::make('quantity')
                    ->label('Količina')
                    ->badge()
                    ->color('success')
                    ->sortable()
                    ->summarize(Tables\Columns\Summarizers\Sum::make()->label('Ukupno')),

                Tables\Columns\TextColumn::make('base_price')
                    ->label('Cijena po komadu')
                    ->money('bam', true),

                Tables\Columns\TextColumn::make('total_value')
                    ->label('Ukupno')
                    ->money('bam', true)
                    ->state(fn ($record) => $record->quantity * $record->base_price)
                    ->summarize(
                        Tables\Columns\Summarizers\Summarizer::make()
                            ->label('Zarada')
                            ->using(fn ($query) => $query->sum(DB::raw('quantity * base_price')))
                    ),

                Tables\Columns\TextColumn::make('is_manual')
                    ->label('Izvor')
                    ->formatStateUsing(fn ($state) => $state ? 'Ručno' : 'Narudžba')
                    ->badge()
                    ->color(fn ($state) => $state ? 'gray' : 'info'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Datum prodaje')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->filters([
                // FILTER PO PARFEMU
                SelectFilter::make('perfume_id')
                    ->label('Parfem')
                    ->relationship('perfume', 'name')
                    ->searchable()
                    ->preload(),

                // FILTER PO KUPCU
                SelectFilter::make('customer_id')
                    ->label('Kupac')
                    ->relationship('customer', 'full_name')
                    ->searchable()
                    ->preload(),

                // FILTER PO DATUMIMA
                Filter::make('sold_at')
                    ->form([
                        DatePicker::make('from')->label('Od datuma'),
                        DatePicker::make('until')->label('Do datuma'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];
                        if ($data['from'] ?? null) $indicators[] = 'Od: ' . \Carbon\Carbon::parse($data['from'])->format('d/m/Y');
                        if ($data['until'] ?? null) $indicators[] = 'Do: ' . \Carbon\Carbon::parse($data['until'])->format('d/m/Y');
                        return $indicators;
                    }),
            ])
            ->actions([])
            ->bulkActions([]);
    }

    public static function getEloquentQuery(): Builder
    {
        // Prikazujemo samo prodaje koje pripadaju ulogovanom prodavcu i nisu otkazane
        return parent::getEloquentQuery()
            ->with(['perfume', 'customer']) // Eager loading za bolje performanse
            ->where('user_id', Auth::id())
            ->where('quantity', '>', 0)
            ->where('cancelled', false);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSoldPerfumes::route('/'),
        ];
    }
}