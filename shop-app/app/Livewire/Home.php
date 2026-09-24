<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\Product;
use App\Services\Cart;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.storefront')]
class Home extends Component
{
    public function addToCart(int $productId, Cart $cart): void
    {
        $cart->add($productId);
        $this->dispatch('cart-updated');
    }

    public function render()
    {
        return view('livewire.home', [
            'categories' => Category::query()->where('is_active', true)->orderBy('name')->limit(8)->get(),
            'featured' => Product::query()->where('is_active', true)->orderByDesc('synced_at')->limit(6)->get(),
        ]);
    }
}
