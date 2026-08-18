<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CustomerMessage extends Model
{
    protected $fillable = [
        'thread_id',
        'direction',
        'author_user_id',
        'body',
        'read_at',
        'is_broadcast',
    ];

    protected $casts = [
        'read_at' => 'datetime',
        'is_broadcast' => 'boolean',
    ];

    public function thread(): BelongsTo
    {
        return $this->belongsTo(CustomerMessageThread::class, 'thread_id');
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_user_id');
    }

    public function isFromAdmin(): bool
    {
        return $this->direction === 'admin';
    }
}
