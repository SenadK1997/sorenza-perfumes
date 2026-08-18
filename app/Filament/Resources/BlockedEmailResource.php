<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BlockedEmailResource\Pages;
use App\Models\BlockedEmail;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class BlockedEmailResource extends Resource
{
    protected static ?string $model = BlockedEmail::class;

    protected static ?string $navigationIcon = 'heroicon-o-envelope-open';
    protected static ?string $navigationLabel = 'Blokirane email adrese';
    protected static ?string $pluralModelLabel = 'Blokirane email adrese';
    protected static ?string $modelLabel = 'Blokirana email';
    protected static ?string $slug = 'blocked-emails';
    protected static ?string $navigationGroup = 'Administracija';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('email')
                ->label('Email adresa')
                ->email()
                ->required()
                ->unique(ignoreRecord: true)
                ->maxLength(255)
                ->placeholder('npr. spam@example.com'),

            Forms\Components\TextInput::make('reason')
                ->label('Razlog')
                ->maxLength(255)
                ->placeholder('npr. Više puta otkazivao/la narudžbu'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('email')
                    ->label('Email adresa')
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
            'index'  => Pages\ListBlockedEmails::route('/'),
            'create' => Pages\CreateBlockedEmail::route('/create'),
            'edit'   => Pages\EditBlockedEmail::route('/{record}/edit'),
        ];
    }
}
