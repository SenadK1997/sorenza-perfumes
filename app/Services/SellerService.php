<?php

namespace App\Services;

use App\Models\Perfume;
use Illuminate\Support\Facades\Auth;

class SellerService
{
    /**
     * Record a perfume as sold by the user.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Perfume  $perfume
     * @param  bool  $isManual
     * @return void
     */
    public static function recordPerfumeSold($user, Perfume $perfume, int $quantity = 1, bool $isManual = true, int $customerId = null): void
    {
        $pivot = $user->perfumes()->where('perfume_id', $perfume->id)->first()?->pivot;

        if (!$pivot) {
            throw new \Exception('Seller does not have this perfume in stock.');
        }

        // 1. Skidanje sa stanja
        if ($pivot->stock >= $quantity) {
            $user->perfumes()->updateExistingPivot($perfume->id, [
                'stock' => $pivot->stock - $quantity
            ]);
        } else {
            $user->perfumes()->detach($perfume->id);
        }

        // 2. Kreiranje prodaje
        // Uvijek kreiramo NOVI red ako imamo kupca ili ako je narudžba.
        // Samo potpuno anonimne manualne prodaje možemo grupisati (opcionalno), 
        // ali radi najbolje analitike, čak i za njih je bolje kreirati novi red.
        
        $user->soldPerfumes()->create([
            'perfume_id'  => $perfume->id,
            'customer_id' => $customerId, // Može biti null za anonimne
            'quantity'    => $quantity,
            'base_price'  => $perfume->base_price, // Čuvamo cijenu u trenutku prodaje
            'is_manual'   => $isManual,
            'sold_at'     => now(), // Ovo je ključno za tvoje mjesečne izvještaje
        ]);

        // 3. Isplata prodavaču (Seller Payment)
        self::handleSellerPayment($user, ($perfume->base_price * $quantity));
    }
    protected static function handleSellerPayment($user, $amount)
    {
        $lastPayment = $user->sellerPayments()
            ->where('status', 'Hold')
            ->latest()
            ->first();

        if ($lastPayment) {
            $lastPayment->increment('amount', $amount);
        } else {
            $user->sellerPayments()->create([
                'amount' => $amount,
                'status' => 'Hold',
            ]);
        }
    }
}
