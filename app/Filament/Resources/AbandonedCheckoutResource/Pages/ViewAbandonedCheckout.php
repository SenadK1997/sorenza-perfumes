<?php

namespace App\Filament\Resources\AbandonedCheckoutResource\Pages;

use App\Filament\Resources\AbandonedCheckoutResource;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ViewRecord;

class ViewAbandonedCheckout extends ViewRecord
{
    protected static string $resource = AbandonedCheckoutResource::class;

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema([
            Section::make('Kupac')
                ->schema([
                    TextEntry::make('email')->copyable(),
                    TextEntry::make('created_at')->label('Prvi put')->dateTime('d.m.Y H:i'),
                    TextEntry::make('updated_at')->label('Zadnja aktivnost')->dateTime('d.m.Y H:i'),
                    TextEntry::make('ip')->label('IP')->placeholder('—'),
                ])
                ->columns(2),

            Section::make('Status')
                ->schema([
                    TextEntry::make('recovered_at')
                        ->label('Status')
                        ->badge()
                        ->formatStateUsing(fn ($s) => $s ? 'Vratio se i naručio' : 'Nije naručio')
                        ->color(fn ($s) => $s ? 'success' : 'warning'),
                    TextEntry::make('order.pretty_id')
                        ->label('Broj narudžbe')
                        ->placeholder('—'),
                    TextEntry::make('subtotal')->label('Iznos')->money('BAM'),
                    TextEntry::make('item_count')->label('Broj artikala'),
                ])
                ->columns(2),

            Section::make('Artikli u korpi')
                ->schema([
                    RepeatableEntry::make('items')
                        ->hiddenLabel()
                        ->schema([
                            TextEntry::make('name')->label('Parfem')->weight('bold'),
                            TextEntry::make('quantity')->label('Količina')->suffix(' kom'),
                            TextEntry::make('price')->label('Cijena')->money('BAM'),
                        ])
                        ->columns(3),
                ]),
        ]);
    }
}
