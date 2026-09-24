<?php

use App\Livewire\Account;
use App\Livewire\Auth\Login;
use App\Livewire\Auth\Register;
use App\Livewire\Checkout;
use App\Livewire\Home;
use App\Livewire\Storefront;
use Illuminate\Support\Facades\Route;

Route::get('/', Home::class)->name('home');
Route::get('/shop', Storefront::class)->name('shop');

Route::middleware('guest')->group(function () {
    Route::get('/login', Login::class)->name('login');
    Route::get('/register', Register::class)->name('register');
});

Route::middleware('auth')->group(function () {
    Route::get('/account', Account::class)->name('account');
    Route::get('/checkout', Checkout::class)->name('checkout');
});
