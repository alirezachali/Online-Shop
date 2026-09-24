<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\Product;
use App\Services\Cart;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.storefront')]
class Storefront extends Component
{
    public ?int $categoryId = null;

    public string $search = '';

    public function filterCategory(?int $id): void
    {
        $this->categoryId = $id;
    }

    public function addToCart(int $productId, Cart $cart): void
    {
        $cart->add($productId);
        $this->dispatch('cart-updated');
    }

    #[Computed]
    public function categories()
    {
        return Category::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->get();
    }

    #[Computed]
    public function products()
    {
        return Product::query()
            ->where('is_active', true)
            ->when($this->categoryId, fn ($q) => $q->where('category_id', $this->categoryId))
            ->when($this->search !== '', fn ($q) => $q->where('name', 'like', '%'.$this->search.'%'))
            ->orderBy('name')
            ->get();
    }

    public function render()
    {
        return view('livewire.storefront');
    }
}
