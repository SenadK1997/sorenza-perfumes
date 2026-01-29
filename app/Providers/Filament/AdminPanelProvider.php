<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Filament\Navigation\NavigationItem;
use Illuminate\Support\HtmlString;
use Filament\Support\Facades\FilamentView;
use Illuminate\Support\Facades\Blade;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->colors([
                'primary' => Color::Amber,
                'gold'    => '#FFD700',
                'silver'  => '#C0C0C0',
                'bronze'  => '#CD7F32',
            ])
            ->brandName(fn () => new HtmlString('
                <div class="sorenza-brand-wrapper flex items-center gap-3">
                    <img src="' . asset('favicon.png') . '" 
                        alt="Logo" 
                        class="sorenza-logo">
                    <span class="sorenza-text font-bold tracking-tight">
                        Sorénza - Admin
                    </span>
                </div>

                <style>
                    /* DASHBOARD POSTAVKE (Sidebar) */
                    .sorenza-logo {
                        height: 2rem; /* Mali logo u sidebaru */
                        width: auto;
                    }
                    .sorenza-text {
                        font-size: 1.1rem;
                    }

                    /* LOGIN STRANICA POSTAVKE (Forsiranje kolone) */
                    /* .fi-simple-main je glavni kontejner Filament Login stranice */
                    :where(.fi-simple-main) .sorenza-brand-wrapper {
                        flex-direction: column !important;
                        text-align: center;
                        gap: 1rem;
                        margin-bottom: 0.5rem;
                    }

                    :where(.fi-simple-main) .sorenza-logo {
                        height: 3.5rem !important; /* Veliki logo na login stranici */
                    }

                    :where(.fi-simple-main) .sorenza-text {
                        font-size: 1.5rem !important; /* Veliki tekst na login stranici */
                    }
                </style>
            '))
            ->favicon(asset('favicon.png'))
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
                Widgets\FilamentInfoWidget::class,
            ])
            ->navigationItems([
                NavigationItem::make('Prebaci na Prodaju')
                    ->url('/seller')
                    ->icon('heroicon-o-arrow-path')
                    ->group('Sistem')
                    ->sort(100)
                    ->visible(fn (): bool => auth()->user()?->hasRole('seller') ?? false),
            ])
            ->renderHook(
                'panels::body.end',
                fn (): string => Blade::render('<style>
                    @media (max-width: 1024px) {
                        .fi-sidebar-nav {
                            padding-bottom: 100px !important;
                        }
                    }
                </style>'),
            )
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
                \App\Http\Middleware\RedirectIfNotAdmin::class,
            ]);
    }
}
