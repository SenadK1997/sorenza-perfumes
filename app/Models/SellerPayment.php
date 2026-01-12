<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;

class SellerPayment extends Model
{
    protected $fillable = ['user_id', 'amount', 'status', 'complaint'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
