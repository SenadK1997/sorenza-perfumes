<?php

namespace App\Models;

use App\Enums\PerfumeRequestStatus;
use Illuminate\Database\Eloquent\Model;

class PerfumeRequest extends Model
{
    protected $fillable = [
        'user_id',
        'perfume_id',
        'quantity',
        'note',
        'status',
        'admin_note',
        'approved_by',
        'resolved_at',
    ];

    protected $casts = [
        'status'      => PerfumeRequestStatus::class,
        'resolved_at' => 'datetime',
    ];

    public function seller()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function perfume()
    {
        return $this->belongsTo(Perfume::class);
    }
}
