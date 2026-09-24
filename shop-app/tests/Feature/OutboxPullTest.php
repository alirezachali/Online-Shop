<?php

use App\Models\OutboxMessage;

test('pos can pull and ack pending outbox messages', function () {
    config(['integration.token' => 'secret-token']);

    OutboxMessage::query()->create([
        'type' => 'order.placed',
        'idempotency_key' => 'abc',
        'payload' => ['order_id' => 1],
        'status' => 'pending',
        'available_at' => now(),
    ]);

    $this->withToken('secret-token')
        ->getJson('/api/outbox/pending')
        ->assertOk()
        ->assertJsonPath('data.0.idempotency_key', 'abc');

    $this->withToken('secret-token')
        ->postJson('/api/outbox/ack', ['idempotency_keys' => ['abc']])
        ->assertOk()
        ->assertJson(['acked' => 1]);

    expect(OutboxMessage::query()->first()->status)->toBe('sent');
});
