<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SellerRankingResource\Pages;
use App\Models\SoldPerfume;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SellerRankingResource extends Resource
{
    protected static ?string $model = SoldPerfume::class;

    protected static ?string $navigationIcon = 'heroicon-o-trophy';
    protected static ?string $navigationLabel = 'Rang Lista Prodavača';
    protected static ?string $pluralModelLabel = 'Rang Lista';
    protected static ?string $navigationGroup = 'Izvještaji';

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('index')
                    ->label('#')
                    ->rowIndex(),

                TextColumn::make('user.name')
                    ->label('Prodavač')
                    ->searchable()
                    // ISPRAVKA: $rowLoop je objekat, koristimo iteration za broj reda
                    ->icon(fn ($rowLoop): ?string => match ($rowLoop?->iteration) {
                        1 => 'heroicon-s-trophy',
                        2 => 'heroicon-m-academic-cap',
                        3 => 'heroicon-s-star',
                        default => null,
                    })
                    ->iconColor(fn ($rowLoop): ?string => match ($rowLoop?->iteration) {
                        1 => 'warning',
                        2 => 'gray',
                        3 => 'danger',
                        default => null,
                    })
                    ->weight('bold'),

                TextColumn::make('total_quantity')
                    ->label('Prodano komada')
                    ->state(fn ($record) => $record->total_quantity)
                    ->sortable()
                    ->alignCenter(),

                TextColumn::make('total_revenue')
                    ->label('Ukupan promet')
                    ->money('BAM')
                    ->state(fn ($record) => $record->total_revenue)
                    ->sortable()
                    ->badge()
                    ->color('success'),
            ])
            ->defaultSort('total_revenue', 'desc')
            ->filters([
                Filter::make('created_at')
                    ->form([
                        DatePicker::make('from')
                            ->label('Od datuma')
                            ->default(Carbon::now()->startOfMonth()), // Default na početak mjeseca
                        DatePicker::make('until')
                            ->label('Do datuma')
                            ->default(Carbon::now()->endOfMonth()),   // Default na kraj mjeseca
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when($data['from'], fn ($query, $date) => $query->whereDate('created_at', '>=', $date))
                            ->when($data['until'], fn ($query, $date) => $query->whereDate('created_at', '<=', $date));
                    })
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->selectRaw('MIN(sold_perfumes.id) as id, user_id') 
            ->selectRaw('SUM(quantity) as total_quantity')
            ->selectRaw('SUM(quantity * base_price) as total_revenue')
            // Isključujemo korisnike sa ulogom 'admin'
            ->whereHas('user', function ($query) {
                $query->whereDoesntHave('roles', function ($q) {
                    $q->where('name', 'admin');
                });
            })
            ->where('cancelled', false)
            ->groupBy('user_id');
    }

    public static function canCreate(): bool { return false; }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSellerRankings::route('/'),
        ];
    }
}