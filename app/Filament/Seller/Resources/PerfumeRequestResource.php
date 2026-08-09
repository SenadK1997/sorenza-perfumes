<?php

namespace App\Filament\Seller\Resources;

use App\Enums\PerfumeRequestStatus;
use App\Filament\Seller\Resources\PerfumeRequestResource\Pages;
use App\Models\Perfume;
use App\Models\PerfumeRequest;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class PerfumeRequestResource extends Resource
{
    protected static ?string $model = PerfumeRequest::class;
    protected static ?string $navigationIcon = 'heroicon-o-plus-circle';
    protected static ?string $navigationLabel = 'Zahtjevi za parfeme';
    protected static ?string $modelLabel = 'Zahtjev za parfem';
    protected static ?string $pluralModelLabel = 'Zahtjevi za parfeme';
    protected static ?string $navigationGroup = 'Parfemi';
    protected static ?int $navigationSort = 6;

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('user_id', Auth::id());
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Zatraži parfeme')
                ->description('Dodajte jedan ili više parfema i količine. Nakon odobrenja admina, količine se automatski dodaju na Vaš lager.')
                ->icon('heroicon-o-plus-circle')
                ->schema([
                    Forms\Components\Repeater::make('items')
                        ->label('Parfemi')
                        ->addActionLabel('+ Dodaj još jedan parfem')
                        ->minItems(1)
                        ->defaultItems(1)
                        ->reorderable(false)
                        ->columnSpanFull()
                        ->dehydrated()
                        ->schema([
                            Forms\Components\Select::make('perfume_id')
                                ->label('Parfem')
                                ->options(fn () => Perfume::query()
                                    ->where('availability', true)
                                    ->orderBy('name')
                                    ->pluck('name', 'id'))
                                ->searchable()
                                ->preload()
                                ->required(),

                            Forms\Components\TextInput::make('quantity')
                                ->label('Količina (kom)')
                                ->numeric()
                                ->minValue(1)
                                ->maxValue(500)
                                ->default(1)
                                ->required(),
                        ])
                        ->columns(2)
                        ->itemLabel(function (array $state): ?string {
                            if (empty($state['perfume_id'])) return null;
                            $name = Perfume::find($state['perfume_id'])?->name;
                            $qty  = (int) ($state['quantity'] ?? 0);
                            return $name ? "{$name} — {$qty} kom" : null;
                        }),

                    Forms\Components\Textarea::make('note')
                        ->label('Napomena za admina (opcionalno)')
                        ->rows(3)
                        ->maxLength(1000)
                        ->columnSpanFull(),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('perfume.name')
                    ->label('Parfem')
                    ->weight('bold')
                    ->searchable(),

                Tables\Columns\TextColumn::make('quantity')
                    ->label('Količina')
                    ->badge()
                    ->color('info')
                    ->formatStateUsing(fn ($state) => $state . ' kom'),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn ($state) => $state->label())
                    ->color(fn ($state) => $state->color()),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Poslano')
                    ->since()
                    ->sortable(),

                Tables\Columns\TextColumn::make('resolved_at')
                    ->label('Riješeno')
                    ->dateTime('d.m.Y H:i')
                    ->placeholder('—'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options(collect(PerfumeRequestStatus::cases())
                        ->mapWithKeys(fn ($c) => [$c->value => $c->label()])),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->visible(fn ($record) => $record->status === PerfumeRequestStatus::PENDING),
            ])
            ->emptyStateHeading('Još nemate zahtjeva')
            ->emptyStateDescription('Kliknite "Novi zahtjev" da zatražite parfeme.')
            ->emptyStateIcon('heroicon-o-plus-circle')
            ->emptyStateActions([
                Tables\Actions\CreateAction::make()
                    ->label('Novi zahtjev')
                    ->icon('heroicon-o-plus'),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListPerfumeRequests::route('/'),
            'create' => Pages\CreatePerfumeRequest::route('/create'),
            'view'   => Pages\ViewPerfumeRequest::route('/{record}'),
        ];
    }

    public static function canEdit($record): bool { return false; }
}
