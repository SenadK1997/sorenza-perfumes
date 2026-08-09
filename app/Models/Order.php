<?php

namespace App\Models;

use App\Enums\OrderStatus;
use App\Enums\Canton;
use App\Support\PhoneNumber;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Order extends Model
{
    protected $fillable = [
        'pretty_id',
        'subtotal',
        'shipping_fee',
        'amount',
        'coupon_code',
        'discount_amount',
        'full_name',
        'phone',
        'address_line_1',
        'address_line_2',
        'city',
        'zipcode',
        'email',
        'user_id',
        'canton',
        'status',
        'cancellation_reason',
    ];

    protected $casts = [
        'status' => OrderStatus::class,
        'canton' => Canton::class,
    ];

    protected function phone(): Attribute
    {
        return Attribute::make(
            set: fn ($value) => PhoneNumber::normalize($value),
        );
    }

    public function getPrettyPhoneAttribute(): ?string
    {
        return PhoneNumber::pretty($this->phone);
    }
    protected static function booted()
    {
        static::creating(function ($order) {
            $order->pretty_id = self::generateUniqueOrderNumber();
        });
    }

    public static function generateUniqueOrderNumber()
    {
        do {
            $code = 'SOR-' . strtoupper(Str::random(5));
        } while (self::where('pretty_id', $code)->exists());

        return $code;
    }

    // Relationships (optional)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
    public function perfumes()
    {
        return $this->belongsToMany(Perfume::class)
            ->withPivot(['quantity', 'price'])
            ->withTimestamps();
    }

    public function refundRequests()
    {
        return $this->hasMany(RefundRequest::class);
    }

    public function latestRefundRequest()
    {
        return $this->hasOne(RefundRequest::class)->latestOfMany();
    }
}