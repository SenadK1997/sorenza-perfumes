<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CustomerMessageThread extends Model
{
    protected $fillable = [
        'customer_id',
        'last_message_at',
        'customer_unread_count',
        'admin_unread_count',
    ];

    protected $casts = [
        'last_message_at' => 'datetime',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function messages(): HasMany
    {
        return $this->hasMany(CustomerMessage::class, 'thread_id');
    }

    public static function forCustomer(Customer $customer): self
    {
        return static::firstOrCreate(['customer_id' => $customer->id]);
    }
}
