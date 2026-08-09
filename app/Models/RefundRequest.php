<?php

namespace App\Models;

use App\Enums\RefundStatus;
use Illuminate\Database\Eloquent\Model;

class RefundRequest extends Model
{
    protected $fillable = [
        'order_id',
        'user_id',
        'customer_name',
        'customer_email',
        'customer_phone',
        'reason',
        'status',
        'admin_response',
        'resolved_by_user_id',
        'resolved_at',
    ];

    protected $casts = [
        'status'      => RefundStatus::class,
        'resolved_at' => 'datetime',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function seller()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function resolver()
    {
        return $this->belongsTo(User::class, 'resolved_by_user_id');
    }
}
