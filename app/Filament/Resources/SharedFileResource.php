<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SharedFileResource\Pages;
use App\Models\SharedFile;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class SharedFileResource extends Resource
{
    protected static ?string $model = SharedFile::class;
    protected static ?string $navigationIcon = 'heroicon-o-folder-arrow-down';
    protected static ?string $navigationLabel = 'Fajlovi za prodavače';
    protected static ?string $modelLabel = 'Fajl';
    protected static ?string $pluralModelLabel = 'Fajlovi';
    protected static ?string $navigationGroup = 'Sistem';
    protected static ?int $navigationSort = 15;

    public static function canAccess(): bool
    {
        return Auth::user()?->hasRole('admin') ?? false;
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Detalji fajla')
                ->schema([
                    Forms\Components\TextInput::make('title')
                        ->label('Naziv')
                        ->required()
                        ->placeholder('Npr. Katalog Sorénza — Ljeto 2026')
                        ->maxLength(160)
                        ->columnSpanFull(),

                    Forms\Components\Select::make('category')
                        ->label('Kategorija')
                        ->options([
                            'catalog'   => 'Katalog',
                            'price'     => 'Cjenovnik',
                            'guide'     => 'Uputstvo',
                            'marketing' => 'Marketing materijal',
                            'other'     => 'Ostalo',
                        ])
                        ->default('catalog')
                        ->native(false),

                    Forms\Components\Textarea::make('description')
                        ->label('Opis (opcionalno)')
                        ->rows(2)
                        ->maxLength(500)
                        ->columnSpanFull(),

                    Forms\Components\FileUpload::make('path')
                        ->label('Fajl')
                        ->required()
                        ->disk('public')
                        ->directory('shared-files')
                        ->preserveFilenames()
                        ->maxSize(50 * 1024) // 50 MB
                        ->acceptedFileTypes([
                            'application/pdf',
                            'application/vnd.ms-excel',
                            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                            'application/msword',
                            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                            'application/zip',
                            'image/jpeg', 'image/png', 'image/webp',
                        ])
                        ->helperText('PDF, Word, Excel, ZIP, ili slika. Max 50 MB.')
                        ->afterStateUpdated(function ($state, callable $set) {
                            if (! $state) return;
                            // $state is a TemporaryUploadedFile
                            try {
                                $set('original_name', $state->getClientOriginalName());
                                $set('mime_type',     $state->getMimeType());
                                $set('size_bytes',    $state->getSize());
                            } catch (\Throwable $e) { /* ignore */ }
                        })
                        ->columnSpanFull(),

                    Forms\Components\Hidden::make('original_name'),
                    Forms\Components\Hidden::make('mime_type'),
                    Forms\Components\Hidden::make('size_bytes'),
                ])
                ->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\IconColumn::make('icon')
                    ->label('')
                    ->icon(fn ($record) => $record->icon)
                    ->color('warning')
                    ->size('lg'),

                Tables\Columns\TextColumn::make('title')
                    ->label('Naziv')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->description(fn ($record) => $record->description),

                Tables\Columns\TextColumn::make('category')
                    ->label('Kategorija')
                    ->badge()
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'catalog'   => 'Katalog',
                        'price'     => 'Cjenovnik',
                        'guide'     => 'Uputstvo',
                        'marketing' => 'Marketing',
                        'other'     => 'Ostalo',
                        default     => $state,
                    })
                    ->color(fn ($state) => match ($state) {
                        'catalog'   => 'primary',
                        'price'     => 'success',
                        'guide'     => 'info',
                        'marketing' => 'warning',
                        default     => 'gray',
                    }),

                Tables\Columns\TextColumn::make('size_human')
                    ->label('Veličina')
                    ->getStateUsing(fn ($record) => $record->size_human),

                Tables\Columns\TextColumn::make('uploader.name')
                    ->label('Postavio')
                    ->placeholder('—')
                    ->toggleable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Postavljeno')
                    ->since()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('category')
                    ->options([
                        'catalog' => 'Katalog',
                        'price'   => 'Cjenovnik',
                        'guide'   => 'Uputstvo',
                        'marketing' => 'Marketing',
                        'other'   => 'Ostalo',
                    ]),
            ])
            ->actions([
                Tables\Actions\Action::make('download')
                    ->label('Preuzmi')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('success')
                    ->url(fn ($record) => $record->url, shouldOpenInNewTab: true),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ])
            ->emptyStateHeading('Nema fajlova')
            ->emptyStateDescription('Postavite prvi fajl da ga prodavači vide na svom panelu.')
            ->emptyStateIcon('heroicon-o-folder-arrow-down');
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListSharedFiles::route('/'),
            'create' => Pages\CreateSharedFile::route('/create'),
            'edit'   => Pages\EditSharedFile::route('/{record}/edit'),
        ];
    }
}
