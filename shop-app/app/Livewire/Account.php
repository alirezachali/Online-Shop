<?php

namespace App\Livewire;

use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.storefront')]
class Account extends Component
{
    public string $name = '';

    public string $address = '';

    public function mount(): void
    {
        $user = Auth::user();
        $this->name = (string) $user->name;
        $this->address = (string) ($user->address ?? '');
    }

    public function save(): void
    {
        $data = $this->validate([
            'name' => ['required', 'string', 'max:120'],
            'address' => ['required', 'string', 'max:500'],
        ]);

        Auth::user()->update($data);
        session()->flash('status', 'پروفایل ذخیره شد.');
    }

    public function logout(): void
    {
        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();

        $this->redirectRoute('home', navigate: true);
    }

    public function render()
    {
        return view('livewire.account', [
            'orders' => Order::query()
                ->where('user_id', Auth::id())
                ->latest()
                ->limit(10)
                ->get(),
        ]);
    }
}
