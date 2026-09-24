<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Product;
use App\Models\ShopSetting;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class CatalogSyncService
{
    /**
     * Upsert catalog rows published by the POS (master). Shop never writes master product data.
     *
     * @param  array<int, array<string, mixed>>  $categories
     * @param  array<int, array<string, mixed>>  $products
     * @param  array<string, mixed>  $settings
     */
    public function ingest(array $categories, array $products, array $settings = []): void
    {
        DB::transaction(function () use ($categories, $products, $settings): void {
            foreach ($categories as $row) {
                Category::query()->updateOrCreate(
                    ['source_id' => $row['source_id']],
                    [
                        'name' => $row['name'],
                        'is_active' => $row['is_active'] ?? true,
                    ],
                );
            }

            foreach ($products as $row) {
                $categoryId = null;
                if (! empty($row['category_source_id'])) {
                    $categoryId = Category::query()
                        ->where('source_id', $row['category_source_id'])
                        ->value('id');
                }

                Product::query()->updateOrCreate(
                    ['source_id' => $row['source_id']],
                    [
                        'barcode' => $row['barcode'] ?? null,
                        'name' => $row['name'],
                        'category_id' => $categoryId,
                        'sell_price' => $row['sell_price'],
                        'stock' => $row['stock'] ?? 0,
                        'unit' => $row['unit'] ?? 'عدد',
                        'is_active' => $row['is_active'] ?? true,
                        'synced_at' => now(),
                    ],
                );
            }

            $this->ingestSettings($settings);
        });
    }

    /**
     * Public storefront fields only — never sync POS internals (backup path, invoice prefixes, …).
     *
     * @param  array<string, mixed>  $settings
     */
    public function ingestSettings(array $settings): void
    {
        $allowed = [
            'store_name',
            'phone',
            'mobile',
            'address',
            'website',
            'currency',
            'receipt_footer',
            'tax_rate',
            'city',
        ];

        foreach ($allowed as $key) {
            if (array_key_exists($key, $settings) && $settings[$key] !== null) {
                ShopSetting::put($key, (string) $settings[$key]);
            }
        }

        if (! empty($settings['store_logo_base64'])) {
            $this->storeLogoFromBase64((string) $settings['store_logo_base64']);
        } elseif (! empty($settings['store_logo_url'])) {
            ShopSetting::put('store_logo', (string) $settings['store_logo_url']);
        }
    }

    private function storeLogoFromBase64(string $data): void
    {
        if (preg_match('/^data:image\/(\w+);base64,(.+)$/', $data, $m) !== 1) {
            return;
        }

        $binary = base64_decode($m[2], true);
        if ($binary === false) {
            return;
        }

        $ext = strtolower($m[1]) === 'jpeg' ? 'jpg' : strtolower($m[1]);
        $path = 'branding/logo.'.$ext;
        Storage::disk('public')->put($path, $binary);
        ShopSetting::put('store_logo', $path);
    }
}
