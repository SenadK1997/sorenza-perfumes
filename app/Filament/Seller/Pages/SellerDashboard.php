<?php

namespace App\Filament\Seller\Pages;

use App\Models\Perfume;
use Filament\Pages\Page;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use App\Services\SellerService;

class SellerDashboard extends Page implements Tables\Contracts\HasTable
{
    use Tables\Concerns\InteractsWithTable;

    protected static ?string $title = 'Seller Dashboard';

    protected static string $view = 'filament.seller.pages.seller-dashboard';

    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';

    /**
     * Return an Eloquent query builder for the table data
     */
    protected function getTableQuery(): Builder
    {
        $user = Auth::user();
        // return $user->perfumes()->withPivot('stock')->getQuery();

        return Perfume::whereHas('sellers', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })
        ->with(['sellers' => function ($query) use ($user) {
            $query->where('user_id', $user->id);
        }]);
    }

    /**
     * Define the columns for the table
     */
    protected function getTableColumns(): array
    {
        return [
            TextColumn::make('name')->label('Perfume Name')->sortable()->searchable(),
            TextColumn::make('base_price')->label('Base Price')->money('bam', true),
            TextColumn::make('stock')
                ->label('Stock')
                ->getStateUsing(function (Perfume $record) {
                    // Get the currently logged in seller
                    $user = Auth::user();

                    // Find the seller pivot for this perfume
                    $seller = $record->sellers->firstWhere('id', $user->id);

                    return $seller?->pivot?->stock ?? 'N/A';
                }),
        ];
    }

    /**
     * Define actions available on each row
     */
    protected function getTableActions(): array
    {
        return [
            Action::make('sold')
                ->label('Sold')
                ->requiresConfirmation()
                ->color('danger')
                ->action(function (Perfume $record) {
                    $user = Auth::user();
                    SellerService::recordPerfumeSold($user, $record, true);
                }),

            Action::make('cancel')
                ->label('Cancel')
                ->form([
                    Select::make('cancellation_reason')
                        ->label('Cancellation Reason')
                        ->required()
                        ->options([
                            'wrong_perfume' => 'Wrong Perfume',
                            'broken' => 'Broken',
                            'cancalled' => 'Cancelled',
                        ]),
                    Textarea::make('cancellation_notes')
                        ->label('Additional Notes')
                        ->nullable(),
                ])
                ->requiresConfirmation()
                ->color('warning')
                ->action(function (Perfume $record, array $data) {
                    $user = Auth::user();

                    $soldPerfume = $user->soldPerfumes()
                        ->where('perfume_id', $record->id)
                        ->first();

                    if (! $soldPerfume) {
                        throw new \Exception('No sold perfume record found to cancel.');
                    }

                    $soldPerfume->update([
                        'cancelled' => true,
                        'cancellation_reason' => $data['cancellation_reason'],
                        // you can add a new column for notes if you want to store them, or skip this
                    ]);
            }),
        ];
    }

    /**
     * You can define the record key if needed
     */
    protected function getTableRecordKeyName(): string
    {
        return 'id';
    }

    /**
     * Additional method example to calculate total base price of perfumes assigned
     */
    public function getTotalBasePrice(): float
    {
        $user = Auth::user();

        return $user->perfumes()->sum('base_price');
    }

    public function getTotalStock(): int
    {
        return Auth::user()
            ->perfumes()
            ->get()
            ->sum(fn ($perfume) => $perfume->pivot->stock);
    }
}
