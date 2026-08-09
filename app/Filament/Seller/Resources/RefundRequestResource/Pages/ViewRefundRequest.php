<?php

namespace App\Filament\Seller\Resources\RefundRequestResource\Pages;

use App\Filament\Seller\Resources\RefundRequestResource;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ViewRecord;

class ViewRefundRequest extends ViewRecord
{
    protected static string $resource = RefundRequestResource::class;

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema([
            Section::make('Narudžba')
                ->schema([
                    TextEntry::make('order.pretty_id')->label('Broj narudžbe'),
                    TextEntry::make('order.amount')->label('Iznos')->money('BAM'),
                    TextEntry::make('created_at')->label('Zahtjev poslan')->dateTime('d.m.Y H:i'),
                ])
                ->columns(3),

            Section::make('Kupac')
                ->schema([
                    TextEntry::make('customer_name')->label('Ime'),
                    TextEntry::make('customer_email')->label('Email')->copyable(),
                    TextEntry::make('customer_phone')->label('Telefon')->copyable(),
                ])
                ->columns(3),

            Section::make('Razlog')
                ->schema([
                    TextEntry::make('reason')->hiddenLabel()->prose(),
                ]),

            Section::make('Status')
                ->schema([
                    TextEntry::make('status')
                        ->badge()
                        ->formatStateUsing(fn ($state) => $state->label())
                        ->color(fn ($state) => $state->color()),
                    TextEntry::make('admin_response')
                        ->label('Odgovor podrške')
                        ->placeholder('—')
                        ->columnSpanFull(),
                ])
                ->columns(2),
        ]);
    }
}
