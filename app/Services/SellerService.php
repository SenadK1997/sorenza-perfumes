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
    public static function recordPerfumeSold($user, Perfume $perfume, int $quantity = 1, bool $isManual = true): void
    {
        $pivot = $user->perfumes()->where('perfume_id', $perfume->id)->first()?->pivot;

        if (! $pivot) {
            throw new \Exception('Could not find pivot entry for this perfume.');
        }

        // Decrease pivot stock
        if ($pivot->stock > $quantity) {
            $pivot->stock -= $quantity;
            $pivot->save();
        } else {
            $user->perfumes()->detach($perfume->id);
        }

        // Find correct sold record (manual or automatic)
        $soldPerfume = $user->soldPerfumes()
            ->where('perfume_id', $perfume->id)
            ->where('is_manual', $isManual)
            ->first();

        if ($soldPerfume) {
            $soldPerfume->increment('quantity', $quantity);
        } else {
            $user->soldPerfumes()->create([
                'perfume_id' => $perfume->id,
                'quantity'   => $quantity,
                'base_price' => $perfume->base_price,
                'is_manual'  => $isManual,
            ]);
        }

        // Seller payment
        $amountToAdd = $perfume->base_price * $quantity;

        $lastPayment = $user->sellerPayments()
            ->where('status', 'Hold')
            ->latest()
            ->first();

        if ($lastPayment) {
            $lastPayment->increment('amount', $amountToAdd);
        } else {
            $user->sellerPayments()->create([
                'amount' => $amountToAdd,
                'status' => 'Hold',
            ]);
        }
    }
}
