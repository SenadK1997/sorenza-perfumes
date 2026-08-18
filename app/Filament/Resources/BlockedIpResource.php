<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BlockedIpResource\Pages;
use App\Models\BlockedIp;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class BlockedIpResource extends Resource
{
    protected static ?string $model = BlockedIp::class;

    protected static ?string $navigationIcon = 'heroicon-o-shield-exclamation';
    protected static ?string $navigationLabel = 'Blokirane IP adrese';
    protected static ?string $pluralModelLabel = 'Blokirane IP adrese';
    protected static ?string $modelLabel = 'Blokirana IP';
    protected static ?string $slug = 'blocked-ips';
    protected static ?string $navigationGroup = 'Administracija';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('ip')
                ->label('IP adresa')
                ->required()
                ->unique(ignoreRecord: true)
                ->maxLength(45)
                ->placeholder('npr. 192.168.1.10'),

            Forms\Components\TextInput::make('reason')
                ->label('Razlog')
                ->maxLength(255)
                ->placeholder('npr. Spam narudžbi'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('ip')
                    ->label('IP adresa')
                    ->searchable()
                    ->copyable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('reason')
                    ->label('Razlog')
                    ->searchable()
                    ->placeholder('—'),

                Tables\Columns\TextColumn::make('blocked_at')
                    ->label('Blokirana')
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()->label('Odblokiraj'),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make()->label('Odblokiraj označene'),
            ])
            ->defaultSort('blocked_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListBlockedIps::route('/'),
            'create' => Pages\CreateBlockedIp::route('/create'),
            'edit'   => Pages\EditBlockedIp::route('/{record}/edit'),
        ];
    }
}
