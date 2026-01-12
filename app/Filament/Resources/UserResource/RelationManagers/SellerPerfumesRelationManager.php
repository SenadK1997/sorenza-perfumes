<?php

namespace App\Filament\Resources\UserResource\RelationManagers;

use App\Models\Perfume;
use Filament\Forms;
use Filament\Tables;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Actions\AttachAction;
use Filament\Tables\Actions\Action;
use Illuminate\Database\Eloquent\Collection;

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
                    ->sortable(),
                Tables\Columns\TextColumn::make('pivot.stock')
                    ->label('Zaliha kod prodavača')
                    ->badge()
                    ->color('info')
                    ->sortable(),
            ])
            ->headerActions([
                // OPCIJA 1: Višestruko dodavanje (Multiple)
                AttachAction::make()
                    ->label('Zaduži parfeme')
                    ->multiple()
                    ->form(fn (AttachAction $action): array => [
                        Forms\Components\Select::make('recordId') // Ručno imenujemo ključ koji Filament očekuje
                            ->label('Parfemi')
                            ->multiple()
                            ->options(Perfume::all()->pluck('name', 'id'))
                            ->required(),
                        Forms\Components\TextInput::make('stock')
                            ->label('Količina')
                            ->numeric()
                            ->default(1)
                            ->required(),
                    ]),
                    // OPCIJA 3: Povećaj zalihe svima za +1
                    Tables\Actions\Action::make('incrementAllStock')
                        ->label('Povećaj za (+1)')
                        ->color('success')
                        ->icon('heroicon-m-arrow-trending-up')
                        ->requiresConfirmation()
                        ->modalHeading('Povećanje zaliha')
                        ->modalDescription('Ova akcija će povećati zalihu za +1 svim parfemima koje ovaj prodavač trenutno posjeduje.')
                        ->action(function () {
                            // Uzimamo sve parfeme trenutnog prodavača
                            $currentPerfumes = $this->getOwnerRecord()->perfumes;

                            foreach ($currentPerfumes as $perfume) {
                                // Povećavamo trenutni pivot stock za 1
                                $newStock = $perfume->pivot->stock + 1;
                                
                                $this->getOwnerRecord()->perfumes()->updateExistingPivot($perfume->id, [
                                    'stock' => $newStock,
                                ]);
                            }
                        }),

                // OPCIJA 2: Dugme "Dodaj sve sa zaliho 1"
                Action::make('addAllPerfumes')
                    ->label('Dodaj sve (zaliha 1)')
                    ->color('gray')
                    ->icon('heroicon-m-plus-circle')
                    ->requiresConfirmation()
                    ->action(function () {
                        $allPerfumeIds = Perfume::pluck('id')->toArray();
                        // Kreiramo niz za pivot tabelu: [id => ['stock' => 1], ...]
                        $attachData = array_fill_keys($allPerfumeIds, ['stock' => 1]);
                        
                        // syncWithoutDetaching osigurava da ne obrišemo već dodijeljene
                        $this->getOwnerRecord()->perfumes()->syncWithoutDetaching($attachData);
                    })
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->form([
                        Forms\Components\TextInput::make('stock')
                            ->label('Izmjeni zalihu')
                            ->numeric()
                            ->required(),
                    ]),
                Tables\Actions\DetachAction::make(),
            ]);
    }
}