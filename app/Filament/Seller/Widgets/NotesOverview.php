<?php

namespace App\Filament\Seller\Widgets;

use App\Models\Note; // Obavezno uvezi model
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

class NotesOverview extends BaseWidget
{
    // Naslov koji će pisati iznad tabele na dashboardu
    protected static ?string $heading = '📝 Moje aktivne bilješke';

    // Widget će zauzimati punu širinu
    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                // Uzimamo samo bilješke ulogovanog prodavača koje NISU završene
                Note::query()
                    ->where('user_id', auth()->id())
                    ->where('is_done', false)
                    ->latest()
            )
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('Naslov')
                    ->weight('bold'),
                
                Tables\Columns\TextColumn::make('description')
                    ->label('Opis')
                    ->limit(50) // Skraćuje opis ako je predug
                    ->placeholder('Nema opisa...'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Kreirano')
                    ->dateTime('d.m.Y H:i')
                    ->color('gray')
                    ->size('sm'),
            ])
            ->actions([
                // Akcija koja omogućava da prodavač jednim klikom završi bilješku
                Tables\Actions\Action::make('markAsDone')
                    ->label('Završi')
                    ->icon('heroicon-m-check-circle')
                    ->color('success')
                    ->action(fn (Note $record) => $record->update(['is_done' => true])),
            ])
            ->emptyStateHeading('Sve bilješke su završene!')
            ->emptyStateIcon('heroicon-o-check-badge');
    }
}