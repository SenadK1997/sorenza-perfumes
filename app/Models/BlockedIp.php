<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class BlockedIp extends Model
{
    protected $fillable = ['ip', 'reason', 'blocked_at'];

    protected $casts = [
        'blocked_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::saved(fn () => Cache::forget('blocked_ips.set'));
        static::deleted(fn () => Cache::forget('blocked_ips.set'));
    }

    public static function isBlocked(?string $ip): bool
    {
        if (!$ip) return false;

        $set = Cache::remember('blocked_ips.set', 300, function () {
            return array_flip(static::query()->pluck('ip')->all());
        });

        return isset($set[$ip]);
    }
}
