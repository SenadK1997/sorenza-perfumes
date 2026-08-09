<?php

namespace App\Filament\Seller\Resources;

use App\Enums\RefundStatus;
use App\Filament\Seller\Resources\RefundRequestResource\Pages;
use App\Models\RefundRequest;
use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class RefundRequestResource extends Resource
{
    protected static ?string $model = RefundRequest::class;
    protected static ?string $navigationIcon = 'heroicon-o-arrow-uturn-left';
    protected static ?string $navigationLabel = 'Moji povrati';
    protected static ?string $modelLabel = 'Zahtjev za povrat';
    protected static ?string $pluralModelLabel = 'Zahtjevi za povrat';
    protected static ?string $navigationGroup = 'Podrška';
    protected static ?int $navigationSort = 5;

    /** Scope: seller sees only refund requests for orders assigned to them. */
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('user_id', Auth::id());
    }

    public static function getNavigationBadge(): ?string
    {
        $count = static::getEloquentQuery()
            ->where('status', RefundStatus::PENDING->value)
            ->count();
        return $count > 0 ? (string) $count : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('order.pretty_id')
                    ->label('Narudžba')
                    ->searchable()
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('customer_name')
                    ->label('Kupac')
                    ->searchable()
                    ->description(fn ($record) => $record->customer_phone ?? $record->customer_email),
                Tables\Columns\TextColumn::make('reason')
                    ->label('Razlog')
                    ->wrap()
                    ->limit(80)
                    ->tooltip(fn ($record) => $record->reason),
                Tables\Columns\TextColumn::make('order.amount')
                    ->label('Iznos')
                    ->money('BAM'),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn ($state) => $state->label())
                    ->color(fn ($state) => $state->color()),
                Tables\Columns\TextColumn::make('resolver.name')
                    ->label('Riješio')
                    ->placeholder('—')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Poslano')
                    ->since()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options(collect(RefundStatus::cases())->mapWithKeys(fn ($c) => [$c->value => $c->label()])),
            ])
            ->actions([
                Action::make('approve')
                    ->label('Odobri')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn ($record) => $record->status === RefundStatus::PENDING)
                    ->requiresConfirmation()
                    ->modalHeading('Odobri povrat?')
                    ->modalDescription(fn ($record) => "Odobravate povrat za narudžbu #{$record->order?->pretty_id} kupca {$record->customer_name}.")
                    ->action(function (RefundRequest $record) {
                        $record->update([
                            'status'              => RefundStatus::APPROVED->value,
                            'resolved_by_user_id' => Auth::id(),
                            'resolved_at'         => now(),
                        ]);
                        Notification::make()->title('Zahtjev odobren')->success()->send();
                    }),

                Action::make('reject')
                    ->label('Odbij')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->visible(fn ($record) => $record->status === RefundStatus::PENDING)
                    ->form([
                        Forms\Components\Textarea::make('admin_response')
                            ->label('Razlog odbijanja (vidjet će kupac)')
                            ->required()
                            ->minLength(5),
                    ])
                    ->action(function (RefundRequest $record, array $data) {
                        $record->update([
                            'status'              => RefundStatus::REJECTED->value,
                            'admin_response'      => $data['admin_response'],
                            'resolved_by_user_id' => Auth::id(),
                            'resolved_at'         => now(),
                        ]);
                        Notification::make()->title('Zahtjev odbijen')->success()->send();
                    }),

                Tables\Actions\ViewAction::make(),
            ])
            ->emptyStateHeading('Nema zahtjeva za povrat')
            ->emptyStateDescription('Kada kupac Vaše narudžbe zatraži povrat, pojaviti će se ovdje.')
            ->emptyStateIcon('heroicon-o-arrow-uturn-left');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRefundRequests::route('/'),
            'view'  => Pages\ViewRefundRequest::route('/{record}'),
        ];
    }

    public static function canCreate(): bool { return false; }
    public static function canEdit($record): bool { return false; }
    public static function canDelete($record): bool { return false; }
}
