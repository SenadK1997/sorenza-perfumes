<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Spatie\Permission\Traits\HasRoles;
use App\Models\Perfume;
use App\Models\SoldPerfume;
use App\Models\SellerPayment;
use App\Models\Customer;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'city',
        'canton',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the user's initials
     */
    public function initials(): string
    {
        return Str::of($this->name)
            ->explode(' ')
            ->take(2)
            ->map(fn ($word) => Str::substr($word, 0, 1))
            ->implode('');
    }

    public function soldPerfumes()
    {
        return $this->hasMany(SoldPerfume::class);
    }

    public function sellerPayments()
    {
        return $this->hasMany(SellerPayment::class);
    }

    public function perfumes()
    {
        return $this->belongsToMany(Perfume::class, 'perfume_seller')
            ->withPivot('stock')
            ->withTimestamps();
    }
    public function customers()
    {
        // Admini mogu videti sve kupce, a selleri samo svoje (opcionalno filtriranje)
        return $this->hasMany(Customer::class);
    }
    public function validSoldPerfumes()
    {
        return $this->hasMany(SoldPerfume::class)
            ->where('cancelled', false);
    }
}
