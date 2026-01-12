<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ExpenseResource\Pages;
use App\Models\Expense;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ExpenseResource extends Resource
{
    protected static ?string $model = Expense::class;

    protected static ?string $navigationIcon = 'heroicon-o-minus-circle';
    protected static ?string $navigationLabel = 'Troškovi (Rashodi)';
    protected static ?string $navigationGroup = 'Finansije';
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Detalji Troška')
                    ->schema([
                        Forms\Components\TextInput::make('description')
                            ->label('Opis/Svrha troška')
                            ->placeholder('Npr. Gorivo, Kirija, Nabavka materijala...')
                            ->required()
                            ->columnSpanFull(),

                        Forms\Components\TextInput::make('amount')
                            ->label('Iznos troška')
                            ->numeric()
                            ->prefix('BAM')
                            ->required(),

                        Forms\Components\DatePicker::make('expense_date')
                            ->label('Datum troška')
                            ->default(now())
                            ->required(),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('description')
                    ->label('Opis')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('amount')
                    ->label('Iznos')
                    ->money('bam', true)
                    ->color('danger') // Crvena boja jer je trošak
                    ->weight('bold')
                    ->summarize(Tables\Columns\Summarizers\Sum::make()->label('Ukupno troškova')),

                Tables\Columns\TextColumn::make('expense_date')
                    ->label('Datum')
                    ->date('d.m.Y')
                    ->sortable(),
            ])
            ->defaultSort('expense_date', 'desc')
            ->filters([
                Tables\Filters\Filter::make('expense_date')
                    ->form([
                        Forms\Components\DatePicker::make('od'),
                        Forms\Components\DatePicker::make('do'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when($data['od'], fn ($q) => $q->whereDate('expense_date', '>=', $data['od']))
                            ->when($data['do'], fn ($q) => $q->whereDate('expense_date', '<=', $data['do']));
                    })
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListExpenses::route('/'),
            'create' => Pages\CreateExpense::route('/create'),
            'edit' => Pages\EditExpense::route('/{record}/edit'),
        ];
    }
}