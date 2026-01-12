<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\SoldPerfume;

class Customer extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'full_name',
        'phone',
        'address_line_1',
        'address_line_2',
        'city',
        'zipcode',
        'email',
        'user_id',
        'canton',
        'interests',
        'suggestions',
    ];

    protected $casts = [
    'interests' => 'array', // Crucial for storing multiple items
    ];

    /**
     * Relationship: A customer can have many orders.
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }
    public function seller()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relationship: A customer might belong to a registered User.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function soldPerfumes()
    {
        return $this->hasMany(SoldPerfume::class, 'customer_id');
    }

    // Dodajemo i pomoćnu relaciju za validne (neotkazane) prodaje
    public function validSales()
    {
        return $this->hasMany(SoldPerfume::class, 'customer_id')
            ->where('cancelled', false);
    }
}
