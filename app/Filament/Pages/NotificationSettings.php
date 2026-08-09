<?php

namespace App\Filament\Pages;

use App\Models\SiteSetting;
use App\Services\TelegramNotifier;
use Filament\Actions\Action;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Concerns\InteractsWithFormActions;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;

class NotificationSettings extends Page implements HasForms
{
    use InteractsWithForms;
    use InteractsWithFormActions;

    protected static ?string $navigationIcon  = 'heroicon-o-bell-alert';
    protected static ?string $navigationLabel = 'Notifikacije';
    protected static ?string $navigationGroup = 'Sistem';
    protected static ?string $title           = 'Notifikacije & Integracije';
    protected static string  $view            = 'filament.pages.notification-settings';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill([
            'telegram_bot_token' => SiteSetting::get('telegram_bot_token'),
            'telegram_chat_id'   => SiteSetting::get('telegram_chat_id'),
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Telegram — nove narudžbe')
                    ->description('Kada je konfigurisano, na svaku novu narudžbu bot šalje poruku u odabrani chat/grupu.')
                    ->icon('heroicon-o-paper-airplane')
                    ->schema([
                        TextInput::make('telegram_bot_token')
                            ->label('Bot token')
                            ->helperText('Dobijate od @BotFather komandom /newbot.')
                            ->password()
                            ->revealable()
                            ->autocomplete('off')
                            ->placeholder('123456:ABC-DEF...'),

                        TextInput::make('telegram_chat_id')
                            ->label('Chat ID (grupa ili korisnik)')
                            ->helperText('Za grupu je negativan broj (npr. -1001234567890). Za privatnu poruku je pozitivan broj.')
                            ->placeholder('-1001234567890'),
                    ])
                    ->columns(1),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $state = $this->form->getState();

        SiteSetting::set('telegram_bot_token', $state['telegram_bot_token'] ?? '');
        SiteSetting::set('telegram_chat_id',   $state['telegram_chat_id']   ?? '');

        Notification::make()
            ->title('Postavke sačuvane')
            ->success()
            ->send();
    }

    public function sendTestTelegram(): void
    {
        $state = $this->form->getState();

        // Persist first so the notifier reads the freshly-typed values
        SiteSetting::set('telegram_bot_token', $state['telegram_bot_token'] ?? '');
        SiteSetting::set('telegram_chat_id',   $state['telegram_chat_id']   ?? '');
        \Illuminate\Support\Facades\Cache::forget('site_settings.all');

        if (! TelegramNotifier::enabled()) {
            Notification::make()
                ->title('Popunite token i chat ID prije testiranja')
                ->danger()
                ->send();
            return;
        }

        $ok = TelegramNotifier::send(
            "✅ *Sorénza test poruka*\n"
            . "Ako vidite ovo — integracija radi 🎉\n"
            . "Vrijeme: " . now()->format('d.m.Y H:i:s')
        );

        Notification::make()
            ->title($ok ? 'Test poruka poslana' : 'Slanje test poruke nije uspjelo')
            ->body($ok ? 'Provjerite svoj Telegram chat/grupu.' : 'Provjerite log ili token/chat ID.')
            ->{$ok ? 'success' : 'danger'}()
            ->send();
    }

    public static function canAccess(): bool
    {
        return Auth::user()?->hasRole('admin') ?? false;
    }
}
