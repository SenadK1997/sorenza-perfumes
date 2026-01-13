<?php

namespace App\Filament\Resources\UserResource\RelationManagers;

use App\Models\Perfume;
use Filament\Forms;
use Filament\Tables;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Actions\AttachAction;
use Filament\Tables\Actions\Action;
use Illuminate\Database\Eloquent\Builder;

class SellerPerfumesRelationManager extends RelationManager
{
    protected static string $relationship = 'perfumes';
    protected static ?string $title = 'Zaduženi Parfemi';

    public function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Parfem')
                    ->description(fn (Perfume $record): string => $record->inspired_by ?? '')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('pivot.stock')
                    ->label('Zaliha kod prodavača')
                    ->badge()
                    ->color('info')
                    ->sortable(),
            ])
            ->headerActions([
                AttachAction::make()
                    ->label('Zaduži parfeme')
                    ->multiple()
                    ->form(fn (AttachAction $action): array => [
                        Forms\Components\Select::make('recordId')
                            ->label('Parfemi')
                            ->multiple()
                            ->required()
                            ->searchable()
                            // SPREČAVANJE DUPLANJA: Filtriramo opcije tako da ne prikazujemo već zadužene parfeme
                            ->options(function () {
                                $alreadyAttached = $this->getOwnerRecord()->perfumes()->pluck('perfumes.id')->toArray();
                                
                                return Perfume::query()
                                    ->whereNotIn('id', $alreadyAttached)
                                    ->get()
                                    ->mapWithKeys(fn ($p) => [$p->id => "{$p->name} ({$p->inspired_by})"]);
                            })
                            // Pretraga također filtrira već dodijeljene parfeme
                            ->getSearchResultsUsing(function (string $search) {
                                $alreadyAttached = $this->getOwnerRecord()->perfumes()->pluck('perfumes.id')->toArray();

                                return Perfume::whereNotIn('id', $alreadyAttached)
                                    ->where(function($q) use ($search) {
                                        $q->where('name', 'like', "%{$search}%")
                                          ->orWhere('inspired_by', 'like', "%{$search}%");
                                    })
                                    ->limit(50)
                                    ->get()
                                    ->mapWithKeys(fn ($p) => [$p->id => "{$p->name} ({$p->inspired_by})"]);
                            }),

                        Forms\Components\TextInput::make('stock')
                            ->label('Količina')
                            ->numeric()
                            ->default(1)
                            ->required(),
                    ]),

                Tables\Actions\Action::make('incrementAllStock')
                    ->label('Povećaj za (+1)')
                    ->color('success')
                    ->icon('heroicon-m-arrow-trending-up')
                    ->requiresConfirmation()
                    ->modalHeading('Povećanje zaliha')
                    ->action(function () {
                        $currentPerfumes = $this->getOwnerRecord()->perfumes;
                        foreach ($currentPerfumes as $perfume) {
                            $this->getOwnerRecord()->perfumes()->updateExistingPivot($perfume->id, [
                                'stock' => $perfume->pivot->stock + 1,
                            ]);
                        }
                    }),

                Action::make('addAllPerfumes')
                    ->label('Dodaj sve (zaliha 1)')
                    ->color('gray')
                    ->icon('heroicon-m-plus-circle')
                    ->requiresConfirmation()
                    ->action(function () {
                        $allPerfumeIds = Perfume::pluck('id')->toArray();
                        $attachData = array_fill_keys($allPerfumeIds, ['stock' => 1]);
                        $this->getOwnerRecord()->perfumes()->syncWithoutDetaching($attachData);
                    })
            ])
            ->actions([
                // KADA JE PARFEM NA 0, ADMIN KORISTI OVO DUGME DA DOPUNI ZALIHU
                Tables\Actions\EditAction::make()
                    ->label('Izmjeni zalihe')
                    ->form([
                        Forms\Components\TextInput::make('stock')
                            ->label('Trenutno stanje u torbi')
                            ->numeric()
                            ->required(),
                    ]),
                Tables\Actions\DetachAction::make()
                    ->label('Razduži'),
            ]);
    }
}