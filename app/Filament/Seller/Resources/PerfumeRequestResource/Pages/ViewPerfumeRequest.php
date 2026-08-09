<?php

namespace App\Filament\Seller\Resources\PerfumeRequestResource\Pages;

use App\Filament\Seller\Resources\PerfumeRequestResource;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ViewRecord;

class ViewPerfumeRequest extends ViewRecord
{
    protected static string $resource = PerfumeRequestResource::class;

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema([
            Section::make('Zahtjev')
                ->schema([
                    TextEntry::make('perfume.name')->label('Parfem')->weight('bold'),
                    TextEntry::make('quantity')->label('Količina')->suffix(' kom'),
                    TextEntry::make('status')
                        ->badge()
                        ->formatStateUsing(fn ($s) => $s->label())
                        ->color(fn ($s) => $s->color()),
                    TextEntry::make('created_at')->label('Poslano')->dateTime('d.m.Y H:i'),
                    TextEntry::make('resolved_at')->label('Riješeno')->dateTime('d.m.Y H:i')->placeholder('—'),
                ])
                ->columns(2),

            Section::make('Napomene')
                ->schema([
                    TextEntry::make('note')->label('Vaša napomena')->placeholder('—'),
                    TextEntry::make('admin_note')->label('Napomena admina')->placeholder('—'),
                ]),
        ]);
    }
}
