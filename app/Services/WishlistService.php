<?php

namespace App\Services;

use App\Models\Perfume;
use App\Models\WishlistItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Str;

class WishlistService
{
    public const COOKIE = 'sorenza_wishlist';
    public const COOKIE_TTL_MIN = 60 * 24 * 365; // 1 year

    /** Get or create the guest token, ensures Cookie::queue for outgoing response. */
    public static function token(): string
    {
        $token = request()->cookie(self::COOKIE);
        if (! $token) {
            $token = (string) Str::uuid();
            Cookie::queue(Cookie::make(self::COOKIE, $token, self::COOKIE_TTL_MIN));
        }
        return $token;
    }

    protected static function scope()
    {
        $user = Auth::user();
        if ($user) {
            return WishlistItem::where('user_id', $user->id);
        }
        return WishlistItem::where('token', self::token())->whereNull('user_id');
    }

    public static function ids(): array
    {
        return static::scope()->pluck('perfume_id')->all();
    }

    public static function count(): int
    {
        return static::scope()->count();
    }

    public static function has(int $perfumeId): bool
    {
        return static::scope()->where('perfume_id', $perfumeId)->exists();
    }

    /** Returns true if item was added, false if it was removed. */
    public static function toggle(int $perfumeId): bool
    {
        $user = Auth::user();
        $token = $user ? null : self::token();

        $existing = WishlistItem::query()
            ->when($user, fn ($q) => $q->where('user_id', $user->id))
            ->when(! $user, fn ($q) => $q->where('token', $token)->whereNull('user_id'))
            ->where('perfume_id', $perfumeId)
            ->first();

        if ($existing) {
            $existing->delete();
            return false;
        }

        WishlistItem::create([
            'user_id'    => $user?->id,
            'token'      => $token,
            'perfume_id' => $perfumeId,
        ]);
        return true;
    }

    public static function remove(int $perfumeId): void
    {
        static::scope()->where('perfume_id', $perfumeId)->delete();
    }

    /** Returns Perfume models currently in the wishlist. */
    public static function perfumes()
    {
        $ids = static::ids();
        if (empty($ids)) return collect();
        return Perfume::whereIn('id', $ids)->get();
    }

    /** Merge a guest wishlist into a freshly-authenticated user. Call after login. */
    public static function mergeTokenIntoUser(int $userId, ?string $token = null): void
    {
        $token = $token ?: request()->cookie(self::COOKIE);
        if (! $token) return;

        $guestItems = WishlistItem::where('token', $token)->whereNull('user_id')->pluck('perfume_id')->all();
        foreach ($guestItems as $perfumeId) {
            WishlistItem::firstOrCreate(
                ['user_id' => $userId, 'perfume_id' => $perfumeId],
                ['token' => null]
            );
        }
        WishlistItem::where('token', $token)->whereNull('user_id')->delete();
    }
}
