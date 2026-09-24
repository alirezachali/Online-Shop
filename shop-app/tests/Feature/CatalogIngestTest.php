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
