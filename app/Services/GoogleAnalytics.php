<?php

namespace App\Services;

use Google\Analytics\Data\V1beta\BetaAnalyticsDataClient;
use Google\Analytics\Data\V1beta\DateRange;
use Google\Analytics\Data\V1beta\Dimension;
use Google\Analytics\Data\V1beta\Metric;
use Google\Analytics\Data\V1beta\OrderBy;
use Google\Analytics\Data\V1beta\OrderBy\MetricOrderBy;
use Google\Analytics\Data\V1beta\RunReportRequest;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class GoogleAnalytics
{
    protected ?BetaAnalyticsDataClient $client = null;

    public function isConfigured(): bool
    {
        $path = base_path((string) config('services.google_analytics.credentials_path'));
        return config('services.google_analytics.property_id')
            && file_exists($path)
            && $this->sdkInstalled();
    }

    public function sdkInstalled(): bool
    {
        return class_exists(BetaAnalyticsDataClient::class);
    }

    protected function client(): BetaAnalyticsDataClient
    {
        if ($this->client) return $this->client;

        $path = base_path((string) config('services.google_analytics.credentials_path'));

        return $this->client = new BetaAnalyticsDataClient([
            'credentials' => $path,
        ]);
    }

    protected function propertyPath(): string
    {
        return 'properties/' . config('services.google_analytics.property_id');
    }

    /** Cache key builder — 5-minute cache to stay well under GA daily quota. */
    protected function cacheKey(string $suffix): string
    {
        return 'ga:' . md5(config('services.google_analytics.property_id') . '|' . $suffix);
    }

    /**
     * Site-wide KPIs.
     *
     * @return array{
     *   sessions:int, users:int, newUsers:int, pageviews:int,
     *   avgSessionSec:float, bounceRate:float,
     *   sessionsPrev:int, usersPrev:int
     * }
     */
    public function totals(string $startDate, string $endDate, ?string $prevStart = null, ?string $prevEnd = null): array
    {
        $ck = $this->cacheKey("totals:{$startDate}:{$endDate}:{$prevStart}:{$prevEnd}");

        return Cache::remember($ck, 300, function () use ($startDate, $endDate, $prevStart, $prevEnd) {
            try {
                $ranges = [new DateRange(['start_date' => $startDate, 'end_date' => $endDate])];
                if ($prevStart && $prevEnd) {
                    $ranges[] = new DateRange(['start_date' => $prevStart, 'end_date' => $prevEnd]);
                }

                $req = new RunReportRequest([
                    'property'   => $this->propertyPath(),
                    'dateRanges' => $ranges,
                    'metrics'    => [
                        new Metric(['name' => 'sessions']),
                        new Metric(['name' => 'totalUsers']),
                        new Metric(['name' => 'newUsers']),
                        new Metric(['name' => 'screenPageViews']),
                        new Metric(['name' => 'averageSessionDuration']),
                        new Metric(['name' => 'bounceRate']),
                    ],
                ]);

                $res = $this->client()->runReport($req);
                $rows = iterator_to_array($res->getRows());

                $get = function ($rowIndex) use ($rows) {
                    if (! isset($rows[$rowIndex])) return array_fill(0, 6, 0);
                    return array_map(fn ($v) => $v->getValue(), iterator_to_array($rows[$rowIndex]->getMetricValues()));
                };

                $cur = $get(0);
                $prev = $get(1);

                return [
                    'sessions'       => (int) $cur[0],
                    'users'          => (int) $cur[1],
                    'newUsers'       => (int) $cur[2],
                    'pageviews'      => (int) $cur[3],
                    'avgSessionSec'  => (float) $cur[4],
                    'bounceRate'     => (float) $cur[5],
                    'sessionsPrev'   => (int) $prev[0],
                    'usersPrev'      => (int) $prev[1],
                    'pageviewsPrev'  => (int) $prev[3],
                    'newUsersPrev'   => (int) $prev[2],
                ];
            } catch (\Throwable $e) {
                Log::warning('GA totals failed: ' . $e->getMessage());
                return $this->emptyTotals();
            }
        });
    }

    /** Sessions per day time series. */
    public function timeseries(string $startDate, string $endDate): array
    {
        $ck = $this->cacheKey("series:{$startDate}:{$endDate}");

        return Cache::remember($ck, 300, function () use ($startDate, $endDate) {
            try {
                $req = new RunReportRequest([
                    'property'   => $this->propertyPath(),
                    'dateRanges' => [new DateRange(['start_date' => $startDate, 'end_date' => $endDate])],
                    'dimensions' => [new Dimension(['name' => 'date'])],
                    'metrics'    => [
                        new Metric(['name' => 'sessions']),
                        new Metric(['name' => 'totalUsers']),
                    ],
                    'orderBys'   => [
                        new OrderBy(['dimension' => new OrderBy\DimensionOrderBy(['dimension_name' => 'date'])]),
                    ],
                ]);

                $res = $this->client()->runReport($req);
                $out = [];
                foreach ($res->getRows() as $row) {
                    $dims = iterator_to_array($row->getDimensionValues());
                    $metrics = iterator_to_array($row->getMetricValues());
                    $raw = $dims[0]->getValue(); // YYYYMMDD
                    $date = \Carbon\Carbon::createFromFormat('Ymd', $raw);
                    $out[] = [
                        'date'     => $date->toDateString(),
                        'label'    => $date->translatedFormat('d.m'),
                        'sessions' => (int) $metrics[0]->getValue(),
                        'users'    => (int) $metrics[1]->getValue(),
                    ];
                }
                return $out;
            } catch (\Throwable $e) {
                Log::warning('GA timeseries failed: ' . $e->getMessage());
                return [];
            }
        });
    }

    /** Top pages by pageviews. */
    public function topPages(string $startDate, string $endDate, int $limit = 10): array
    {
        $ck = $this->cacheKey("pages:{$startDate}:{$endDate}:{$limit}");

        return Cache::remember($ck, 300, function () use ($startDate, $endDate, $limit) {
            try {
                $req = new RunReportRequest([
                    'property'   => $this->propertyPath(),
                    'dateRanges' => [new DateRange(['start_date' => $startDate, 'end_date' => $endDate])],
                    'dimensions' => [new Dimension(['name' => 'pagePath']), new Dimension(['name' => 'pageTitle'])],
                    'metrics'    => [new Metric(['name' => 'screenPageViews']), new Metric(['name' => 'totalUsers'])],
                    'orderBys'   => [new OrderBy([
                        'metric' => new MetricOrderBy(['metric_name' => 'screenPageViews']),
                        'desc'   => true,
                    ])],
                    'limit'      => $limit,
                ]);
                $res = $this->client()->runReport($req);
                $out = [];
                foreach ($res->getRows() as $row) {
                    $dims = iterator_to_array($row->getDimensionValues());
                    $metrics = iterator_to_array($row->getMetricValues());
                    $out[] = [
                        'path'      => $dims[0]->getValue(),
                        'title'     => $dims[1]->getValue(),
                        'pageviews' => (int) $metrics[0]->getValue(),
                        'users'     => (int) $metrics[1]->getValue(),
                    ];
                }
                return $out;
            } catch (\Throwable $e) {
                Log::warning('GA topPages failed: ' . $e->getMessage());
                return [];
            }
        });
    }

    /** Top traffic sources (channel groupings). */
    public function topSources(string $startDate, string $endDate, int $limit = 8): array
    {
        $ck = $this->cacheKey("sources:{$startDate}:{$endDate}:{$limit}");

        return Cache::remember($ck, 300, function () use ($startDate, $endDate, $limit) {
            try {
                $req = new RunReportRequest([
                    'property'   => $this->propertyPath(),
                    'dateRanges' => [new DateRange(['start_date' => $startDate, 'end_date' => $endDate])],
                    'dimensions' => [new Dimension(['name' => 'sessionDefaultChannelGroup'])],
                    'metrics'    => [new Metric(['name' => 'sessions']), new Metric(['name' => 'totalUsers'])],
                    'orderBys'   => [new OrderBy([
                        'metric' => new MetricOrderBy(['metric_name' => 'sessions']),
                        'desc'   => true,
                    ])],
                    'limit'      => $limit,
                ]);
                $res = $this->client()->runReport($req);
                $out = [];
                foreach ($res->getRows() as $row) {
                    $dims = iterator_to_array($row->getDimensionValues());
                    $metrics = iterator_to_array($row->getMetricValues());
                    $out[] = [
                        'channel'  => $dims[0]->getValue() ?: '(direct)',
                        'sessions' => (int) $metrics[0]->getValue(),
                        'users'    => (int) $metrics[1]->getValue(),
                    ];
                }
                return $out;
            } catch (\Throwable $e) {
                Log::warning('GA topSources failed: ' . $e->getMessage());
                return [];
            }
        });
    }

    /** Device category breakdown (mobile / desktop / tablet). */
    public function deviceBreakdown(string $startDate, string $endDate): array
    {
        $ck = $this->cacheKey("devices:{$startDate}:{$endDate}");

        return Cache::remember($ck, 300, function () use ($startDate, $endDate) {
            try {
                $req = new RunReportRequest([
                    'property'   => $this->propertyPath(),
                    'dateRanges' => [new DateRange(['start_date' => $startDate, 'end_date' => $endDate])],
                    'dimensions' => [new Dimension(['name' => 'deviceCategory'])],
                    'metrics'    => [new Metric(['name' => 'sessions'])],
                ]);
                $res = $this->client()->runReport($req);
                $out = [];
                foreach ($res->getRows() as $row) {
                    $dims = iterator_to_array($row->getDimensionValues());
                    $metrics = iterator_to_array($row->getMetricValues());
                    $out[] = [
                        'device'   => $dims[0]->getValue(),
                        'sessions' => (int) $metrics[0]->getValue(),
                    ];
                }
                return $out;
            } catch (\Throwable $e) {
                Log::warning('GA deviceBreakdown failed: ' . $e->getMessage());
                return [];
            }
        });
    }

    /** Real-time active users (last 30 min). Short cache so the number stays fresh. */
    public function activeUsers(): int
    {
        return Cache::remember($this->cacheKey('activeUsers'), 15, function () {
            try {
                $res = $this->client()->runRealtimeReport([
                    'property' => $this->propertyPath(),
                    'metrics'  => [new Metric(['name' => 'activeUsers'])],
                ]);
                $rows = iterator_to_array($res->getRows());
                if (! isset($rows[0])) return 0;
                $metrics = iterator_to_array($rows[0]->getMetricValues());
                return (int) $metrics[0]->getValue();
            } catch (\Throwable $e) {
                Log::warning('GA activeUsers failed: ' . $e->getMessage());
                return 0;
            }
        });
    }

    protected function emptyTotals(): array
    {
        return [
            'sessions' => 0, 'users' => 0, 'newUsers' => 0, 'pageviews' => 0,
            'avgSessionSec' => 0.0, 'bounceRate' => 0.0,
            'sessionsPrev' => 0, 'usersPrev' => 0, 'pageviewsPrev' => 0, 'newUsersPrev' => 0,
        ];
    }
}
