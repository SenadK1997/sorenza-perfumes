<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Models\Order;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use App\Enums\OrderStatus;
use Illuminate\Support\Facades\Auth;
use Filament\Tables\Actions\Action;
use Filament\Notifications\Notification;
use App\Enums\Canton;
use App\Models\User;
use App\Filament\Resources\OrderPerfumesResource\RelationManagers\PerfumesRelationManager;
use Filament\Navigation\NavigationItem;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'Narudžbe (Admin)';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('full_name')->required(),
            Forms\Components\TextInput::make('phone')->required(),
            Forms\Components\TextInput::make('city')->required(),
            Forms\Components\Select::make('canton')
                ->options(self::getEnumOptions(Canton::class))
                ->required(),
            Forms\Components\Select::make('status')
                ->options(self::getEnumOptions(OrderStatus::class))
                ->required(),
            Forms\Components\TextInput::make('amount')->numeric()->disabled(),
            Forms\Components\Select::make('user_id')
                ->label('Dodijeljeno prodavaču')
                ->relationship('user', 'name')
                ->placeholder('Slobodna narudžba')
                ->searchable(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->sortable(),
                Tables\Columns\TextColumn::make('full_name')->label('Kupac')->searchable(),
                Tables\Columns\TextColumn::make('amount')->money('BAM')->label('Iznos'),
                
                // Prikazujemo prodavača koji je preuzeo
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Preuzeo')
                    ->placeholder('Niko (Slobodno)')
                    ->badge()
                    ->color(fn ($state) => $state ? 'warning' : 'success'),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->formatStateUsing(fn ($state) => $state->label())
                    ->badge(),
                    
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Datum')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Action::make('take')
                    ->label('Take')
                    ->visible(fn ($record) => $record->user_id === null)
                    ->requiresConfirmation()
                    ->color('success')
                    ->action(function ($record) {
                        $record->update([
                            'user_id' => Auth::id(),
                            'status' => OrderStatus::TAKEN->value
                        ]);
                        Notification::make()->title('Preuzeto!')->success()->send();
                    }),
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        // Admin vidi sve, uključujući i tuđe Taken narudžbe
        return parent::getEloquentQuery();
    }

    public static function getEnumOptions(string $enumClass): array
    {
        return collect($enumClass::cases())
            ->mapWithKeys(fn ($case) => [$case->value => $case->label()])
            ->toArray();
    }

    public static function getRelations(): array
    {
        return [PerfumesRelationManager::class];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'completed' => Pages\CompletedOrders::route('/completed'),
            'cancelled' => Pages\CancelledOrders::route('/cancelled'),
            'create' => Pages\CreateOrder::route('/create'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
    public static function getNavigationItems(): array
    {
        return [
            // Glavna lista (Sve/Slobodne/Taken)
            NavigationItem::make('Aktivne Narudžbe')
                ->icon('heroicon-o-rectangle-stack')
                ->group('Prodaja')
                ->url(static::getUrl('index')),

            // Završene
            NavigationItem::make('Završene Narudžbe')
                ->icon('heroicon-o-check-circle')
                ->group('Prodaja')
                ->url(static::getUrl('completed')),

            // Otkazane
            NavigationItem::make('Otkazane Narudžbe')
                ->icon('heroicon-o-x-circle')
                ->group('Prodaja')
                ->url(static::getUrl('cancelled')),
        ];
    }

    public static function canViewAny(): bool { return Auth::user()?->hasAnyRole(['admin', 'seller']); }
    public static function canEdit($record): bool { return Auth::user()?->hasRole('admin'); }
    public static function canDelete($record): bool { return Auth::user()?->hasRole('admin'); }
}