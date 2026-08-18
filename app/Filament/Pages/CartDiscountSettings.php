<?php

namespace App\Filament\Pages;

use App\Models\SiteSetting;
use App\Services\CartTierDiscount;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;

class CartDiscountSettings extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-gift';
    protected static ?string $navigationLabel = 'Popusti u korpi';
    protected static ?string $navigationGroup = 'Marketing';
    protected static ?string $title = 'Automatski popusti po iznosu korpe';
    protected static string $view = 'filament.pages.cart-discount-settings';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill([
            'cart_tier_discount_enabled' => CartTierDiscount::enabled(),
            'tiers' => array_values(CartTierDiscount::tiers()),
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Prikaz na korpi')
                    ->description('Kad je uključeno, na stranici korpe kupac vidi progres bar: "Dodajte još X KM za Y KM popusta". Popust se automatski primjenjuje kad dostigne prag.')
                    ->schema([
                        Toggle::make('cart_tier_discount_enabled')
                            ->label('Uključi automatske popuste po iznosu')
                            ->inline(false)
                            ->columnSpanFull(),
                    ]),

                Section::make('Pragovi (milestones)')
                    ->description('Dodajte pragove: kad kupac dostigne "Iznos korpe", automatski dobija "Popust". Pragovi se sortiraju po iznosu; primjenjuje se najviši dostignuti.')
                    ->schema([
                        Repeater::make('tiers')
                            ->label('')
                            ->schema([
                                TextInput::make('min_subtotal')
                                    ->label('Iznos korpe (KM)')
                                    ->numeric()
                                    ->minValue(1)
                                    ->step('any')
                                    ->required(),
                                TextInput::make('discount')
                                    ->label('Popust (KM)')
                                    ->numeric()
                                    ->minValue(1)
                                    ->step('any')
                                    ->required(),
                            ])
                            ->columns(2)
                            ->defaultItems(0)
                            ->addActionLabel('+ Dodaj prag')
                            ->reorderable(false)
                            ->columnSpanFull(),
                    ]),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $state = $this->form->getState();

        SiteSetting::set(
            CartTierDiscount::SETTING_ENABLED,
            !empty($state['cart_tier_discount_enabled']) ? '1' : '0'
        );

        CartTierDiscount::setTiers($state['tiers'] ?? []);

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
