<?php

namespace App\Filament\Pages;

use App\Models\Perfume;
use App\Models\User;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SellersStock extends Page
{
    protected static ?string $navigationIcon  = 'heroicon-o-archive-box';
    protected static ?string $navigationLabel = 'Lager prodavača';
    protected static ?string $navigationGroup = 'Administracija';
    protected static ?string $title           = 'Lager prodavača';
    protected static ?int $navigationSort     = 10;
    protected static string $view             = 'filament.pages.sellers-stock';

    public ?int $expandedSellerId = null;
    public string $search = '';

    public static function canAccess(): bool
    {
        return Auth::user()?->hasRole('admin') ?? false;
    }

    public function toggle(int $sellerId): void
    {
        $this->expandedSellerId = $this->expandedSellerId === $sellerId ? null : $sellerId;
    }

    protected function sellers()
    {
        // Users who have any pivot rows in perfume_seller
        return User::query()
            ->select('users.id', 'users.name', 'users.email')
            ->selectRaw('(
                SELECT COUNT(DISTINCT ps.perfume_id)
                FROM perfume_seller ps
                JOIN perfumes p ON p.id = ps.perfume_id
                WHERE ps.user_id = users.id
                  AND ps.stock > 0
                  AND p.availability = 1
            ) as unique_in_stock')
            ->selectRaw('(
                SELECT COUNT(DISTINCT p.id) FROM perfumes p WHERE p.availability = 1
            ) as catalog_total')
            ->selectRaw('(
                SELECT COALESCE(SUM(ps.stock), 0)
                FROM perfume_seller ps
                JOIN perfumes p ON p.id = ps.perfume_id
                WHERE ps.user_id = users.id AND ps.stock > 0 AND p.availability = 1
            ) as total_units')
            ->selectRaw('(
                SELECT COALESCE(SUM(ps.stock * p.base_price), 0)
                FROM perfume_seller ps
                JOIN perfumes p ON p.id = ps.perfume_id
                WHERE ps.user_id = users.id AND ps.stock > 0 AND p.availability = 1
            ) as inventory_value')
            ->whereExists(function ($q) {
                $q->select(DB::raw(1))
                  ->from('perfume_seller')
                  ->whereColumn('perfume_seller.user_id', 'users.id');
            })
            ->when($this->search, fn ($q) =>
                $q->where(fn ($sub) => $sub
                    ->where('users.name', 'like', "%{$this->search}%")
                    ->orWhere('users.email', 'like', "%{$this->search}%"))
            )
            ->orderByDesc('inventory_value')
            ->get();
    }

    protected function perfumesFor(int $userId)
    {
        return Perfume::query()
            ->select('perfumes.id', 'perfumes.name', 'perfumes.inspired_by', 'perfumes.base_price', 'perfumes.availability')
            ->join('perfume_seller', 'perfume_seller.perfume_id', '=', 'perfumes.id')
            ->addSelect('perfume_seller.stock as pivot_stock')
            ->where('perfume_seller.user_id', $userId)
            ->where('perfume_seller.stock', '>', 0)
            ->orderBy('perfume_seller.stock', 'asc')
            ->get();
    }

    public function getViewData(): array
    {
        $sellers = $this->sellers();

        $expandedRows = collect();
        if ($this->expandedSellerId && $sellers->firstWhere('id', $this->expandedSellerId)) {
            $expandedRows = $this->perfumesFor($this->expandedSellerId);
        }

        return [
            'sellers'       => $sellers,
            'expandedId'    => $this->expandedSellerId,
            'expandedRows'  => $expandedRows,
            'totals'        => [
                'sellers'         => $sellers->count(),
                'unique_perfumes' => $sellers->sum('unique_in_stock'),
                'total_units'     => $sellers->sum('total_units'),
                'inventory_value' => $sellers->sum('inventory_value'),
            ],
        ];
    }
}
