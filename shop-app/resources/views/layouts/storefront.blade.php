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
    <header class="relative z-20 border-b border-olive/15 bg-cream/80 backdrop-blur">
        <div class="mx-auto flex max-w-6xl items-center justify-between gap-4 px-4 py-4">
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
            <livewire:cart-drawer />
        </div>
    </header>

    <main class="relative z-10 mx-auto max-w-6xl px-4 py-8">
        {{ $slot }}
    </main>

    <footer class="relative z-10 mt-16 border-t border-olive/15 bg-olive text-cream">
        <div class="mx-auto flex max-w-6xl flex-col gap-2 px-4 py-8 text-sm sm:flex-row sm:items-center sm:justify-between">
            <p>{{ \App\Support\Storefront::footer() }}</p>
            <p class="opacity-80">
                @if (\App\Support\Storefront::phone())
                    {{ \App\Support\Storefront::phone() }}
                @endif
                @if (\App\Support\Storefront::address())
                    · {{ \App\Support\Storefront::address() }}
                @endif
            </p>
        </div>
    </footer>
</body>
</html>
