<?php

namespace App\Filament\Resources;

use App\Enums\PerfumeRequestStatus;
use App\Filament\Resources\PerfumeRequestResource\Pages;
use App\Models\PerfumeRequest;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PerfumeRequestResource extends Resource
{
    protected static ?string $model = PerfumeRequest::class;
    protected static ?string $navigationIcon = 'heroicon-o-inbox-arrow-down';
    protected static ?string $navigationLabel = 'Zahtjevi prodavača';
    protected static ?string $modelLabel = 'Zahtjev za parfem';
    protected static ?string $pluralModelLabel = 'Zahtjevi prodavača';
    protected static ?string $navigationGroup = 'Podrška';
    protected static ?int $navigationSort = 6;

    public static function getNavigationBadge(): ?string
    {
        $c = static::getModel()::where('status', PerfumeRequestStatus::PENDING->value)->count();
        return $c > 0 ? (string) $c : null;
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
                    Forms\Components\TextInput::make('seller.name')->label('Prodavač')->disabled(),
                    Forms\Components\TextInput::make('perfume.name')->label('Parfem')->disabled(),
                    Forms\Components\TextInput::make('quantity')->label('Količina')->suffix('kom')->disabled(),
                    Forms\Components\Textarea::make('note')->label('Napomena prodavača')->rows(3)->disabled()->columnSpanFull(),
                ])
                ->columns(3),

            Forms\Components\Section::make('Obrada')
                ->schema([
                    Forms\Components\Select::make('status')
                        ->label('Status')
                        ->options(collect(PerfumeRequestStatus::cases())
                            ->mapWithKeys(fn ($c) => [$c->value => $c->label()]))
                        ->required(),
                    Forms\Components\Textarea::make('admin_note')
                        ->label('Napomena admina')
                        ->rows(3)
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
                Tables\Columns\TextColumn::make('seller.name')
                    ->label('Prodavač')
                    ->searchable()
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('perfume.name')
                    ->label('Parfem')
                    ->searchable()
                    ->description(fn ($record) => $record->perfume?->inspired_by),
                Tables\Columns\TextColumn::make('quantity')
                    ->label('Količina')
                    ->badge()
                    ->color('info')
                    ->formatStateUsing(fn ($state) => $state . ' kom'),
                Tables\Columns\TextColumn::make('note')
                    ->label('Napomena')
                    ->limit(40)
                    ->tooltip(fn ($record) => $record->note)
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
                    ->options(collect(PerfumeRequestStatus::cases())
                        ->mapWithKeys(fn ($c) => [$c->value => $c->label()])),
            ])
            ->actions([
                Action::make('approve')
                    ->label('Odobri')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn ($record) => $record->status === PerfumeRequestStatus::PENDING)
                    ->requiresConfirmation()
                    ->modalHeading('Odobri zahtjev i dodaj u lager?')
                    ->modalDescription(fn ($record) =>
                        "Prodavaču {$record->seller?->name} će biti dodano {$record->quantity} kom parfema \"{$record->perfume?->name}\" na lager."
                    )
                    ->action(function (PerfumeRequest $record) {
                        DB::transaction(function () use ($record) {
                            $seller = $record->seller;
                            $perfumeId = $record->perfume_id;
                            $qty = (int) $record->quantity;

                            $existing = $seller->perfumes()
                                ->where('perfume_id', $perfumeId)
                                ->first()?->pivot;

                            if ($existing) {
                                $seller->perfumes()->updateExistingPivot($perfumeId, [
                                    'stock' => (int) $existing->stock + $qty,
                                ]);
                            } else {
                                $seller->perfumes()->attach($perfumeId, ['stock' => $qty]);
                            }

                            $record->update([
                                'status'      => PerfumeRequestStatus::APPROVED->value,
                                'approved_by' => Auth::id(),
                                'resolved_at' => now(),
                            ]);
                        });

                        Notification::make()
                            ->title('Zahtjev odobren')
                            ->body('Lager prodavača je ažuriran.')
                            ->success()
                            ->send();
                    }),

                Action::make('reject')
                    ->label('Odbij')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->visible(fn ($record) => $record->status === PerfumeRequestStatus::PENDING)
                    ->form([
                        Forms\Components\Textarea::make('admin_note')
                            ->label('Razlog odbijanja')
                            ->required(),
                    ])
                    ->action(function (PerfumeRequest $record, array $data) {
                        $record->update([
                            'status'      => PerfumeRequestStatus::REJECTED->value,
                            'admin_note'  => $data['admin_note'],
                            'approved_by' => Auth::id(),
                            'resolved_at' => now(),
                        ]);
                        Notification::make()->title('Zahtjev odbijen')->success()->send();
                    }),

                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->emptyStateHeading('Nema zahtjeva')
            ->emptyStateDescription('Zahtjevi prodavača za parfeme će se pojaviti ovdje.')
            ->emptyStateIcon('heroicon-o-inbox-arrow-down');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPerfumeRequests::route('/'),
            'edit'  => Pages\EditPerfumeRequest::route('/{record}/edit'),
        ];
    }

    public static function canCreate(): bool { return false; }
}
