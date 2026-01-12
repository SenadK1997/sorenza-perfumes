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
            Tables\Columns\TextColumn::make('name')
                ->label('Perfume Name')
                ->description(fn (Perfume $record): string => $record->inspired_by ?? '') // Prikazuje originalni naziv ispod tvog
                ->sortable()
                ->searchable(['name', 'inspired_by']), // Omogućava pretragu po oba polja!
                
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

    /**
     * Define actions available on each row
     */
    protected function getTableActions(): array
    {
        return [
            Action::make('sold')
                ->label('Prodaj')
                ->icon('heroicon-m-banknotes')
                ->color('success')
                ->form([
                    Select::make('customer_id')
                        ->label('Kupac (za koga se radi storno)')
                        ->placeholder('Počnite kucati ime ili email...')
                        ->searchable()
                        // 1. Inicijalno punjenje opcija (npr. zadnjih 20 kupaca radi brzine)
                        ->options(function () {
                            $user = auth()->user();
                            $query = Customer::query();

                            if (!$user->hasRole('admin')) {
                                $query->where('user_id', $user->id);
                            }

                            return $query->latest()->limit(20)->get()->mapWithKeys(function ($customer) {
                                return [$customer->id => "{$customer->full_name} ({$customer->email})"];
                            });
                        })
                        // 2. Dinamička pretraga dok korisnik kuca
                        ->getSearchResultsUsing(function (string $search) {
                            $user = auth()->user();
                            
                            $query = Customer::where(function($q) use ($search) {
                                $q->where('full_name', 'like', "%{$search}%")
                                ->orWhere('email', 'like', "%{$search}%");
                            });

                            if (!$user->hasRole('admin')) {
                                $query->where('user_id', $user->id);
                            }

                            return $query->limit(50)->get()->mapWithKeys(function ($customer) {
                                return [$customer->id => "{$customer->full_name} ({$customer->email})"];
                            });
                        }),
                                        
                    TextInput::make('quantity')
                        ->label('Quantity')
                        ->numeric()
                        ->default(1)
                        ->required()
                        ->minValue(1),
                ])
                ->action(function (Perfume $record, array $data) {
                    $user = Auth::user();
                    
                    // Pozivamo servis sa customer_id (koji može biti null)
                    SellerService::recordPerfumeSold(
                        $user, 
                        $record, 
                        $data['quantity'], 
                        true, // isManual = true
                        $data['customer_id'] ?? null
                    );

                    Notification::make()
                        ->title('Sale recorded successfully')
                        ->success()
                        ->send();
                }),

            Action::make('cancel')
                ->label('Storniraj')
                ->icon('heroicon-m-arrow-uturn-left')
                ->color('warning')
                ->form([
                    Select::make('customer_id')
                        ->label('Kupac (ako je poznat)')
                        ->options(fn() => Customer::pluck('full_name', 'id'))
                        ->searchable()
                        ->placeholder('Anonimni kupac'),
                        
                    TextInput::make('quantity')
                        ->label('Količina za storniranje')
                        ->numeric()
                        ->default(1)
                        ->required(),

                    Select::make('cancellation_reason')
                        ->label('Razlog storna')
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
                    $stornoAmount = $record->base_price * $qty;

                    // 1. Kreiraj storno zapis (negativna količina)
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

                    // 2. Vrati stock
                    $pivot = $user->perfumes()->where('perfume_id', $record->id)->first()?->pivot;
                    if ($pivot) {
                        $user->perfumes()->updateExistingPivot($record->id, [
                            'stock' => $pivot->stock + $qty
                        ]);
                    }

                    Notification::make()
                        ->title('Storno uspješan')
                        ->body('Zaliha je vraćena na vaše stanje. Dug će biti korigovan nakon potvrde admina.')
                        ->warning()
                        ->send();
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
