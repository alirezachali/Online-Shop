<?php

use App\Livewire\Checkout;
use App\Livewire\Storefront;
use Illuminate\Support\Facades\Route;

Route::get('/', Storefront::class)->name('home');
Route::get('/checkout', Checkout::class)->name('checkout');
