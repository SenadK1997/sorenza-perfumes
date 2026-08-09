<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExtraIncome extends Model
{
    protected $fillable = ['description', 'amount', 'income_date', 'user_id'];

    protected $casts = [
        'amount'      => 'decimal:2',
        'income_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
