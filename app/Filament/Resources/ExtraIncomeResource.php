<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ExtraIncomeResource\Pages;
use App\Models\ExtraIncome;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class ExtraIncomeResource extends Resource
{
    protected static ?string $model = ExtraIncome::class;

    protected static ?string $navigationIcon = 'heroicon-o-plus-circle';
    protected static ?string $navigationLabel = 'Dodatni Prihodi';
    protected static ?string $pluralModelLabel = 'Dodatni Prihodi';
    protected static ?string $modelLabel = 'Dodatni prihod';
    protected static ?string $navigationGroup = 'Finansije';
    protected static ?int $navigationSort = 4;

    public static function canAccess(): bool
    {
        return Auth::user()?->hasRole('admin') ?? false;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Detalji dodatnog prihoda')
                    ->description('Novac koji ulazi u kasu a nije redovna prodaja (npr. razlika u marži, gotovinski depozit, poklon).')
                    ->schema([
                        Forms\Components\TextInput::make('description')
                            ->label('Opis / Razlog')
                            ->placeholder('Npr. Razlika u marži za parfem X, gotovinski depozit...')
                            ->required()
                            ->columnSpanFull(),

                        Forms\Components\TextInput::make('amount')
                            ->label('Iznos')
                            ->numeric()
                            ->prefix('BAM')
                            ->minValue(0.01)
                            ->step(0.01)
                            ->required(),

                        Forms\Components\DatePicker::make('income_date')
                            ->label('Datum')
                            ->default(now())
                            ->required(),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('income_date', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('description')
                    ->label('Opis')
                    ->searchable()
                    ->sortable()
                    ->wrap(),

                Tables\Columns\TextColumn::make('amount')
                    ->label('Iznos')
                    ->money('BAM')
                    ->color('success')
                    ->weight('bold')
                    ->summarize(Tables\Columns\Summarizers\Sum::make()->label('Ukupno prihoda')),

                Tables\Columns\TextColumn::make('income_date')
                    ->label('Datum')
                    ->date('d.m.Y')
                    ->sortable(),

                Tables\Columns\TextColumn::make('user.name')
                    ->label('Dodao')
                    ->placeholder('—')
                    ->toggleable(),
            ])
            ->filters([
                Tables\Filters\Filter::make('income_date')
                    ->form([
                        Forms\Components\DatePicker::make('od')->label('Od datuma'),
                        Forms\Components\DatePicker::make('do')->label('Do datuma'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when($data['od'], fn ($q) => $q->whereDate('income_date', '>=', $data['od']))
                            ->when($data['do'], fn ($q) => $q->whereDate('income_date', '<=', $data['do']));
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateHeading('Nema dodatnih prihoda')
            ->emptyStateDescription('Ovdje evidentirajte novac koji nije došao kroz redovnu prodaju.')
            ->emptyStateIcon('heroicon-o-plus-circle');
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListExtraIncomes::route('/'),
            'create' => Pages\CreateExtraIncome::route('/create'),
            'edit'   => Pages\EditExtraIncome::route('/{record}/edit'),
        ];
    }
}
