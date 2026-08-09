<?php

namespace App\Filament\Seller\Widgets;

use App\Enums\RefundStatus;
use App\Filament\Seller\Resources\RefundRequestResource;
use App\Models\RefundRequest;
use Filament\Widgets\Widget;
use Illuminate\Support\Facades\Auth;

class PendingRefundsAlert extends Widget
{
    protected static string $view = 'filament.seller.widgets.pending-refunds-alert';
    protected static ?int $sort = -20;
    protected int | string | array $columnSpan = 'full';

    protected function baseQuery()
    {
        return RefundRequest::where('user_id', Auth::id())
            ->where('status', RefundStatus::PENDING->value);
    }

    public function getViewData(): array
    {
        $q = $this->baseQuery();

        return [
            'count'   => (int) $q->count(),
            'latest'  => $q->orderByDesc('created_at')->limit(3)->get(),
            'listUrl' => RefundRequestResource::getUrl('index'),
        ];
    }

    public static function canView(): bool
    {
        return RefundRequest::where('user_id', Auth::id())
            ->where('status', RefundStatus::PENDING->value)
            ->exists();
    }
}
