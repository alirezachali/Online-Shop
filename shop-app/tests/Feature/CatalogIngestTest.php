<?php

use App\Models\Product;

test('catalog ingest requires a token', function () {
    $this->postJson('/api/catalog/ingest', [
        'products' => [
            ['source_id' => 1, 'name' => 'A', 'sell_price' => 10],
        ],
    ])->assertUnauthorized();
});

test('catalog ingest upserts products from the pos', function () {
    config(['integration.token' => 'secret-token']);

    $this->withToken('secret-token')
        ->postJson('/api/catalog/ingest', [
            'categories' => [
                ['source_id' => 4, 'name' => 'نوشیدنی'],
            ],
            'products' => [
                [
                    'source_id' => 11,
                    'name' => 'آب',
                    'barcode' => '123',
                    'category_source_id' => 4,
                    'sell_price' => 15000,
                    'stock' => 8,
                ],
            ],
        ])
        ->assertOk()
        ->assertJson(['ok' => true]);

    expect(Product::query()->where('source_id', 11)->first())
        ->name->toBe('آب')
        ->sell_price->toBe('15000.00');
});

test('catalog ingest stores public shop settings from pos', function () {
    config(['integration.token' => 'secret-token']);

    $this->withToken('secret-token')
        ->postJson('/api/catalog/ingest', [
            'settings' => [
                'store_name' => 'سوپرمارکت نمونه',
                'phone' => '02112345678',
                'currency' => 'تومان',
                'city' => 'تهران',
            ],
        ])
        ->assertOk();

    expect(\App\Support\Storefront::name())->toBe('سوپرمارکت نمونه');
    expect(\App\Support\Storefront::currency())->toBe('تومان');
});
