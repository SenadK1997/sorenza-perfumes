<?php

namespace App\Filament\Seller\Resources;

use App\Filament\Seller\Resources\SellerPaymentResource\Pages;
use App\Models\SellerPayment;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions\Action;
use Filament\Forms\Components\Textarea;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class SellerPaymentResource extends Resource
{
    protected static ?string $model = SellerPayment::class;
    protected static ?string $navigationIcon = 'heroicon-o-credit-card';
    protected static ?string $navigationLabel = 'Moji Dugovi';
    protected static ?int $navigationSort = 5;

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('amount')
                    ->label('Iznos za predati')
                    ->money('bam', true)
                    ->sortable(),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Hold' => 'danger',
                        'Pending' => 'warning',
                        'Approved' => 'success',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'Hold' => 'Kod mene',
                        'Pending' => 'Poslano na potvrdu',
                        'Approved' => 'Završeno/Plaćeno',
                        default => $state,
                    }),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Datum zaduženja')
                    ->dateTime('d/m/Y')
                    ->sortable(),
            ])
            ->actions([
                // AKCIJA ZA PRIJAVU UPLATE
                Action::make('markAsPending')
                    ->label('Predao sam novac')
                    ->icon('heroicon-m-banknotes')
                    ->color('success')
                    ->requiresConfirmation()
                    ->visible(fn (SellerPayment $record) => $record->status === 'Hold')
                    ->action(fn (SellerPayment $record) => $record->update(['status' => 'Pending'])),

                // AKCIJA ZA ŽALBU (COMPLAIN)

                Action::make('complain')
                    ->label('Reklamacija')
                    ->icon('heroicon-m-chat-bubble-left-ellipsis')
                    ->color('warning')
                    ->modalHeading('Prijavi problem sa dugom')
                    ->form([
                        Textarea::make('complaint') // Naziv polja mora biti isti kao u migraciji
                            ->label('Objasnite razlog (npr. stornirani parfemi)')
                            ->placeholder('Napišite zašto smatrate da dug treba korigovati...')
                            ->required(),
                    ])
                    ->action(function (SellerPayment $record, array $data) {
                        $record->update([
                            'complaint' => $data['complaint']
                        ]);

                        \Filament\Notifications\Notification::make()
                            ->title('Reklamacija poslana')
                            ->body('Admin je obaviješten i pregledat će vašu poruku.')
                            ->success()
                            ->send();
                    })
                    ->visible(fn (SellerPayment $record) => $record->status !== 'Approved'), // Ne može se žaliti na već plaćeno
            ])
            ->bulkActions([]);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('user_id', Auth::id())
            ->latest();
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSellerPayments::route('/'),
        ];
    }
    public static function canCreate(): bool
    {
        // Ovo će sakriti "New" ili "Create" dugme u headeru tabele
        return false;
    }

    // Opcionalno, ako želiš spriječiti i brisanje ili editovanje od strane prodavača:
    public static function canEdit($record): bool
    {
        return false;
    }

    public static function canDelete($record): bool
    {
        return false;
    }
}