<?php

namespace App\Filament\Resources;

use App\Enums\Canton;
use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Grid;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Hash;
use App\Filament\Resources\UserResource\RelationManagers\SellerPerfumesRelationManager;

class UserResource extends Resource
{
    protected static ?string $model = User::class;
    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationLabel = 'Korisnici';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // SEKCIJA 1: PRISTUPNI PODACI
                Section::make('Podaci za prijavu')
                    ->description('Email adresa i lozinka za pristup sistemu')
                    ->schema([
                        Grid::make(2)->schema([
                            Forms\Components\TextInput::make('email')
                                ->email()
                                ->required()
                                ->unique(ignoreRecord: true)
                                ->maxLength(255),

                            Forms\Components\TextInput::make('password')
                                ->label('Nova lozinka')
                                ->password()
                                ->placeholder('Ostavite prazno ako ne mijenjate')
                                ->minLength(8)
                                ->dehydrated(fn ($state) => filled($state))
                                ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                                // Zahtijevaj lozinku samo prilikom kreiranja novog korisnika
                                ->required(fn (string $context): bool => $context === 'create'),
                        ]),
                    ]),

                // SEKCIJA 2: LIČNI PODACI I LOKACIJA
                Section::make('Profil i Lokacija')
                    ->description('Osnovni podaci o korisniku i regiji')
                    ->schema([
                        Grid::make(2)->schema([
                            Forms\Components\TextInput::make('name')
                                ->label('Ime i Prezime')
                                ->required()
                                ->maxLength(255),

                            Forms\Components\Select::make('roles')
                                ->label('Uloge (Permissions)')
                                ->multiple()
                                ->relationship('roles', 'name')
                                ->preload(),
                        ]),

                        Grid::make(2)->schema([
                            Forms\Components\Select::make('canton')
                                ->label('Kanton')
                                ->options(Canton::class) // Automatski vuče tvoj Enum
                                ->searchable()
                                ->native(false),

                            Forms\Components\TextInput::make('city')
                                ->label('Grad')
                                ->placeholder('npr. Sarajevo, Tuzla, Mostar')
                                ->maxLength(255),
                        ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Ime')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('email')
                    ->copyable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('roles.name')
                    ->label('Uloga')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'admin' => 'danger',
                        'seller' => 'success',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('canton')
                    ->label('Kanton')
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('city')
                    ->label('Grad')
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Registrovan')
                    ->dateTime('d.m.Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('canton')
                    ->label('Filtriraj po kantonu')
                    ->options(Canton::class),
                
                Tables\Filters\SelectFilter::make('roles')
                    ->label('Uloga')
                    ->relationship('roles', 'name'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            SellerPerfumesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}