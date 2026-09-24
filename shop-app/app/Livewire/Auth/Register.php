<?php

namespace App\Livewire\Auth;

use App\Models\User;
use App\Support\Storefront as Brand;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.storefront')]
class Register extends Component
{
    public string $name = '';

    public string $phone = '';

    public string $address = '';

    public string $password = '';

    public string $password_confirmation = '';

    public function register(): void
    {
        $data = $this->validate([
            'name' => ['required', 'string', 'max:120'],
            'phone' => ['required', 'regex:/^09\d{9}$/', 'unique:users,phone'],
            'address' => ['required', 'string', 'max:500'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'phone.regex' => 'شماره موبایل باید ۱۱ رقم و با ۰۹ شروع شود.',
            'phone.unique' => 'این شماره قبلاً ثبت شده است.',
        ]);

        $user = User::query()->create([
            'name' => $data['name'],
            'phone' => $data['phone'],
            'email' => $data['phone'].'@customers.local',
            'city' => Brand::city(),
            'address' => $data['address'],
            'password' => $data['password'],
        ]);

        event(new Registered($user));
        Auth::login($user);

        $this->redirectRoute('home', navigate: true);
    }

    public function render()
    {
        return view('livewire.auth.register');
    }
}
