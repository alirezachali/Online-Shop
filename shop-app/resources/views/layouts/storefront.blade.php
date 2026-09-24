<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? \App\Support\Storefront::name() }}</title>
    @if (\App\Support\Storefront::logoUrl())
        <link rel="icon" href="{{ \App\Support\Storefront::logoUrl() }}">
    @endif
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-cream text-ink antialiased">
    <div class="pattern-veil"></div>
    <header class="relative z-20 border-b border-olive/15 bg-cream/85 backdrop-blur">
        <div class="mx-auto flex max-w-6xl flex-wrap items-center justify-between gap-4 px-4 py-4">
            <a href="{{ route('home') }}" class="flex items-center gap-3">
                @if (\App\Support\Storefront::logoUrl())
                    <img src="{{ \App\Support\Storefront::logoUrl() }}" alt="" class="h-12 w-12 rounded-2xl object-cover shadow-sm ring-1 ring-olive/20">
                @else
                    <span class="grid h-12 w-12 place-items-center rounded-2xl bg-olive text-lg font-bold text-cream">{{ mb_substr(\App\Support\Storefront::name(), 0, 1) }}</span>
                @endif
                <div>
                    <p class="font-display text-lg font-semibold text-olive">{{ \App\Support\Storefront::name() }}</p>
                    @if (\App\Support\Storefront::city())
                        <p class="text-xs text-ink/60">ارسال فقط در {{ \App\Support\Storefront::city() }}</p>
                    @endif
                </div>
            </a>
            <nav class="flex flex-wrap items-center gap-2 text-sm">
                <a href="{{ route('home') }}" class="rounded-full px-3 py-1.5 {{ request()->routeIs('home') ? 'bg-olive text-cream' : 'text-olive' }}">خانه</a>
                <a href="{{ route('shop') }}" class="rounded-full px-3 py-1.5 {{ request()->routeIs('shop') ? 'bg-olive text-cream' : 'text-olive' }}">ویترین</a>
                @auth
                    <a href="{{ route('account') }}" class="rounded-full px-3 py-1.5 {{ request()->routeIs('account') ? 'bg-olive text-cream' : 'text-olive' }}">حساب من</a>
                @else
                    <a href="{{ route('login') }}" class="rounded-full px-3 py-1.5 text-olive">ورود</a>
                    <a href="{{ route('register') }}" class="rounded-full bg-saffron px-3 py-1.5 text-ink">ثبت‌نام</a>
                @endauth
                <livewire:cart-drawer />
            </nav>
        </div>
    </header>

    <main class="relative z-10 mx-auto max-w-6xl px-4 py-8">
        {{ $slot }}
    </main>

    <footer class="relative z-10 mt-16 border-t border-olive/15 bg-olive text-cream">
        <div class="mx-auto grid max-w-6xl gap-6 px-4 py-10 sm:grid-cols-3">
            <div>
                <p class="font-display text-lg">{{ \App\Support\Storefront::name() }}</p>
                <p class="mt-2 text-sm text-cream/70">{{ \App\Support\Storefront::footer() }}</p>
            </div>
            <div class="text-sm text-cream/80">
                @if (\App\Support\Storefront::phone())
                    <p>{{ \App\Support\Storefront::phone() }}</p>
                @endif
                @if (\App\Support\Storefront::address())
                    <p class="mt-1">{{ \App\Support\Storefront::address() }}</p>
                @endif
            </div>
            <div class="text-sm">
                <a class="block text-cream/80" href="{{ route('shop') }}">ویترین</a>
                <a class="mt-1 block text-cream/80" href="{{ route('login') }}">ورود مشتری</a>
            </div>
        </div>
    </footer>
</body>
</html>
