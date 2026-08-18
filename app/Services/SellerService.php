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
    /**
     * Record a perfume sale.
     *
     * @param float|null $customerPrice What the customer actually paid per unit.
     *                                  Manual direct sales: seller enters this (may differ from perfume.price
     *                                  if seller gave an in-person discount).
     *                                  Order-derived: pass the pivot price so the customer view stays exact.
     *                                  If null, we snapshot the perfume's current selling price as a fallback.
     */
    public static function recordPerfumeSold($user, Perfume $perfume, int $quantity = 1, bool $isManual = true, int $customerId = null, ?float $customerPrice = null): void
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
        //    base_price     = vault / seller-payment side (unchanged, snapshot of cost)
        //    customer_price = what the customer actually paid per unit (may include a discount)
        $customerPrice = $customerPrice ?? (float) $perfume->price;

        $user->soldPerfumes()->create([
            'perfume_id'     => $perfume->id,
            'customer_id'    => $customerId, // Može biti null za anonimne
            'quantity'       => $quantity,
            'base_price'     => $perfume->base_price,
            'customer_price' => $customerPrice,
            'is_manual'      => $isManual,
            'sold_at'        => now(),
        ]);

        // 3. Isplata prodavaču (Seller Payment) — uvijek se računa iz base_price × qty
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
