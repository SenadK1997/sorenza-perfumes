<?php

namespace App\Filament\Seller\Resources;

use App\Filament\Seller\Resources\CancelledPerfumeResource\Pages;
use App\Models\SoldPerfume;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class CancelledPerfumeResource extends Resource
{
    protected static ?string $model = SoldPerfume::class;

    protected static ?string $navigationIcon = 'heroicon-o-archive-box-x-mark';

    protected static ?string $navigationLabel = 'Otkazani Parfemi';
    protected static ?int $navigationSort = 10;
    protected static ?string $navigationGroup = 'Parfemi';

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

                Tables\Columns\TextColumn::make('quantity')
                    ->label('Količina (Storno)')
                    ->badge()
                    ->color('danger') // Crvena boja jer je storno
                    ->sortable(),

                Tables\Columns\TextColumn::make('cancellation_reason')
                    ->label('Razlog otkaza')
                    ->badge()
                    ->color('warning')
                    // Ovdje vršimo prevod ključeva iz baze u čitljiv tekst
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'wrong_perfume' => 'Pogrešan parfem',
                        'broken'        => 'Oštećeno/Polupano',
                        'returned'      => 'Kupac vratio',
                        default         => $state,
                    }),

                Tables\Columns\TextColumn::make('customer.full_name')
                    ->label('Kupac')
                    ->placeholder('Anonimni kupac'),

                Tables\Columns\TextColumn::make('base_price')
                    ->label('Cijena')
                    ->money('bam', true),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Datum storna')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->filters([
                Filter::make('created_at')
                    ->form([
                        DatePicker::make('from')->label('Od'),
                        DatePicker::make('until')->label('Do'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when($data['from'], fn ($q, $date) => $q->whereDate('created_at', '>=', $date))
                            ->when($data['until'], fn ($q, $date) => $q->whereDate('created_at', '<=', $date));
                    })
            ])
            ->actions([])
            ->bulkActions([]);
    }

    public static function getEloquentQuery(): Builder
    {
        // OVDJE FILTRIRAMO SAMO STORNIRANE ZAPISE
        return parent::getEloquentQuery()
            ->where('user_id', Auth::id())
            ->where(function ($query) {
                $query->where('quantity', '<', 0)
                      ->orWhere('cancelled', true);
            });
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCancelledPerfumes::route('/'),
        ];
    }
}