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
use App\Services\SellerService;
use Filament\Notifications\Notification;
use Filament\Forms\Components\TextInput;
use App\Models\Customer;

class SellerDashboard extends Page implements Tables\Contracts\HasTable
{
    use Tables\Concerns\InteractsWithTable;

    protected static ?string $title = 'Prodaja Parfema';
    protected static string $view = 'filament.seller.pages.seller-dashboard';
    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';
    protected static ?string $navigationGroup = 'Parfemi';

    protected function getTableQuery(): Builder
    {
        $user = Auth::user();

        return Perfume::whereHas('sellers', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })
        ->with(['sellers' => function ($query) use ($user) {
            $query->where('user_id', $user->id);
        }]);
    }

    protected function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('name')
                ->label('Perfume Name')
                ->description(fn (Perfume $record): string => $record->inspired_by ?? '')
                ->sortable()
                ->searchable(['name', 'inspired_by']),
                
            Tables\Columns\TextColumn::make('price')
                ->label('Cijena')
                ->money('bam', true),

            Tables\Columns\TextColumn::make('stock')
                ->label('Na stanju')
                ->badge()
                ->color(fn ($state): string => match (true) {
                    $state <= 2 => 'danger',
                    $state <= 5 => 'warning',
                    default => 'success',
                })
                ->getStateUsing(function (Perfume $record) {
                    $user = Auth::user();
                    $seller = $record->sellers->firstWhere('id', $user->id);
                    return $seller?->pivot?->stock ?? 0;
                }),
        ];
    }

    protected function getTableActions(): array
    {
        return [
            Action::make('sold')
                ->label('Prodaj')
                ->icon('heroicon-m-banknotes')
                ->color('success')
                // 1. ONEMOGUĆI DUGME AKO JE STOCK 0
                ->disabled(function (Perfume $record) {
                    $user = Auth::user();
                    $seller = $record->sellers->firstWhere('id', $user->id);
                    return ($seller?->pivot?->stock ?? 0) <= 0;
                })
                ->form([
                    Select::make('customer_id')
                        ->label('Kupac (opcionalno)')
                        ->placeholder('Počnite kucati ime...')
                        ->searchable()
                        ->options(function () {
                            $user = auth()->user();
                            $query = Customer::query();
                            if (!$user->hasRole('admin')) {
                                $query->where('user_id', $user->id);
                            }
                            return $query->latest()->limit(20)->get()->mapWithKeys(fn($c) => [$c->id => "{$c->full_name}"]);
                        })
                        ->getSearchResultsUsing(function (string $search) {
                            $user = auth()->user();
                            $query = Customer::where('full_name', 'like', "%{$search}%");
                            if (!$user->hasRole('admin')) {
                                $query->where('user_id', $user->id);
                            }
                            return $query->limit(50)->get()->mapWithKeys(fn($c) => [$c->id => "{$c->full_name}"]);
                        }),
                                        
                    TextInput::make('quantity')
                        ->label('Quantity')
                        ->numeric()
                        ->default(1)
                        ->required()
                        ->minValue(1)
                        // 2. DODAJ MAX LIMIT NA OSNOVU STANJA
                        ->maxValue(function (Perfume $record) {
                            $user = Auth::user();
                            $seller = $record->sellers->firstWhere('id', $user->id);
                            return $seller?->pivot?->stock ?? 0;
                        }),
                ])
                ->action(function (Perfume $record, array $data) {
                    $user = Auth::user();
                    SellerService::recordPerfumeSold($user, $record, $data['quantity'], true, $data['customer_id'] ?? null);

                    Notification::make()->title('Sale recorded successfully')->success()->send();
                }),

            Action::make('cancel')
                ->label('Storniraj')
                ->icon('heroicon-m-arrow-uturn-left')
                ->color('warning')
                ->form([
                    Select::make('customer_id')
                        ->label('Kupac')
                        ->options(fn() => Customer::pluck('full_name', 'id'))
                        ->searchable(),
                        
                    TextInput::make('quantity')
                        ->label('Količina')
                        ->numeric()
                        ->default(1)
                        ->required(),

                    Select::make('cancellation_reason')
                        ->label('Razlog')
                        ->required()
                        ->options([
                            'wrong_perfume' => 'Pogrešan parfem',
                            'broken' => 'Oštećeno/Polupano',
                            'returned' => 'Kupac vratio',
                        ]),
                ])
                ->action(function (Perfume $record, array $data) {
                    $user = Auth::user();
                    $qty = (int) $data['quantity'];

                    $user->soldPerfumes()->create([
                        'perfume_id'  => $record->id,
                        'customer_id' => $data['customer_id'] ?? null,
                        'quantity'    => -$qty,
                        'base_price'  => $record->base_price,
                        'is_manual'   => true,
                        'cancelled'   => true, 
                        'cancellation_reason' => $data['cancellation_reason'],
                        'sold_at'     => now(),
                    ]);

                    $pivot = $user->perfumes()->where('perfume_id', $record->id)->first()?->pivot;
                    if ($pivot) {
                        $user->perfumes()->updateExistingPivot($record->id, [
                            'stock' => $pivot->stock + $qty
                        ]);
                    }

                    Notification::make()->title('Storno uspješan')->warning()->send();
                }),
        ];
    }

    protected function getTableRecordKeyName(): string
    {
        return 'id';
    }

    public function getTotalStock(): int
    {
        return Auth::user()->perfumes()->get()->sum(fn ($perfume) => $perfume->pivot->stock);
    }
}