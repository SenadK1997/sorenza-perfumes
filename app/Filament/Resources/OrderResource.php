<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\RelationManagers;
use App\Models\Order;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Enums\OrderStatus;
use Illuminate\Support\Facades\Auth;
use Filament\Tables\Actions\Action;
use Filament\Notifications\Notification;
use App\Enums\Canton;
use App\Models\User;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Navigation\NavigationItem;
use App\Filament\Resources\OrderResource\Pages\MyCompletedOrders;
use App\Filament\Resources\OrderPerfumesResource\RelationManagers\PerfumesRelationManager;
use Filament\Forms\Components\Repeater;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('full_name')->required()->maxLength(255),
            TextInput::make('phone')->required()->maxLength(20),
            TextInput::make('address_line_1')->required()->maxLength(255),
            TextInput::make('address_line_2')->maxLength(255),
            TextInput::make('city')->required()->maxLength(100),
            TextInput::make('zipcode')->required()->maxLength(20),
            TextInput::make('email')->email()->maxLength(255)->nullable(),

            Select::make('canton')
                ->options(self::getEnumOptions(Canton::class))
                ->required(),

            Select::make('status')
                ->options(self::getEnumOptions(OrderStatus::class))
                ->required(),

            TextInput::make('amount')->numeric()->required()->disabled(),

            Select::make('user_id')
                ->label('Assign to User')
                ->options(User::all()->pluck('name', 'id')->toArray())
                ->searchable()
                ->nullable(),

            TextInput::make('coupon')->maxLength(50)->nullable(),
            Repeater::make('perfumes')
                ->label('Perfumes in Order')
                ->disableLabel()
                ->relationship() // important, to link to the `perfumes()` relation
                ->schema([
                    Forms\Components\TextInput::make('name')
                        ->label('Perfume Name')
                        ->disabled(),
                    Forms\Components\TextInput::make('quantity')
                        ->label('Quantity')
                        ->disabled(),
                    Forms\Components\TextInput::make('price')
                        ->label('Price')
                        ->disabled(),
                ])
                ->columns(3)
                ->disabled()
                ->visible(fn ($record) => $record?->exists),
        ]);
    }
// TODO: COMPLETE ORDER TO IS MANUAL TRUEE ETC
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // Tables\Columns\TextColumn::make('id')->sortable(),
                Tables\Columns\TextColumn::make('full_name')->searchable(),
                // Tables\Columns\TextColumn::make('phone'),
                Tables\Columns\TextColumn::make('amount')->money('BAM'),
                // Tables\Columns\TextColumn::make('status')
                //     ->label('Order Status')
                //     ->formatStateUsing(fn ($state) => $state->label())
                //     ->badge(),
                // Tables\Columns\TextColumn::make('canton'),
                Tables\Columns\TextColumn::make('cancellation_reason')
                    ->label('Reason')
                    ->formatStateUsing(fn ($state) => $state ?: null)
                    ->hidden(fn ($record) => empty($record->cancellation_reason)),
                // Tables\Columns\TextColumn::make('created_at')->dateTime(),
                // Tables\Columns\TextColumn::make('user.name')->label('Seller')->sortable()->default('None')->searchable(),
            ])
            ->filters([
                // optional: add filters by status or canton
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Action::make('take')
                    ->label('Take')
                    ->visible(fn ($record) =>
                        Auth::user()?->hasAnyRole(['seller', 'admin']) &&
                        $record->user_id === null
                    )
                    ->requiresConfirmation()
                    ->color('success')
                    ->action(function ($record) {
                        $record->user_id = Auth::id();
                        $record->status = OrderStatus::TAKEN->value;
                        $record->save();

                        Notification::make()
                            ->title('Order successfully taken!')
                            ->success()
                            ->send();
                    }),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getEnumOptions(string $enumClass): array
    {
        return collect($enumClass::cases())
            ->mapWithKeys(fn ($case) => [$case->value => $case->label()])
            ->toArray();
    }

    public static function getRelations(): array
    {
        return [
            PerfumesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }

    // public static function getNavigationItems(): array
    // {
    //     return [
    //         NavigationItem::make('Orders')
    //             ->icon('heroicon-o-rectangle-stack')
    //             ->url(static::getUrl('index'))
    //             ->visible(fn () => auth()->user()->hasAnyRole(['admin', 'seller'])),

    //         NavigationItem::make('My Orders')
    //             ->icon('heroicon-o-clipboard')
    //             ->url(static::getUrl('my-orders'))
    //             ->visible(fn () => auth()->user()->hasRole('seller')),
    //     ];
    // }
    public static function canViewAny(): bool
    {
        return Auth::user()?->hasAnyRole(['admin', 'seller']) ?? false;
    }

    public static function canView($record): bool
    {
        return Auth::user()?->hasAnyRole(['admin', 'seller']);
    }

    public static function canEdit($record): bool
    {
        return Auth::user()?->hasRole('admin');
    }

    public static function canDelete($record): bool
    {
        return Auth::user()?->hasRole('admin');
    }

    public static function canCreate(): bool
    {
        return Auth::user()?->hasRole('admin');
    }
}
