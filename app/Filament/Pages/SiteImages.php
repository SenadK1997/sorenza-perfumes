<?php

namespace App\Filament\Pages;

use App\Models\SiteSetting;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;

class SiteImages extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon  = 'heroicon-o-photo';
    protected static ?string $navigationLabel = 'Slike sajta';
    protected static ?string $navigationGroup = 'Sistem';
    protected static ?string $title           = 'Slike sajta — Hero & Kolekcije';
    protected static string  $view            = 'filament.pages.site-images';

    public ?array $data = [];

    public static function canAccess(): bool
    {
        return Auth::user()?->hasRole('admin') ?? false;
    }

    public function mount(): void
    {
        $this->form->fill([
            'hero_image'            => SiteSetting::get('hero_image'),
            'gender_female_image'   => SiteSetting::get('gender_female_image'),
            'gender_male_image'     => SiteSetting::get('gender_male_image'),
            'gender_unisex_image'   => SiteSetting::get('gender_unisex_image'),
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Hero (naslovna slika)')
                    ->description('Velika slika koja se prikazuje na vrhu početne stranice iza teksta "Sorénza".')
                    ->schema([
                        FileUpload::make('hero_image')
                            ->label('Hero slika')
                            ->image()
                            ->imageEditor()
                            ->directory('site/hero')
                            ->disk('public')
                            ->visibility('public')
                            ->maxSize(4096)
                            ->helperText('Preporučeno: pejzažni format, min. 1920×1080. Ako ostavite prazno, koristi se zadana slika.')
                            ->imageResizeMode('cover'),
                    ]),

                Section::make('Kolekcije po polu')
                    ->description('Slike koje se pojavljuju u sekciji "Pronađi Svoj Potpis" na početnoj stranici.')
                    ->schema([
                        FileUpload::make('gender_female_image')
                            ->label('Ženski parfemi')
                            ->image()
                            ->imageEditor()
                            ->directory('site/gender')
                            ->disk('public')
                            ->visibility('public')
                            ->maxSize(4096),

                        FileUpload::make('gender_male_image')
                            ->label('Muški parfemi')
                            ->image()
                            ->imageEditor()
                            ->directory('site/gender')
                            ->disk('public')
                            ->visibility('public')
                            ->maxSize(4096),

                        FileUpload::make('gender_unisex_image')
                            ->label('Unisex parfemi')
                            ->image()
                            ->imageEditor()
                            ->directory('site/gender')
                            ->disk('public')
                            ->visibility('public')
                            ->maxSize(4096),
                    ])
                    ->columns(['default' => 1, 'md' => 3]),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $state = $this->form->getState();

        foreach (['hero_image', 'gender_female_image', 'gender_male_image', 'gender_unisex_image'] as $k) {
            SiteSetting::set($k, $state[$k] ?? '');
        }

        Notification::make()
            ->title('Slike sačuvane')
            ->body('Osvježite početnu stranicu da vidite promjene.')
            ->success()
            ->send();
    }
}
