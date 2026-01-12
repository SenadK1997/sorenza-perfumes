<?php

namespace App\Filament\Seller\Resources;

use App\Filament\Seller\Resources\OrderResource\Pages;
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
use App\Filament\Seller\Pages\MyCompletedOrders;
use App\Filament\Seller\Pages\MyCancelledOrders;
use App\Filament\Seller\Pages\MyOrders;
use Filament\Forms\Components\Repeater;
use App\Filament\Resources\OrderPerfumesResource\RelationManagers\PerfumesRelationManager;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;
    protected static ?string $modelLabel = 'Narudžba';
    protected static ?string $pluralModelLabel = 'Narudžbe';
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'Nove Narudžbe';
    protected static ?string $navigationGroup = 'Narudžbe';
    protected static ?int $navigationSort = 1;

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
                Tables\Columns\TextColumn::make('canton'),
                // Tables\Columns\TextColumn::make('created_at')->dateTime(),
                // Tables\Columns\TextColumn::make('user.name')->label('Seller')->sortable()->default('None')->searchable(),
            ])
            ->filters([
                // optional: add filters by status or canton
            ])
            ->actions([
                // Ovaj ViewAction mijenja Edit stranicu - otvara se kao moderan Slide-over sa desne strane
                Tables\Actions\ViewAction::make()
                    ->modalHeading('Detalji narudžbe')
                    ->modalWidth('2xl') 
                    ->slideOver(),

                // OVDJE JE BILA GREŠKA: EditAction je izbačen jer Seller panel nema definisanu rutu za edit.
                
                Action::make('take')
                    ->label('Take')
                    ->visible(fn () => Auth::user()?->hasAnyRole(['seller', 'admin']))
                    ->requiresConfirmation()
                    ->color('success')
                    ->icon('heroicon-m-hand-raised')
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
                // Admin i dalje može raditi bulk delete ako mu zatreba kroz seller panel (zbog tvog canDelete uslova)
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
            // 'view' => Pages\ViewOrder::route('/{record}'),
        ];
    }

    // public static function getNavigationItems(): array
    // {
    //     return [
    //         NavigationItem::make('Narudžbe') // Promijenjeno sa 'Orders'
    //             ->icon('heroicon-o-rectangle-stack')
    //             ->url(static::getUrl('index'))
    //             ->visible(fn () => auth()->user()->hasAnyRole(['admin', 'seller'])),
    //     ];
    // }
    public static function canViewAny(): bool
    {
        return Auth::user()?->hasAnyRole(['admin', 'seller']) ?? false;
    }

    public static function canView($record): bool
    {
        return Auth::user()?->hasAnyRole(['admin', 'seller']) ?? false;
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
    public static function getEloquentQuery(): Builder
    {
        // Osiguravamo da se vide samo narudžbe koje NEMAJU dodijeljenog korisnika
        // i koje su u statusu PENDING (ili tvom početnom statusu)
        return parent::getEloquentQuery()
            ->whereNull('user_id')
            ->where('status', OrderStatus::PENDING->value);
    }
}
