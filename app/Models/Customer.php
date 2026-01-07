<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
    ];

    /**
     * Relationship: A customer can have many orders.
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Relationship: A customer might belong to a registered User.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
