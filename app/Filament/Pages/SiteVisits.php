<?php

namespace App\Filament\Pages;

use App\Services\GoogleAnalytics;
use Carbon\Carbon;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class SiteVisits extends Page
{
    protected static ?string $navigationIcon  = 'heroicon-o-globe-alt';
    protected static ?string $navigationLabel = 'Posjete sajta';
    protected static ?string $navigationGroup = 'Izvještaji';
    protected static ?string $title           = 'Posjete sajta (Google Analytics)';
    protected static ?int $navigationSort     = -15;
    protected static string $view             = 'filament.pages.site-visits';

    /** 7d | 30d | 90d */
    public string $range = '30d';

    public static function canAccess(): bool
    {
        return Auth::user()?->hasRole('admin') ?? false;
    }

    public function setRange(string $key): void
    {
        $this->range = $key;
    }

    /** Wipe the GA response cache so the page shows the latest numbers immediately. */
    public function refreshCache(): void
    {
        Cache::flush();
        Notification::make()
            ->title('Cache očišćen')
            ->body('Stranica će učitati svježe podatke iz GA.')
            ->success()
            ->send();
    }

    /** Run a full connection check and show the result as a Filament notification. */
    public function runDiagnostic(): void
    {
        $ga = new GoogleAnalytics();
        $propId = config('services.google_analytics.property_id');
        $credPath = base_path((string) config('services.google_analytics.credentials_path'));
        $fileExists = file_exists($credPath);
        $configured = $ga->isConfigured();

        $lines = [
            '• Property ID: ' . ($propId ?: 'MISSING'),
            '• Credentials path: ' . $credPath,
            '• Fajl postoji: ' . ($fileExists ? 'DA' : 'NE'),
            '• Servis konfigurisan: ' . ($configured ? 'DA' : 'NE'),
        ];

        if (! $configured || ! $fileExists) {
            Notification::make()
                ->title('GA nije spreman')
                ->body(implode("\n", $lines))
                ->danger()
                ->persistent()
                ->send();
            return;
        }

        // Try realtime + last 7 days totals
        try {
            Cache::flush();
            $realtime = $ga->activeUsers();
            $totals = $ga->totals('7daysAgo', 'today');

            $lines[] = '• Realtime posjetioci: ' . $realtime;
            $lines[] = '• Sesije (zadnjih 7 dana): ' . $totals['sessions'];
            $lines[] = '• Pregleda (zadnjih 7 dana): ' . $totals['pageviews'];

            $verdict = ($realtime > 0 || $totals['sessions'] > 0)
                ? ['title' => 'GA radi ✓', 'kind' => 'success']
                : ['title' => 'API radi, ali vraća 0', 'kind' => 'warning'];

            Notification::make()
                ->title($verdict['title'])
                ->body(implode("\n", $lines) . "\n\n" . (
                    $verdict['kind'] === 'warning'
                        ? '→ Provjerite da property ID odgovara onome gdje vidite posjete u analytics.google.com.'
                        : ''
                ))
                ->{$verdict['kind']}()
                ->persistent()
                ->send();
        } catch (\Throwable $e) {
            $lines[] = '• GREŠKA: ' . $e->getMessage();

            Notification::make()
                ->title('API poziv nije uspio')
                ->body(implode("\n", $lines))
                ->danger()
                ->persistent()
                ->send();
        }
    }

    protected function rangeBounds(): array
    {
        return match ($this->range) {
            '7d'    => ['start' => '7daysAgo',   'end' => 'today', 'label' => 'Zadnjih 7 dana',   'days' => 7],
            '90d'   => ['start' => '90daysAgo',  'end' => 'today', 'label' => 'Zadnjih 90 dana',  'days' => 90],
            '365d'  => ['start' => '365daysAgo', 'end' => 'today', 'label' => 'Zadnjih 12 mjeseci','days' => 365],
            'all'   => ['start' => '2020-01-01', 'end' => 'today', 'label' => 'Sve vrijeme',      'days' => 365],
            default => ['start' => '30daysAgo',  'end' => 'today', 'label' => 'Zadnjih 30 dana',  'days' => 30],
        };
    }

    protected function previousBounds(int $days): array
    {
        // Prior N days immediately before the current window
        return [
            'start' => ($days * 2) . 'daysAgo',
            'end'   => ($days + 1) . 'daysAgo',
        ];
    }

    protected static function delta(float $c, float $p): ?float
    {
        if ($p <= 0) return $c > 0 ? 100.0 : null;
        return (($c - $p) / $p) * 100.0;
    }

    protected static function fmtDuration(float $sec): string
    {
        if ($sec <= 0) return '0s';
        $m = (int) floor($sec / 60);
        $s = (int) round($sec - $m * 60);
        return $m > 0 ? "{$m}m {$s}s" : "{$s}s";
    }

    public function getViewData(): array
    {
        $ga = new GoogleAnalytics();

        if (! $ga->isConfigured()) {
            return [
                'configured' => false,
                'presets'    => [
                    '7d'   => '7 dana',
                    '30d'  => '30 dana',
                    '90d'  => '90 dana',
                    '365d' => '12 mjeseci',
                    'all'  => 'Sve vrijeme',
                ],
                'label'      => 'GA nije konfigurisan',
            ];
        }

        $range = $this->rangeBounds();
        $prev  = $this->previousBounds($range['days']);

        $totals = $ga->totals($range['start'], $range['end'], $prev['start'], $prev['end']);
        $series = $ga->timeseries($range['start'], $range['end']);
        $pages  = $ga->topPages($range['start'], $range['end']);
        $sources = $ga->topSources($range['start'], $range['end']);
        $devices = $ga->deviceBreakdown($range['start'], $range['end']);
        $realtime = $ga->activeUsers();

        $kpis = [
            [
                'label' => 'Sesije', 'value' => number_format($totals['sessions'], 0, ',', '.'),
                'delta' => static::delta($totals['sessions'], $totals['sessionsPrev']),
                'icon' => 'heroicon-o-cursor-arrow-rays', 'tone' => 'primary',
            ],
            [
                'label' => 'Posjetioci', 'value' => number_format($totals['users'], 0, ',', '.'),
                'delta' => static::delta($totals['users'], $totals['usersPrev']),
                'icon' => 'heroicon-o-user-group', 'tone' => 'emerald',
            ],
            [
                'label' => 'Novi posjetioci', 'value' => number_format($totals['newUsers'], 0, ',', '.'),
                'delta' => static::delta($totals['newUsers'], $totals['newUsersPrev']),
                'icon' => 'heroicon-o-sparkles', 'tone' => 'indigo',
            ],
            [
                'label' => 'Pregleda stranica', 'value' => number_format($totals['pageviews'], 0, ',', '.'),
                'delta' => static::delta($totals['pageviews'], $totals['pageviewsPrev']),
                'icon' => 'heroicon-o-eye', 'tone' => 'rose',
            ],
        ];

        return [
            'configured'   => true,
            'rangeInfo'    => $range,
            'kpis'         => $kpis,
            'series'       => $series,
            'topPages'     => $pages,
            'topSources'   => $sources,
            'devices'      => $devices,
            'realtime'     => $realtime,
            'avgSession'   => static::fmtDuration($totals['avgSessionSec']),
            'bounceRate'   => round($totals['bounceRate'] * 100, 1),
            'presets'      => [
                '7d'   => '7 dana',
                '30d'  => '30 dana',
                '90d'  => '90 dana',
                '365d' => '12 mjeseci',
                'all'  => 'Sve vrijeme',
            ],
        ];
    }
}
