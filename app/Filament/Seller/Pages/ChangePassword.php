<?php

namespace App\Filament\Seller\Pages;

use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ChangePassword extends Page
{
    // Konfiguracija navigacije u sidebar-u
    protected static ?string $navigationIcon = 'heroicon-o-key';
    protected static ?string $navigationLabel = 'Promjena lozinke';
    protected static ?string $navigationGroup = 'Postavke';
    protected static ?int $navigationSort = 100;

    protected static string $view = 'filament.seller.pages.change-password';

    // Niz u koji se spremaju podaci iz forme
    public ?array $data = [];

    public function mount(): void
    {
        // Inicijalizacija forme
        $this->form->fill();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Sigurnost vašeg računa')
                    ->description('Savjetujemo korištenje jake lozinke (minimalno 8 znakova).')
                    ->schema([
                        TextInput::make('current_password')
                            ->label('Trenutna lozinka')
                            ->password()
                            ->required()
                            ->currentPassword(), // Validira da li je stara lozinka tačna

                        TextInput::make('new_password')
                            ->label('Nova lozinka')
                            ->password()
                            ->required()
                            ->rule(Password::default()->min(8))
                            ->same('new_password_confirmation'), // Mora biti ista kao polje ispod

                        TextInput::make('new_password_confirmation')
                            ->label('Potvrdi novu lozinku')
                            ->password()
                            ->required(),
                    ])
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        // Validacija i preuzimanje podataka
        $data = $this->form->getState();

        // Ažuriranje korisnika u bazi
        auth()->user()->update([
            'password' => Hash::make($data['new_password']),
        ]);

        // Čišćenje forme nakon uspjeha
        $this->form->fill();

        // Slanje notifikacije prodavaču
        Notification::make()
            ->title('Uspješno!')
            ->body('Vaša lozinka je uspješno promijenjena.')
            ->success()
            ->send();
    }
}