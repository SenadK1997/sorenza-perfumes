<?php

namespace App\Filament\Seller\Widgets;

use App\Filament\Seller\Resources\PerfumeRequestResource;
use App\Models\Perfume;
use Filament\Widgets\Widget;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MissingPerfumes extends Widget
{
    protected static string $view = 'filament.seller.widgets.missing-perfumes';
    protected static ?int $sort = -7;
    protected int | string | array $columnSpan = 'full';

    public function getViewData(): array
    {
        $userId = Auth::id();

        $ownedIds = DB::table('perfume_seller')
            ->where('user_id', $userId)
            ->where('stock', '>', 0)
            ->pluck('perfume_id');

        $missing = Perfume::query()
            ->where('availability', true)
            ->whereNotIn('id', $ownedIds)
            ->orderByRaw('LENGTH(name), name')  // "01","02",...,"10" ordered numerically
            ->get(['id', 'name']);

        return [
            'missing'   => $missing,
            'requestUrl' => PerfumeRequestResource::getUrl('create'),
        ];
    }

    public static function canView(): bool
    {
        $ownedIds = DB::table('perfume_seller')
            ->where('user_id', Auth::id())
            ->where('stock', '>', 0)
            ->pluck('perfume_id');

        return Perfume::where('availability', true)
            ->whereNotIn('id', $ownedIds)
            ->exists();
    }
}
