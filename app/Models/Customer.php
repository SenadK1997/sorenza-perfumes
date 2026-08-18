<?php

namespace App\Models;

use App\Support\PhoneNumber;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\SoldPerfume;

class Customer extends Authenticatable
{
    use HasFactory, Notifiable;

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
        'password',
        'user_id',
        'canton',
        'interests',
        'suggestions',
        'last_login_at',
        'is_blocked',
        'blocked_reason',
        'blocked_at',
        'loyalty_adjustment',
        'loyalty_adjustment_note',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'interests' => 'array', // Crucial for storing multiple items
        'password' => 'hashed',
        'last_login_at' => 'datetime',
        'is_blocked' => 'boolean',
        'blocked_at' => 'datetime',
        'loyalty_adjustment' => 'decimal:2',
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

    /**
     * Storefront orders linked by email (orders table has no customer_id).
     */
    public function ordersByEmail()
    {
        return Order::query()->where('email', $this->email);
    }

    /**
     * Completed storefront orders for this customer — the source of truth
     * for "actually bought" (money paid / delivered), not just placed.
     */
    public function completedOrders()
    {
        return $this->ordersByEmail()->where('status', 'completed');
    }

    /**
     * Eligible for magic-link / dashboard access.
     * True when this customer has actually bought something —
     * either a COMPLETED storefront order OR a non-cancelled manual seller sale.
     * (Sellers only log a sold_perfume once the sale is done, so cancelled=false there = completed.)
     */
    public function isActive(): bool
    {
        if (!$this->email) return false;
        return $this->completedOrders()->exists() || $this->validSales()->exists();
    }

    /**
     * Scope: customers who actually bought (see isActive()).
     */
    public function scopeActive($query)
    {
        return $query->where(function ($q) {
            $q->whereExists(function ($sub) {
                    $sub->select(\Illuminate\Support\Facades\DB::raw(1))
                        ->from('orders')
                        ->whereColumn('orders.email', 'customers.email')
                        ->where('orders.status', 'completed');
                })
                ->orWhereHas('validSales');
        });
    }

    /**
     * Scope: customers with at least one cancelled storefront order.
     */
    public function scopeWithCancellations($query)
    {
        return $query->whereExists(function ($sub) {
            $sub->select(\Illuminate\Support\Facades\DB::raw(1))
                ->from('orders')
                ->whereColumn('orders.email', 'customers.email')
                ->where('orders.status', 'cancelled');
        });
    }

    public function block(?string $reason = null): void
    {
        $this->update([
            'is_blocked'     => true,
            'blocked_reason' => $reason,
            'blocked_at'     => now(),
        ]);

        // Mirror to the standalone email blocklist so the block sticks even if the
        // customer row is later deleted, merged, or re-created with the same email.
        if ($this->email) {
            BlockedEmail::add($this->email, $reason);
        }
    }

    public function unblock(): void
    {
        $this->update([
            'is_blocked'     => false,
            'blocked_reason' => null,
            'blocked_at'     => null,
        ]);

        if ($this->email) {
            $email = strtolower(trim($this->email));
            BlockedEmail::where('email', $email)->get()->each->delete();
        }
    }
}
