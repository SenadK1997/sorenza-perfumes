<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SoldPerfume extends Model
{
    protected $fillable = [
        'user_id',
        'perfume_id',
        'quantity',
        'base_price',
        'is_manual',
        'cancelled',
        'cancellation_reason',
    ];

    public function perfume()
    {
        return $this->belongsTo(Perfume::class);
    }
}
