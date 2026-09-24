<?php

use App\Models\OutboxMessage;
use App\Models\Product;
use App\Services\OutboxDispatcher;
use App\Services\PlaceOrderService;
use Illuminate\Support\Facades\Http;

test('placing an order writes an outbox row in the same transaction', function () {
    $product = Product::query()->create([
        'source_id' => 99,
        'name' => 'نان',
        'sell_price' => 20000,
        'stock' => 10,
        'is_active' => true,
    ]);

    $order = app(PlaceOrderService::class)->place(
        ['name' => 'علی', 'phone' => '09120000000', 'address' => 'خیابان ۱'],
        [['product_id' => $product->id, 'quantity' => 2]],
        '11111111-1111-1111-1111-111111111111',
    );

    expect($order->total)->toBe('40000.00');
    expect(OutboxMessage::query()->where('idempotency_key', $order->idempotency_key)->exists())->toBeTrue();
});

test('outbox stays pending when the broker is unreachable', function () {
    Http::fake([
        '*' => Http::response('down', 503),
    ]);

    OutboxMessage::query()->create([
        'type' => 'order.placed',
        'idempotency_key' => 'k-1',
        'payload' => ['order_id' => 1],
        'status' => 'pending',
        'available_at' => now(),
    ]);

    $sent = app(OutboxDispatcher::class)->dispatchPending();

    expect($sent)->toBe(0);
    expect(OutboxMessage::query()->first())
        ->status->toBe('pending')
        ->attempts->toBe(1);
});

test('outbox is marked sent when the broker accepts the event', function () {
    Http::fake([
        '*' => Http::response(['ok' => true], 200),
    ]);

    OutboxMessage::query()->create([
        'type' => 'order.placed',
        'idempotency_key' => 'k-2',
        'payload' => ['order_id' => 2],
        'status' => 'pending',
        'available_at' => now(),
    ]);

    $sent = app(OutboxDispatcher::class)->dispatchPending();

    expect($sent)->toBe(1);
    expect(OutboxMessage::query()->first()->status)->toBe('sent');
});
