<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Session;

class Cart
{
    public const SESSION_KEY = 'storefront_cart';

    /**
     * @return array<int, int> productId => qty
     */
    public function lines(): array
    {
        return Session::get(self::SESSION_KEY, []);
    }

    public function add(int $productId, int $qty = 1): void
    {
        $cart = $this->lines();
        $cart[$productId] = ($cart[$productId] ?? 0) + max(1, $qty);
        Session::put(self::SESSION_KEY, $cart);
    }

    public function setQty(int $productId, int $qty): void
    {
        $cart = $this->lines();
        if ($qty < 1) {
            unset($cart[$productId]);
        } else {
            $cart[$productId] = $qty;
        }
        Session::put(self::SESSION_KEY, $cart);
    }

    public function clear(): void
    {
        Session::forget(self::SESSION_KEY);
    }

    public function count(): int
    {
        return array_sum($this->lines());
    }

    /**
     * @return Collection<int, object{product: Product, quantity: int, line_total: float}>
     */
    public function detailed(): Collection
    {
        $lines = $this->lines();
        if ($lines === []) {
            return collect();
        }

        $products = Product::query()
            ->whereIn('id', array_keys($lines))
            ->where('is_active', true)
            ->get()
            ->keyBy('id');

        return collect($lines)
            ->map(function (int $qty, int $id) use ($products) {
                $product = $products->get($id);
                if (! $product) {
                    return null;
                }

                return (object) [
                    'product' => $product,
                    'quantity' => $qty,
                    'line_total' => (float) $product->sell_price * $qty,
                ];
            })
            ->filter()
            ->values();
    }

    public function total(): float
    {
        return (float) $this->detailed()->sum('line_total');
    }
}
