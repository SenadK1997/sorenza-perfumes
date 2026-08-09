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

        // Extract service account email from JSON so we can display + compare
        $serviceEmail = 'unknown';
        if ($fileExists) {
            $json = @json_decode(@file_get_contents($credPath), true);
            $serviceEmail = $json['client_email'] ?? 'unknown';
        }

        $sdk = $ga->sdkInstalled();

        $lines = [
            '• Property ID: ' . ($propId ?: 'MISSING'),
            '• Servis konto: ' . $serviceEmail,
            '• Fajl postoji: ' . ($fileExists ? 'DA' : 'NE'),
            '• SDK instaliran: ' . ($sdk ? 'DA' : 'NE (google/analytics-data paket nedostaje!)'),
            '• Konfigurisan: ' . ($configured ? 'DA' : 'NE'),
        ];

        if (! $sdk) {
            Notification::make()
                ->title('SDK PAKET NEDOSTAJE U KONTEJNERU')
                ->body(implode("\n", $lines)
                    . "\n\n→ Coolify build nije instalirao google/analytics-data paket."
                    . "\n→ Provjerite deployment logove: traži liniju 'google/analytics-data'."
                    . "\n→ Ako je nema, ručno pokrenite 'composer install --no-dev' u kontejneru.")
                ->danger()
                ->persistent()
                ->send();
            return;
        }

        if (! $configured || ! $fileExists) {
            Notification::make()
                ->title('GA nije spreman')
                ->body(implode("\n", $lines))
                ->danger()
                ->persistent()
                ->send();
            return;
        }

        // Aggressively test with a wide date range and low-level call
        try {
            Cache::flush();

            // 1) Raw wide-range totals ("Sve vrijeme")
            $t = $ga->totals('2020-01-01', 'today');
            $lines[] = '• Sesije (sve vrijeme): ' . $t['sessions'];
            $lines[] = '• Posjetioci (sve vrijeme): ' . $t['users'];
            $lines[] = '• Pregleda (sve vrijeme): ' . $t['pageviews'];

            // 2) Yesterday specifically (should ALWAYS have data if any tracking happened)
            $y = $ga->totals('yesterday', 'yesterday');
            $lines[] = '• Jučer: ' . $y['sessions'] . ' sesija, ' . $y['users'] . ' posj.';

            // 3) Realtime
            $rt = $ga->activeUsers();
            $lines[] = '• Realtime: ' . $rt . ' aktivnih';

            // 4) Try a DIFFERENT API surface — list metadata for the property
            //    If this succeeds, permissions work. If it fails, permissions issue.
            $metaOk = 'NEPOZNATO';
            try {
                $client = new \Google\Analytics\Data\V1beta\BetaAnalyticsDataClient([
                    'credentials' => $credPath,
                ]);
                $meta = $client->getMetadata('properties/' . $propId . '/metadata');
                $count = count(iterator_to_array($meta->getMetrics()));
                $metaOk = "DA ({$count} metrika)";
            } catch (\Throwable $me) {
                $metaOk = 'NE — ' . substr($me->getMessage(), 0, 200);
            }
            $lines[] = '• Metadata API: ' . $metaOk;

            $hasAnyData = $t['sessions'] > 0 || $t['users'] > 0 || $t['pageviews'] > 0 || $rt > 0;

            $verdict = $hasAnyData
                ? ['title' => 'GA radi ✓', 'kind' => 'success', 'extra' => '']
                : ['title' => 'API poziv prolazi, ali vraća 0 svugdje', 'kind' => 'warning',
                   'extra' => "\n\n→ Servisni konto NE VIDI podatke ovog property-ja iako je auth ispravan.\n"
                            . "→ Idite u GA Admin → PROPERTY (desna kolona) → Property Access Management\n"
                            . "→ Provjerite da tačno ovaj email postoji: {$serviceEmail}\n"
                            . "→ Ako postoji samo pod Account Access (lijeva kolona), obrišite ga i dodajte ISKLJUČIVO pod Property Access."];

            Notification::make()
                ->title($verdict['title'])
                ->body(implode("\n", $lines) . $verdict['extra'])
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
