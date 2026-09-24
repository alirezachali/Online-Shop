<?php

namespace App\Support;

use App\Models\ShopSetting;

class Storefront
{
    public static function name(): string
    {
        return (string) ShopSetting::get('store_name', config('app.name', 'فروشگاه'));
    }

    public static function phone(): ?string
    {
        return ShopSetting::get('phone') ?: ShopSetting::get('mobile');
    }

    public static function address(): ?string
    {
        return ShopSetting::get('address');
    }

    public static function currency(): string
    {
        return (string) ShopSetting::get('currency', 'تومان');
    }

    public static function footer(): string
    {
        return (string) ShopSetting::get('receipt_footer', 'از خرید شما سپاسگزاریم');
    }

    public static function city(): string
    {
        return (string) (ShopSetting::get('city') ?: config('integration.city') ?: '');
    }

    public static function logoUrl(): ?string
    {
        $path = ShopSetting::get('store_logo');
        if (! $path) {
            return null;
        }

        if (str_starts_with((string) $path, 'http://') || str_starts_with((string) $path, 'https://')) {
            return $path;
        }

        return asset('storage/'.$path);
    }
}
