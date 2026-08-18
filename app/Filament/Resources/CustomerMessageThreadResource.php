<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CustomerMessageThreadResource\Pages;
use App\Models\CustomerMessageThread;
use App\Services\Messaging;
use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class CustomerMessageThreadResource extends Resource
{
    protected static ?string $model = CustomerMessageThread::class;
    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-right';
    protected static ?string $navigationLabel = 'Poruke';
    protected static ?string $pluralModelLabel = 'Poruke';
    protected static ?string $modelLabel = 'Razgovor';
    protected static ?string $slug = 'messages';
    protected static ?string $navigationGroup = 'Administracija';

    public static function getNavigationBadge(): ?string
    {
        $n = Messaging::totalAdminUnread();
        return $n > 0 ? (string) $n : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'danger';
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('customer.full_name')
                    ->label('Kupac')
                    ->description(fn ($record) => $record->customer?->email ?? '')
                    ->searchable(['customers.full_name', 'customers.email'])
                    ->sortable(),

                Tables\Columns\TextColumn::make('admin_unread_count')
                    ->label('Nepročitano')
                    ->badge()
                    ->color(fn ($state) => $state > 0 ? 'danger' : 'gray'),

                Tables\Columns\TextColumn::make('last_message_at')
                    ->label('Zadnja poruka')
                    ->since()
                    ->sortable(),

                Tables\Columns\TextColumn::make('messages_count')
                    ->counts('messages')
                    ->label('Br. poruka')
                    ->alignCenter(),
            ])
            ->filters([
                Tables\Filters\Filter::make('unread_only')
                    ->label('Samo nepročitano')
                    ->query(fn ($q) => $q->where('admin_unread_count', '>', 0)),
            ])
            ->headerActions([
                Tables\Actions\Action::make('broadcast')
                    ->label('Pošalji svim kupcima')
                    ->icon('heroicon-o-megaphone')
                    ->color('primary')
                    ->form([
                        Forms\Components\Textarea::make('body')
                            ->label('Poruka')
                            ->required()
                            ->rows(6)
                            ->helperText('Šalje se svakom aktivnom kupcu — onima koji su nešto kupili (online ili direktno).'),
                    ])
                    ->requiresConfirmation()
                    ->modalHeading('Broadcast poruka svim kupcima')
                    ->modalDescription('Poruka će biti poslata svakom aktivnom kupcu i vidjet će je pri sljedećem otvaranju svog naloga. Kupci vide identitet "Sorenza".')
                    ->action(function (array $data) {
                        $count = Messaging::broadcast(Auth::user(), $data['body']);
                        Notification::make()
                            ->title("Broadcast poslat — {$count} kupaca")
                            ->success()
                            ->send();
                    }),
            ])
            ->actions([
                Tables\Actions\Action::make('open')
                    ->label('Otvori')
                    ->icon('heroicon-o-arrow-right')
                    ->url(fn ($record) => static::getUrl('view', ['record' => $record])),

                Tables\Actions\DeleteAction::make()
                    ->label('Obriši razgovor')
                    ->requiresConfirmation()
                    ->modalDescription('Sve poruke ovog razgovora biće trajno obrisane.'),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make()
                    ->label('Obriši označene razgovore')
                    ->requiresConfirmation()
                    ->modalDescription('Sve poruke unutar označenih razgovora biće trajno obrisane.'),

                Tables\Actions\BulkAction::make('purge_broadcasts_older_than')
                    ->label('Obriši broadcast poruke starije od…')
                    ->icon('heroicon-o-trash')
                    ->color('warning')
                    ->requiresConfirmation()
                    ->form([
                        Forms\Components\Select::make('days')
                            ->label('Starije od')
                            ->options([
                                '7'   => '7 dana',
                                '30'  => '30 dana',
                                '90'  => '90 dana',
                                '180' => '180 dana',
                                '365' => '1 godina',
                            ])
                            ->required(),
                    ])
                    ->action(function (array $data, $records) {
                        $cutoff = now()->subDays((int) $data['days']);
                        $threadIds = collect($records)->pluck('id')->all();
                        $deleted = \App\Models\CustomerMessage::whereIn('thread_id', $threadIds)
                            ->where('is_broadcast', true)
                            ->where('created_at', '<', $cutoff)
                            ->delete();
                        Notification::make()->title("Obrisano {$deleted} broadcast poruka")->success()->send();
                    }),
            ])
            ->defaultSort('last_message_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCustomerMessageThreads::route('/'),
            'view'  => Pages\ViewCustomerMessageThread::route('/{record}'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canAccess(): bool
    {
        return Auth::user()?->hasRole('admin') ?? false;
    }
}
