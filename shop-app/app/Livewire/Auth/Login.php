<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.storefront')]
class Login extends Component
{
    public string $phone = '';

    public string $password = '';

    public bool $remember = false;

    public function login(): void
    {
        $this->validate([
            'phone' => ['required', 'regex:/^09\d{9}$/'],
            'password' => ['required', 'string'],
        ], [
            'phone.regex' => 'شماره موبایل باید ۱۱ رقم و با ۰۹ شروع شود.',
        ]);

        $key = Str::transliterate(Str::lower('login|'.$this->phone.'|'.request()->ip()));

        if (RateLimiter::tooManyAttempts($key, 5)) {
            $this->addError('phone', 'دفعات تلاش زیاد بود. کمی بعد دوباره امتحان کنید.');

            return;
        }

        if (! Auth::attempt(['phone' => $this->phone, 'password' => $this->password], $this->remember)) {
            RateLimiter::hit($key, 60);
            $this->addError('phone', 'موبایل یا رمز عبور نادرست است.');

            return;
        }

        RateLimiter::clear($key);
        session()->regenerate();

        $this->redirectIntended(route('home'), navigate: true);
    }

    public function render()
    {
        return view('livewire.auth.login');
    }
}
