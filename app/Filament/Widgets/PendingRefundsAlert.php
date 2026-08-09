<?php

namespace App\Filament\Widgets;

use App\Enums\RefundStatus;
use App\Filament\Resources\RefundRequestResource;
use App\Models\RefundRequest;
use Filament\Widgets\Widget;

class PendingRefundsAlert extends Widget
{
    protected static string $view = 'filament.widgets.pending-refunds-alert';
    protected static ?int $sort = -20;
    protected int | string | array $columnSpan = 'full';

    public function getViewData(): array
    {
        $q = RefundRequest::where('status', RefundStatus::PENDING->value);

        return [
            'count'   => (int) $q->count(),
            'latest'  => $q->orderByDesc('created_at')->limit(3)->get(),
            'listUrl' => RefundRequestResource::getUrl('index'),
        ];
    }

    public static function canView(): bool
    {
        return RefundRequest::where('status', RefundStatus::PENDING->value)->exists();
    }
}
