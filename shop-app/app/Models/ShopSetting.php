<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class ShopSetting extends Model
{
    protected $fillable = ['key', 'value'];

    public static function get(string $key, mixed $default = null): mixed
    {
        $all = static::allCached();

        return $all[$key] ?? $default;
    }

    /**
     * @return array<string, string|null>
     */
    public static function allCached(): array
    {
        return Cache::remember('shop_settings', 60, function () {
            return static::query()->pluck('value', 'key')->all();
        });
    }

    public static function put(string $key, ?string $value): void
    {
        static::query()->updateOrCreate(['key' => $key], ['value' => $value]);
        Cache::forget('shop_settings');
    }
}
