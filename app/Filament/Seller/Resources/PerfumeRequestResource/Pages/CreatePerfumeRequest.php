<?php

namespace App\Filament\Seller\Resources\PerfumeRequestResource\Pages;

use App\Filament\Seller\Resources\PerfumeRequestResource;
use App\Models\PerfumeRequest;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CreatePerfumeRequest extends CreateRecord
{
    protected static string $resource = PerfumeRequestResource::class;

    protected ?bool $hasDatabaseTransactions = false; // we manage the transaction ourselves

    protected function getHeaderWidgets(): array
    {
        return [
            \App\Filament\Seller\Widgets\MissingPerfumes::class,
        ];
    }

    protected function getFormActions(): array
    {
        return [
            $this->getCreateFormAction()
                ->label('Pošalji zahtjev')
                ->icon('heroicon-o-paper-airplane'),

            $this->getCancelFormAction()
                ->label('Otkaži'),
        ];
    }

    protected function getCreatedNotification(): ?\Filament\Notifications\Notification
    {
        return null; // we send our own
    }

    protected function handleRecordCreation(array $data): Model
    {
        $userId = Auth::id();
        $note   = $data['note'] ?? null;
        $items  = collect($data['items'] ?? [])
            ->filter(fn ($i) => !empty($i['perfume_id']) && (int) ($i['quantity'] ?? 0) > 0)
            ->values();

        if ($items->isEmpty()) {
            Notification::make()
                ->title('Nema stavki za slanje')
                ->body('Dodajte najmanje jedan parfem i količinu.')
                ->danger()
                ->send();

            $this->halt();
        }

        // Merge duplicate perfume_ids into a single row with summed quantity
        $items = $items
            ->groupBy('perfume_id')
            ->map(fn ($rows) => [
                'perfume_id' => (int) $rows->first()['perfume_id'],
                'quantity'   => (int) $rows->sum(fn ($r) => (int) $r['quantity']),
            ])
            ->values();

        $first = DB::transaction(function () use ($items, $userId, $note) {
            $created = collect();
            foreach ($items as $item) {
                $created->push(PerfumeRequest::create([
                    'user_id'    => $userId,
                    'perfume_id' => $item['perfume_id'],
                    'quantity'   => $item['quantity'],
                    'note'       => $note,
                    'status'     => 'pending',
                ]));
            }
            return $created->first();
        });

        Notification::make()
            ->title($items->count() === 1
                ? 'Zahtjev poslan'
                : "Poslano {$items->count()} zahtjeva")
            ->body('Admin će pregledati i odobriti uskoro.')
            ->success()
            ->send();

        return $first;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
