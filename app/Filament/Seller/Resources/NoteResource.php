<?php

namespace App\Filament\Seller\Resources;

use App\Filament\Seller\Resources\NoteResource\Pages;
use App\Models\Note;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class NoteResource extends Resource
{
    protected static ?string $model = Note::class;

    protected static ?string $navigationIcon = 'heroicon-o-pencil-square';
    
    protected static ?string $navigationLabel = 'Moje Bilješke';
    
    protected static ?string $pluralModelLabel = 'Bilješke';

    /**
     * SPREČAVANJE PRISTUPA TUĐIM BILJEŠKAMA
     * Osigurava da prodavač vidi samo podatke gdje je user_id jednak njegovom ID-u
     */
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('user_id', auth()->id());
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Nova Bilješka')
                    ->description('Zapišite važne podsjetnike ili detalje o prodaji.')
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->label('Naslov')
                            ->placeholder('Npr. Nazvati kupca za sutra')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),

                        Forms\Components\Textarea::make('description')
                            ->label('Detaljan opis')
                            ->placeholder('Unesite više detalja ovdje...')
                            ->rows(4)
                            ->columnSpanFull(),

                        Forms\Components\Toggle::make('is_done')
                            ->label('Označi kao završeno')
                            ->default(false)
                            ->onColor('success')  // Ispravljeno
                            ->offColor('danger'), // Ispravljeno

                        // Automatsko dodjeljivanje vlasnika bilješke
                        Forms\Components\Hidden::make('user_id')
                            ->default(auth()->id()),
                    ])->columns(1),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\IconColumn::make('is_done')
                    ->label('Status')
                    ->boolean()
                    ->sortable(),

                Tables\Columns\TextColumn::make('title')
                    ->label('Naslov bilješke')
                    ->searchable()
                    ->sortable()
                    ->wrap(), // Omogućava tekst u više redova ako je dug

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Datum kreiranja')
                    ->dateTime('d.m.Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),
            ])
            ->defaultSort('created_at', 'desc') // Najnovije bilješke idu prve
            ->filters([
                Tables\Filters\TernaryFilter::make('is_done')
                    ->label('Status završetka')
                    ->placeholder('Sve bilješke')
                    ->trueLabel('Samo završene')
                    ->falseLabel('Samo aktivne'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListNotes::route('/'),
            'create' => Pages\CreateNote::route('/create'),
            'edit' => Pages\EditNote::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('user_id', auth()->id())
            ->where('is_done', false)
            ->count() ?: null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'danger'; // Crveni kružić ako ima aktivnih bilješki
    }
}