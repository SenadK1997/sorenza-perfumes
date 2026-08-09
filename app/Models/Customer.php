<?php

namespace App\Models;

use App\Support\PhoneNumber;
use Illuminate\Database\Eloquent\Casts\Attribute;
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

    /** Always store phone in canonical +387… form. */
    protected function phone(): Attribute
    {
        return Attribute::make(
            set: fn ($value) => PhoneNumber::normalize($value),
        );
    }

    /** Formatted phone for display; use {{ $customer->pretty_phone }} */
    public function getPrettyPhoneAttribute(): ?string
    {
        return PhoneNumber::pretty($this->phone);
    }

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
