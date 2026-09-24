<?php

use App\Models\User;
use Livewire\Livewire;

test('homepage loads', function () {
    $this->get('/')->assertOk();
});

test('guest is redirected from checkout to login', function () {
    $this->get('/checkout')->assertRedirect(route('login'));
});

test('customer can register with mobile and login', function () {
    Livewire::test(\App\Livewire\Auth\Register::class)
        ->set('name', 'علی مشتری')
        ->set('phone', '09123456789')
        ->set('address', 'خیابان گل‌ها')
        ->set('password', 'password12')
        ->set('password_confirmation', 'password12')
        ->call('register')
        ->assertHasNoErrors()
        ->assertRedirect(route('home'));

    $this->assertAuthenticated();
    expect(User::query()->where('phone', '09123456789')->exists())->toBeTrue();

    auth()->logout();

    Livewire::test(\App\Livewire\Auth\Login::class)
        ->set('phone', '09123456789')
        ->set('password', 'password12')
        ->call('login')
        ->assertHasNoErrors();

    $this->assertAuthenticated();
});
