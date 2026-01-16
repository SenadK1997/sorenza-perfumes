<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ImageResource\Pages;
use App\Models\Image;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Support\Str;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class ImageResource extends Resource
{
    protected static ?string $model = Image::class;

    protected static ?string $navigationIcon = 'heroicon-o-photo';

    protected static ?string $navigationLabel = 'Slike (Media)';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Upload Slike')
                    ->description('Ovdje možete dodati nove slike. Ime fajla će biti generisano na osnovu Naziva.')
                    ->schema([
                        TextInput::make('name')
                            ->label('Naziv slike')
                            ->placeholder('npr. Man Perfume Hero')
                            ->required()
                            ->live(onBlur: true),

                        FileUpload::make('path')
                            ->label('Slika')
                            ->directory('images')
                            ->disk('public')
                            ->image()
                            ->imageEditor()
                            ->imageEditorAspectRatios([
                                null,
                                '16:9',
                                '4:3',
                                '1:1',
                            ])
                            // Automatski popuni "name" polje iz imena fajla ako je prazno
                            ->afterStateUpdated(function (Forms\Set $set, $state) {
                                if ($state instanceof TemporaryUploadedFile) {
                                    $set('name', pathinfo($state->getClientOriginalName(), PATHINFO_FILENAME));
                                }
                            })
                            // Preimenuje fajl na disku koristeći slug iz polja "name"
                            ->getUploadedFileNameForStorageUsing(function (Forms\Get $get, TemporaryUploadedFile $file): string {
                                $name = $get('name') ?? 'image';
                                return (string) Str::slug($name) . '.' . $file->getClientOriginalExtension();
                            })
                            ->maxSize(2048) 
                            ->required()
                            ->helperText('Maksimalna veličina: 2MB. Prvo odaberite sliku, pa prepravite Naziv ako želite drugo ime fajla.'),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('path')
                    ->label('Pregled')
                    ->disk('public')
                    ->square(),

                TextColumn::make('name')
                    ->label('Naziv')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('path')
                    ->label('Putanja (URL)')
                    ->copyable()
                    ->color('gray')
                    ->fontFamily('mono'),

                TextColumn::make('created_at')
                    ->label('Dodano')
                    ->dateTime('d.m.Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([])
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListImages::route('/'),
            'create' => Pages\CreateImage::route('/create'),
            'edit' => Pages\EditImage::route('/{record}/edit'),
        ];
    }

    // --- PERMISIJE: Vidljivo samo tebi ---

    public static function canViewAny(): bool
    {
        return auth()->user()?->email === 'senad.okt97@gmail.com';
    }

    public static function canDelete($record): bool
    {
        return auth()->user()?->email === 'senad.okt97@gmail.com';
    }

    public static function canCreate(): bool
    {
        return auth()->user()?->email === 'senad.okt97@gmail.com';
    }

    public static function canEdit($record): bool
    {
        return auth()->user()?->email === 'senad.okt97@gmail.com';
    }
}