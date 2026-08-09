<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WishlistItem extends Model
{
    protected $fillable = ['token', 'user_id', 'perfume_id'];

    public function perfume()
    {
        return $this->belongsTo(Perfume::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
