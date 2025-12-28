<?php

namespace App\Models;

use App\Enums\DebtStatus;
use Illuminate\Database\Eloquent\Model;

class Debt extends Model
{
    protected $fillable = [
        'user_id',
        'amount',
        'status',
    ];

    protected $casts = [
        'status' => DebtStatus::class,
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeUnpaid($query)
    {
        return $query->where('status', DebtStatus::UNPAID);
    }

    public function markAsCompleted(): void
    {
        $this->update(['status' => DebtStatus::COMPLETED]);
    }
}
