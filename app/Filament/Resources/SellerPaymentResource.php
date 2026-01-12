<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SellerPaymentResource\Pages;
use App\Models\SellerPayment;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;

class SellerPaymentResource extends Resource
{
    protected static ?string $model = SellerPayment::class;
    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';
    protected static ?string $navigationLabel = 'Isplate i Dugovi Prodavača';
    protected static ?string $navigationGroup = 'Finansije';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Detalji duga')
                    ->schema([
                        Forms\Components\Select::make('user_id')
                            ->relationship('user', 'name')
                            ->label('Prodavač')
                            ->required()
                            ->disabledOn('edit'), 

                        Forms\Components\TextInput::make('amount')
                            ->label('Iznos (KM)')
                            ->numeric()
                            ->prefix('KM')
                            ->required(),

                        Forms\Components\Select::make('status')
                            ->label('Status uplate')
                            ->options([
                                'Hold' => 'Kod prodavača',
                                'Pending' => 'Čeka potvrdu (Poslano)',
                                'Approved' => 'Odobreno / Plaćeno',
                            ])
                            ->required(),

                        Forms\Components\Textarea::make('complaint')
                            ->label('Reklamacija / Bilješka prodavača')
                            ->rows(3)
                            ->columnSpanFull()
                            ->placeholder('Nema aktivnih reklamacija'),
                    ])->columns(2)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Prodavač')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('amount')
                    ->label('Iznos')
                    ->money('bam', true)
                    ->color('primary')
                    ->weight('bold'),

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
                        'Hold' => 'Kod prodavača',
                        'Pending' => 'Čeka potvrdu',
                        'Approved' => 'Plaćeno',
                        default => $state,
                    }),

                Tables\Columns\IconColumn::make('complaint')
                    ->label('Žalba')
                    ->icon(fn ($state) => $state ? 'heroicon-m-chat-bubble-left-right' : null)
                    ->color('warning')
                    ->tooltip(fn (SellerPayment $record) => $record->complaint),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Datum')
                    ->dateTime('d/m/Y')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                // 1. FILTER PO PRODAVAČU
                Tables\Filters\SelectFilter::make('user')
                    ->relationship('user', 'name')
                    ->label('Prodavač')
                    ->searchable()
                    ->preload(),

                // 2. FILTER PO STATUSU
                Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'Hold' => 'Kod prodavača',
                        'Pending' => 'Čeka potvrdu',
                        'Approved' => 'Plaćeno',
                    ]),

                // 3. FILTER ZA REKLAMACIJE (Samo oni sa tekstom žalbe)
                Tables\Filters\TernaryFilter::make('complaint')
                    ->label('Ima reklamaciju')
                    ->placeholder('Sve uplate')
                    ->trueLabel('Samo sa reklamacijom')
                    ->falseLabel('Bez reklamacije')
                    ->queries(
                        true: fn (Builder $query) => $query->whereNotNull('complaint')->where('complaint', '!=', ''),
                        false: fn (Builder $query) => $query->whereNull('complaint')->orWhere('complaint', ''),
                        blank: fn (Builder $query) => $query,
                    ),

                // 4. FILTER PO DATUMU
                Tables\Filters\Filter::make('created_at')
                    ->form([
                        Forms\Components\DatePicker::make('from')->label('Od datuma'),
                        Forms\Components\DatePicker::make('until')->label('Do datuma'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when($data['from'], fn ($q, $date) => $q->whereDate('created_at', '>=', $date))
                            ->when($data['until'], fn ($q, $date) => $q->whereDate('created_at', '<=', $date));
                    })
            ])
            ->actions([
                Action::make('approvePayment')
                    ->label('Odobri')
                    ->icon('heroicon-m-check-badge')
                    ->color('success')
                    ->requiresConfirmation()
                    ->visible(fn (SellerPayment $record) => $record->status !== 'Approved')
                    ->action(function (SellerPayment $record) {
                        $record->update([
                            'status' => 'Approved',
                            'complaint' => null,
                        ]);

                        Notification::make()
                            ->title('Uplata odobrena')
                            ->success()
                            ->send();
                    }),

                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSellerPayments::route('/'),
            'create' => Pages\CreateSellerPayment::route('/create'),
            'edit' => Pages\EditSellerPayment::route('/{record}/edit'),
        ];
    }
}