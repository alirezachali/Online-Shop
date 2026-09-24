<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class CatalogSyncService
{
    /**
     * Upsert catalog rows published by the POS (master). Shop never writes master product data.
     *
     * @param  array<int, array<string, mixed>>  $categories
     * @param  array<int, array<string, mixed>>  $products
     */
    public function ingest(array $categories, array $products): void
    {
        DB::transaction(function () use ($categories, $products): void {
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
        });
    }
}
