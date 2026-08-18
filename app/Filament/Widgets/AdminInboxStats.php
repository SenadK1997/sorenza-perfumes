<?php

namespace App\Filament\Widgets;

use App\Enums\OrderStatus;
use App\Enums\PerfumeRequestStatus;
use App\Enums\RefundStatus;
use App\Filament\Resources\CustomerMessageThreadResource;
use App\Filament\Resources\OrderResource;
use App\Filament\Resources\PerfumeRequestResource;
use App\Filament\Resources\RefundRequestResource;
use App\Models\Order;
use App\Models\PerfumeRequest;
use App\Models\RefundRequest;
use App\Services\Messaging;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;

class AdminInboxStats extends BaseWidget
{
    protected static ?int $sort = -100;
    protected int | string | array $columnSpan = 'full';

    public static function canView(): bool
    {
        return Auth::user()?->hasRole('admin') ?? false;
    }

    protected function getStats(): array
    {
        $unreadMessages = Messaging::totalAdminUnread();

        $pendingOrders = Order::where('status', OrderStatus::PENDING->value)->count();

        $pendingRefunds = RefundRequest::where('status', RefundStatus::PENDING->value)->count();

        $pendingSellerRequests = PerfumeRequest::where('status', PerfumeRequestStatus::PENDING->value)->count();

        return [
            Stat::make('Nove poruke', $unreadMessages)
                ->description('Neodgovoreni razgovori sa kupcima')
                ->descriptionIcon('heroicon-o-chat-bubble-left-right')
                ->color($unreadMessages > 0 ? 'danger' : 'gray')
                ->url(CustomerMessageThreadResource::getUrl('index')),

            Stat::make('Nove narudžbe', $pendingOrders)
                ->description('Slobodne narudžbe koje čekaju prodavača')
                ->descriptionIcon('heroicon-o-rectangle-stack')
                ->color($pendingOrders > 0 ? 'warning' : 'gray')
                ->url(OrderResource::getUrl('index')),

            Stat::make('Zahtjevi za povrat', $pendingRefunds)
                ->description('Novi zahtjevi na obradu')
                ->descriptionIcon('heroicon-o-arrow-uturn-left')
                ->color($pendingRefunds > 0 ? 'danger' : 'gray')
                ->url(RefundRequestResource::getUrl('index')),

            Stat::make('Zahtjevi od prodavača', $pendingSellerRequests)
                ->description('Novi zahtjevi za parfeme')
                ->descriptionIcon('heroicon-o-sparkles')
                ->color($pendingSellerRequests > 0 ? 'primary' : 'gray')
                ->url(PerfumeRequestResource::getUrl('index')),
        ];
    }
}
