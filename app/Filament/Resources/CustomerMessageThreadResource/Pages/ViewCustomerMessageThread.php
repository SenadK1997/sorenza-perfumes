<?php

namespace App\Filament\Resources\CustomerMessageThreadResource\Pages;

use App\Filament\Resources\CustomerMessageThreadResource;
use App\Models\CustomerMessage;
use App\Services\Messaging;
use Filament\Actions;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;

class ViewCustomerMessageThread extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string $resource = CustomerMessageThreadResource::class;
    protected static string $view = 'filament.resources.customer-message-thread.view';

    public $record;
    public ?array $data = [];

    public function mount($record): void
    {
        $this->record = $this->getResource()::getEloquentQuery()->findOrFail($record);
        Messaging::markAdminRead($this->record);
        $this->form->fill();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Textarea::make('body')
                    ->label('Vaš odgovor (kupac vidi identitet "Sorenza")')
                    ->required()
                    ->rows(4),
            ])
            ->statePath('data');
    }

    public function send(): void
    {
        $state = $this->form->getState();
        Messaging::adminSendToCustomer(
            Auth::user(),
            $this->record->customer,
            $state['body']
        );

        Notification::make()->title('Poruka poslata')->success()->send();

        $this->form->fill(); // clear textarea
        $this->record->refresh();
    }

    public function deleteMessage(int $id): void
    {
        $msg = CustomerMessage::where('thread_id', $this->record->id)
            ->where('id', $id)
            ->first();

        if ($msg) {
            $msg->delete();
            Notification::make()->title('Poruka obrisana')->success()->send();
            $this->record->refresh();
        }
    }

    public function getThreadMessages()
    {
        return $this->record->messages()
            ->with('author:id,name')
            ->orderBy('created_at')
            ->get();
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('deleteThread')
                ->label('Obriši razgovor')
                ->color('danger')
                ->icon('heroicon-o-trash')
                ->requiresConfirmation()
                ->modalDescription('Trajno briše sve poruke ovog razgovora.')
                ->action(function () {
                    $this->record->delete();
                    $this->redirect(static::getResource()::getUrl('index'));
                }),
        ];
    }

    public function getTitle(): string
    {
        return 'Razgovor · ' . ($this->record->customer?->full_name ?? 'Kupac');
    }
}
