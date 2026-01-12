<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    /**
     * Dozvoli adminima da vide listu.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasRole('admin');
    }

    /**
     * Dozvoli adminima da vide profil.
     */
    public function view(User $user, User $model): bool
    {
        return $user->hasRole('admin');
    }

    /**
     * Dozvoli adminima kreiranje novih usera.
     */
    public function create(User $user): bool
    {
        return $user->hasRole('admin');
    }

    /**
     * ZAŠTITA: Samo ti (Senad) možeš editovati sebe.
     * Ostali admini edituju sve osim tebe.
     */
    public function update(User $user, User $model): bool
    {
        // Ako je korisnik kojeg pokušavamo editovati Senad
        if ($model->email === 'senad.okt97@gmail.com') {
            // Dozvoli samo ako si to ti lično ulogovan
            return $user->email === 'senad.okt97@gmail.com';
        }

        // Sve ostale korisnike mogu editovati svi koji imaju ulogu admin
        return $user->hasRole('admin');
    }

    /**
     * ZABRANA BRISANJA: Niko ne može obrisati Senada.
     */
    public function delete(User $user, User $model): bool
    {
        if ($model->email === 'senad.okt97@gmail.com') {
            return false;
        }

        return $user->hasRole('admin');
    }

    public function restore(User $user, User $model): bool
    {
        return $user->hasRole('admin');
    }

    public function forceDelete(User $user, User $model): bool
    {
        if ($model->email === 'senad.okt97@gmail.com') {
            return false;
        }

        return $user->hasRole('admin');
    }
}