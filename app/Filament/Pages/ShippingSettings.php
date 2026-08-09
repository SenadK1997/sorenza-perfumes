<?php

namespace App\Filament\Pages;

use App\Models\SiteSetting;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;

class ShippingSettings extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-truck';
    protected static ?string $navigationLabel = 'Postavke Dostave';
    protected static ?string $navigationGroup = 'Sistem';
    protected static ?string $title = 'Postavke Dostave & Povrata';
    protected static string $view = 'filament.pages.shipping-settings';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill([
            'free_shipping_enabled'   => SiteSetting::bool('free_shipping_enabled', true),
            'shipping_fee'            => SiteSetting::float('shipping_fee', 10),
            'free_shipping_threshold' => SiteSetting::float('free_shipping_threshold', 120),
            'refund_days'             => SiteSetting::int('refund_days', 7),
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Dostava')
                    ->description('Kontrolišite naplatu dostave.')
                    ->icon('heroicon-o-truck')
                    ->schema([
                        Toggle::make('free_shipping_enabled')
                            ->label('Besplatna dostava (uvijek)')
                            ->helperText('Kada je UKLJUČENO — dostava je uvijek besplatna. Kada je ISKLJUČENO — naplaćuje se cijena ispod, a ako postavite prag, iznad njega je besplatna.')
                            ->inline(false)
                            ->live()
                            ->columnSpanFull(),
                        TextInput::make('shipping_fee')
                            ->label('Cijena dostave (KM)')
                            ->numeric()
                            ->minValue(0)
                            ->step(0.5)
                            ->required()
                            ->disabled(fn ($get) => (bool) $get('free_shipping_enabled'))
                            ->helperText(fn ($get) => $get('free_shipping_enabled')
                                ? 'Ne primjenjuje se dok je besplatna dostava uključena.'
                                : 'Iznos koji se naplaćuje kupcu za dostavu.'),
                        TextInput::make('free_shipping_threshold')
                            ->label('Prag za besplatnu dostavu (KM)')
                            ->numeric()
                            ->minValue(0)
                            ->step(1)
                            ->disabled(fn ($get) => (bool) $get('free_shipping_enabled'))
                            ->helperText(fn ($get) => $get('free_shipping_enabled')
                                ? 'Ne primjenjuje se dok je besplatna dostava uključena.'
                                : 'Iznad ovog iznosa dostava je besplatna. Postavite 0 ako ne želite prag.'),
                    ])
                    ->columns(['default' => 1, 'md' => 2]),

                Section::make('Povrat sredstava')
                    ->description('Prozor tokom kojeg kupci mogu zatražiti povrat.')
                    ->icon('heroicon-o-arrow-uturn-left')
                    ->schema([
                        TextInput::make('refund_days')
                            ->label('Broj dana za povrat')
                            ->numeric()
                            ->minValue(0)
                            ->step(1)
                            ->suffix('dana')
                            ->helperText('Kupci mogu zatražiti povrat u okviru ovog broja dana nakon narudžbe.')
                            ->required(),
                    ]),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $state = $this->form->getState();

        SiteSetting::set('free_shipping_enabled', $state['free_shipping_enabled'] ? '1' : '0');
        SiteSetting::set('shipping_fee', $state['shipping_fee']);
        SiteSetting::set('free_shipping_threshold', $state['free_shipping_threshold']);
        SiteSetting::set('refund_days', $state['refund_days']);

        Notification::make()
            ->title('Postavke sačuvane')
            ->success()
            ->send();
    }

    public static function canAccess(): bool
    {
        return Auth::user()?->hasRole('admin') ?? false;
    }
}
