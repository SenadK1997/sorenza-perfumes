<?php

namespace App\Filament\Seller\Resources;

use App\Filament\Seller\Resources\SellerRankingResource\Pages;
use App\Models\SoldPerfume;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class SellerRankingResource extends Resource
{
    protected static ?string $model = SoldPerfume::class;
    protected static ?string $navigationIcon = 'heroicon-o-trophy';
    protected static ?string $navigationLabel = 'Rang Lista';
    protected static ?string $pluralModelLabel = 'Rang Lista Prodavača';
    protected static ?string $navigationGroup = 'Moja Statistika';
    protected static ?int $navigationSort = 2;

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('index')
                    ->label('#')
                    ->rowIndex(),

                TextColumn::make('user.name')
                    ->label('Prodavač')
                    ->formatStateUsing(fn ($state, $record) => $record->user_id === Auth::id() ? "⭐ {$state} (TI)" : $state)
                    // Highlight boje teksta ako je ulogovan korisnik
                    ->color(fn ($record) => $record->user_id === Auth::id() ? 'primary' : null)
                    ->weight(fn ($record) => $record->user_id === Auth::id() ? 'bold' : 'medium')
                    ->icon(fn ($rowLoop, $record): ?string => 
                        match ($rowLoop?->iteration) {
                            1 => 'heroicon-s-trophy',
                            2 => 'heroicon-m-academic-cap',
                            3 => 'heroicon-s-star',
                            default => $record->user_id === Auth::id() ? 'heroicon-m-user' : null,
                        }
                    )
                    ->iconColor(fn ($rowLoop, $record): ?string => 
                        match ($rowLoop?->iteration) {
                            1 => 'warning',
                            2 => 'gray',
                            3 => 'danger',
                            default => 'primary',
                        }
                    ),

                TextColumn::make('total_revenue')
                    ->label('Ukupan promet')
                    ->money('BAM')
                    ->sortable()
                    ->badge()
                    ->color(fn ($record) => $record->user_id === Auth::id() ? 'primary' : 'success'),

                TextColumn::make('total_quantity')
                    ->label('Prodano komada')
                    ->alignCenter(),
            ])
            // Alternativni način za highlight: Boja cijelog reda preko inline stila
            ->recordClasses(fn ($record) => $record->user_id === Auth::id() 
                ? 'bg-primary-50 dark:bg-primary-950/50 border-l-4 border-primary-600' 
                : null
            )
            ->defaultSort('total_revenue', 'desc')
            ->filters([
                Filter::make('created_at')
                    ->form([
                        DatePicker::make('from')
                            ->label('Od datuma')
                            ->default(Carbon::now()->startOfMonth()),
                        DatePicker::make('until')
                            ->label('Do datuma')
                            ->default(Carbon::now()->endOfMonth()),
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
            ->whereHas('user', function ($query) {
                $query->whereDoesntHave('roles', function ($q) {
                    $q->where('name', 'admin');
                });
            })
            ->where('cancelled', false)
            ->groupBy('user_id');
    }

    public static function canCreate(): bool { return false; }
    public static function canEdit($record): bool { return false; }
    public static function canDelete($record): bool { return false; }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSellerRankings::route('/'),
        ];
    }
}