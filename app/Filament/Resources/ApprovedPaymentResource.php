<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ApprovedPaymentResource\Pages;
use App\Models\SellerPayment;
use App\Models\Expense; // Obavezno uvezi model za troškove
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\Summarizers\Summarizer;
use Illuminate\Database\Eloquent\Builder;

class ApprovedPaymentResource extends Resource
{
    protected static ?string $model = SellerPayment::class;

    protected static ?string $navigationIcon = 'heroicon-o-check-circle';
    protected static ?string $navigationLabel = 'Primljeni Novac (Arhiva)';
    protected static ?string $navigationGroup = 'Finansije';
    protected static ?int $navigationSort = 2;

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Prodavač')
                    ->sortable(),

                Tables\Columns\TextColumn::make('amount')
                    ->label('Primljeni Iznos')
                    ->money('bam', true)
                    ->color('success')
                    ->weight('bold')
                    ->summarize([
                        // 1. Ukupne uplate od prodavača
                        Tables\Columns\Summarizers\Sum::make()
                            ->label('Ukupno uplate')
                            ->money('bam', true),

                        // 2. Ukupni troškovi (iz druge tabele)
                        Tables\Columns\Summarizers\Summarizer::make()
                            ->label('Ukupno troškovi')
                            ->using(fn () => Expense::sum('amount'))
                            ->money('bam', true),

                        // 3. Neto stanje (Uplate - Troškovi)
                        Tables\Columns\Summarizers\Summarizer::make()
                            ->label('NETO OSTATAK')
                            ->using(function ($query) {
                                // Suma trenutno filtriranih uplata
                                $totalApproved = $query->sum('amount');
                                // Suma svih troškova
                                $totalExpenses = Expense::sum('amount');
                                
                                return $totalApproved - $totalExpenses;
                            })
                            ->money('bam', true),
                    ]),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Datum uplate')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\Filter::make('created_at')
                    ->form([
                        \Filament\Forms\Components\DatePicker::make('from')->label('Od datuma'),
                        \Filament\Forms\Components\DatePicker::make('until')->label('Do datuma'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when($data['from'], fn ($q, $date) => $q->whereDate('updated_at', '>=', $date))
                            ->when($data['until'], fn ($q, $date) => $q->whereDate('updated_at', '<=', $date));
                    })
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        // Prikazujemo samo odobrene uplate
        return parent::getEloquentQuery()
            ->with(['user']) // Eager loading za performanse
            ->where('status', 'Approved');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListApprovedPayments::route('/'),
        ];
    }
}