<?php

namespace App\Livewire;

use App\Services\Cart;
use Livewire\Attributes\On;
use Livewire\Component;

class CartDrawer extends Component
{
    public bool $open = false;

    #[On('cart-updated')]
    public function refreshCart(): void
    {
        $this->open = true;
    }

    public function toggle(): void
    {
        $this->open = ! $this->open;
    }

    public function increment(int $productId, Cart $cart): void
    {
        $cart->add($productId, 1);
    }

    public function decrement(int $productId, Cart $cart): void
    {
        $qty = ($cart->lines()[$productId] ?? 1) - 1;
        $cart->setQty($productId, $qty);
    }

    public function render(Cart $cart)
    {
        return view('livewire.cart-drawer', [
            'count' => $cart->count(),
            'lines' => $cart->detailed(),
            'total' => $cart->total(),
        ]);
    }
}
