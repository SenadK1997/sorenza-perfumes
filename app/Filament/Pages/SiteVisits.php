<?php

namespace App\Filament\Pages;

use App\Services\GoogleAnalytics;
use Carbon\Carbon;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;

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

    protected function rangeBounds(): array
    {
        return match ($this->range) {
            '7d'  => ['start' => '7daysAgo',  'end' => 'today', 'label' => 'Zadnjih 7 dana',  'days' => 7],
            '90d' => ['start' => '90daysAgo', 'end' => 'today', 'label' => 'Zadnjih 90 dana', 'days' => 90],
            default => ['start' => '30daysAgo', 'end' => 'today', 'label' => 'Zadnjih 30 dana', 'days' => 30],
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
                'presets'    => ['7d' => '7 dana', '30d' => '30 dana', '90d' => '90 dana'],
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
            'presets'      => ['7d' => '7 dana', '30d' => '30 dana', '90d' => '90 dana'],
        ];
    }
}
