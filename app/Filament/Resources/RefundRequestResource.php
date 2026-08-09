<?php

namespace App\Filament\Resources;

use App\Enums\RefundStatus;
use App\Filament\Resources\RefundRequestResource\Pages;
use App\Models\RefundRequest;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions\Action;

class RefundRequestResource extends Resource
{
    protected static ?string $model = RefundRequest::class;
    protected static ?string $navigationIcon = 'heroicon-o-arrow-uturn-left';
    protected static ?string $navigationLabel = 'Zahtjevi za povrat';
    protected static ?string $modelLabel = 'Zahtjev za povrat';
    protected static ?string $pluralModelLabel = 'Zahtjevi za povrat';
    protected static ?string $navigationGroup = 'Podrška';
    protected static ?int $navigationSort = 5;

    public static function getNavigationBadge(): ?string
    {
        $count = static::getModel()::where('status', RefundStatus::PENDING->value)->count();
        return $count > 0 ? (string) $count : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Zahtjev')
                ->schema([
                    Forms\Components\TextInput::make('order.pretty_id')
                        ->label('Broj narudžbe')
                        ->disabled(),
                    Forms\Components\TextInput::make('customer_name')->label('Kupac')->disabled(),
                    Forms\Components\TextInput::make('customer_email')->label('Email')->disabled(),
                    Forms\Components\TextInput::make('customer_phone')->label('Telefon')->disabled(),
                    Forms\Components\Textarea::make('reason')
                        ->label('Razlog kupca')
                        ->disabled()
                        ->rows(4)
                        ->columnSpanFull(),
                ])
                ->columns(2),

            Forms\Components\Section::make('Obrada')
                ->schema([
                    Forms\Components\Select::make('status')
                        ->label('Status')
                        ->options(collect(RefundStatus::cases())->mapWithKeys(fn ($c) => [$c->value => $c->label()]))
                        ->required(),
                    Forms\Components\Textarea::make('admin_response')
                        ->label('Odgovor kupcu / interna napomena')
                        ->rows(4)
                        ->columnSpanFull(),
                ])
                ->columns(2),
        ]);
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
                    ->description(fn ($record) => $record->customer_email),
                Tables\Columns\TextColumn::make('reason')
                    ->label('Razlog')
                    ->wrap()
                    ->limit(60)
                    ->tooltip(fn ($record) => $record->reason),
                Tables\Columns\TextColumn::make('order.amount')
                    ->label('Iznos')
                    ->money('BAM'),
                Tables\Columns\TextColumn::make('seller.name')
                    ->label('Prodavač')
                    ->placeholder('—'),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn ($state) => $state->label())
                    ->color(fn ($state) => $state->color()),
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
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Action::make('approve')
                    ->label('Odobri')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn ($record) => $record->status === RefundStatus::PENDING)
                    ->requiresConfirmation()
                    ->action(function ($record) {
                        $record->update([
                            'status'              => RefundStatus::APPROVED->value,
                            'resolved_by_user_id' => \Illuminate\Support\Facades\Auth::id(),
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
                            ->label('Razlog odbijanja')
                            ->required(),
                    ])
                    ->action(function ($record, array $data) {
                        $record->update([
                            'status'              => RefundStatus::REJECTED->value,
                            'admin_response'      => $data['admin_response'],
                            'resolved_by_user_id' => \Illuminate\Support\Facades\Auth::id(),
                            'resolved_at'         => now(),
                        ]);
                        Notification::make()->title('Zahtjev odbijen')->success()->send();
                    }),
            ])
            ->emptyStateHeading('Nema zahtjeva za povrat')
            ->emptyStateDescription('Ovdje će se pojaviti kada kupac zatraži povrat.')
            ->emptyStateIcon('heroicon-o-arrow-uturn-left');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRefundRequests::route('/'),
            'edit'  => Pages\EditRefundRequest::route('/{record}/edit'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }
}
