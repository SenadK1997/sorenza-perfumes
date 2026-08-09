<?php

namespace App\Filament\Seller\Widgets;

use App\Filament\Seller\Resources\PerfumeRequestResource;
use App\Models\PerfumeRequest;
use Filament\Widgets\Widget;
use Illuminate\Support\Facades\Auth;

class RequestPerfumesCta extends Widget
{
    protected static string $view = 'filament.seller.widgets.request-perfumes-cta';
    protected static ?int $sort = -8;
    protected int | string | array $columnSpan = 'full';

    public function getViewData(): array
    {
        $userId = Auth::id();

        return [
            'createUrl'    => PerfumeRequestResource::getUrl('create'),
            'indexUrl'     => PerfumeRequestResource::getUrl('index'),
            'pendingCount' => PerfumeRequest::where('user_id', $userId)
                ->where('status', 'pending')
                ->count(),
        ];
    }
}
