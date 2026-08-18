<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class BlockedEmail extends Model
{
    protected $fillable = ['email', 'reason', 'blocked_at'];

    protected $casts = [
        'blocked_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::saving(function (self $r) {
            $r->email = strtolower(trim((string) $r->email));
        });
        static::saved(fn () => Cache::forget('blocked_emails.set'));
        static::deleted(fn () => Cache::forget('blocked_emails.set'));
    }

    public static function isBlocked(?string $email): bool
    {
        if (!$email) return false;
        $email = strtolower(trim($email));

        $set = Cache::remember('blocked_emails.set', 300, function () {
            return array_flip(static::query()->pluck('email')->all());
        });

        return isset($set[$email]);
    }

    /**
     * Add an email to the blocklist (idempotent).
     */
    public static function add(string $email, ?string $reason = null): self
    {
        $email = strtolower(trim($email));
        return static::updateOrCreate(
            ['email' => $email],
            ['reason' => $reason, 'blocked_at' => now()]
        );
    }
}
