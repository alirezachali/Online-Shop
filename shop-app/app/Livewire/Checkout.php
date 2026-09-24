<?php

namespace App\Livewire;

use App\Services\Cart;
use App\Services\PlaceOrderService;
use App\Support\Storefront as Brand;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.storefront')]
class Checkout extends Component
{
    public string $name = '';

    public string $phone = '';

    public string $address = '';

    public ?int $orderId = null;

    public function mount(): void
    {
        $user = auth()->user();
        if ($user) {
            $this->name = (string) $user->name;
            $this->phone = (string) ($user->phone ?? '');
            $this->address = (string) ($user->address ?? '');
        }
    }

    public function place(Cart $cart, PlaceOrderService $orders): void
    {
        $this->validate([
            'name' => ['required', 'string', 'max:120'],
            'phone' => ['required', 'string', 'max:32'],
            'address' => ['required', 'string', 'max:500'],
        ]);

        $items = $cart->detailed();
        if ($items->isEmpty()) {
            $this->addError('address', 'سبد خرید خالی است.');

            return;
        }

        $order = $orders->place(
            [
                'name' => $this->name,
                'phone' => $this->phone,
                'address' => $this->address,
                'city' => Brand::city(),
            ],
            $items->map(fn ($line) => [
                'product_id' => $line->product->id,
                'quantity' => $line->quantity,
            ])->all(),
        );

        $cart->clear();
        $this->orderId = $order->id;
    }

    public function render(Cart $cart)
    {
        return view('livewire.checkout', [
            'lines' => $cart->detailed(),
            'total' => $cart->total(),
        ]);
    }
}
