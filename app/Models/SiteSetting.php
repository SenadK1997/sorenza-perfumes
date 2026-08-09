<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class SiteSetting extends Model
{
    protected $fillable = ['key', 'value'];

    protected static function booted(): void
    {
        static::saved(fn () => Cache::forget('site_settings.all'));
        static::deleted(fn () => Cache::forget('site_settings.all'));
    }

    public static function all_cached(): array
    {
        return Cache::rememberForever('site_settings.all', function () {
            return static::query()->pluck('value', 'key')->toArray();
        });
    }

    public static function get(string $key, mixed $default = null): mixed
    {
        return static::all_cached()[$key] ?? $default;
    }

    public static function set(string $key, mixed $value): void
    {
        static::updateOrCreate(['key' => $key], ['value' => (string) $value]);
    }

    public static function bool(string $key, bool $default = false): bool
    {
        $v = static::get($key);
        if ($v === null) return $default;
        return in_array(strtolower((string) $v), ['1', 'true', 'yes', 'on'], true);
    }

    public static function float(string $key, float $default = 0.0): float
    {
        $v = static::get($key);
        return $v === null ? $default : (float) $v;
    }

    public static function int(string $key, int $default = 0): int
    {
        $v = static::get($key);
        return $v === null ? $default : (int) $v;
    }
}
