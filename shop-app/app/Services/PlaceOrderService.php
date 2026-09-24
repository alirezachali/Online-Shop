<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OutboxMessage;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use InvalidArgumentException;

class PlaceOrderService
{
    /**
     * Persist the order locally and enqueue an outbox event in the same transaction.
     *
     * @param  array<int, array{product_id: int, quantity: int}>  $items
     */
    public function place(array $customer, array $items, ?string $idempotencyKey = null): Order
    {
        if ($items === []) {
            throw new InvalidArgumentException('Order must have at least one item.');
        }

        return DB::transaction(function () use ($customer, $items, $idempotencyKey): Order {
            $key = $idempotencyKey ?: (string) Str::uuid();

            $existing = Order::query()->where('idempotency_key', $key)->first();
            if ($existing) {
                return $existing;
            }

            $order = Order::query()->create([
                'user_id' => $customer['user_id'] ?? auth()->id(),
                'idempotency_key' => $key,
                'status' => 'pending',
                'customer_name' => $customer['name'] ?? null,
                'customer_phone' => $customer['phone'] ?? null,
                'city' => $customer['city'] ?? config('integration.city'),
                'address' => $customer['address'] ?? null,
                'total' => 0,
            ]);

            $total = 0;

            foreach ($items as $line) {
                /** @var Product $product */
                $product = Product::query()
                    ->where('is_active', true)
                    ->findOrFail($line['product_id']);

                $qty = (int) $line['quantity'];
                if ($qty < 1) {
                    throw new InvalidArgumentException('Quantity must be at least 1.');
                }

                $unitPrice = (float) $product->sell_price;
                $total += $unitPrice * $qty;

                $order->items()->create([
                    'product_id' => $product->id,
                    'source_product_id' => $product->source_id,
                    'quantity' => $qty,
                    'unit_price' => $unitPrice,
                ]);
            }

            $order->update(['total' => $total]);
            $order->load('items');

            OutboxMessage::query()->create([
                'type' => 'order.placed',
                'idempotency_key' => $order->idempotency_key,
                'payload' => [
                    'order_id' => $order->id,
                    'idempotency_key' => $order->idempotency_key,
                    'customer' => [
                        'name' => $order->customer_name,
                        'phone' => $order->customer_phone,
                        'city' => $order->city,
                        'address' => $order->address,
                    ],
                    'total' => (float) $order->total,
                    'items' => $order->items->map(fn ($item) => [
                        'source_product_id' => $item->source_product_id,
                        'quantity' => $item->quantity,
                        'unit_price' => (float) $item->unit_price,
                    ])->all(),
                ],
                'status' => 'pending',
                'available_at' => now(),
            ]);

            return $order;
        });
    }
}
