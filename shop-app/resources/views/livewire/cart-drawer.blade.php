<div>
    <button type="button" wire:click="toggle" class="relative rounded-2xl bg-saffron px-4 py-2 text-sm font-medium text-ink">
        سبد
        @if ($count)
            <span class="absolute -top-1 -left-1 grid h-5 min-w-5 place-items-center rounded-full bg-olive px-1 text-[11px] text-cream">{{ $count }}</span>
        @endif
    </button>

    @if ($open)
        <div class="fixed inset-0 z-40 bg-ink/40" wire:click="toggle"></div>
        <aside class="fixed inset-y-0 left-0 z-50 flex w-full max-w-md flex-col bg-cream shadow-2xl">
            <div class="flex items-center justify-between border-b border-olive/10 px-5 py-4">
                <h2 class="font-display text-lg text-olive">سبد خرید</h2>
                <button type="button" wire:click="toggle" class="text-sm text-ink/60">بستن</button>
            </div>
            <div class="flex-1 overflow-y-auto px-5 py-4">
                @forelse ($lines as $line)
                    <div class="mb-4 flex items-center justify-between gap-3 border-b border-olive/10 pb-4">
                        <div>
                            <p class="font-medium text-olive">{{ $line->product->name }}</p>
                            <p class="text-sm text-ink/60">{{ number_format($line->line_total) }} {{ \App\Support\Storefront::currency() }}</p>
                        </div>
                        <div class="flex items-center gap-2">
                            <button type="button" wire:click="decrement({{ $line->product->id }})" class="h-8 w-8 rounded-full bg-white ring-1 ring-olive/20">−</button>
                            <span>{{ $line->quantity }}</span>
                            <button type="button" wire:click="increment({{ $line->product->id }})" class="h-8 w-8 rounded-full bg-white ring-1 ring-olive/20">+</button>
                        </div>
                    </div>
                @empty
                    <p class="text-ink/50">سبد خالی است.</p>
                @endforelse
            </div>
            <div class="border-t border-olive/10 p-5">
                <p class="mb-3 text-lg font-semibold">جمع: {{ number_format($total) }} {{ \App\Support\Storefront::currency() }}</p>
                <a href="{{ route('checkout') }}" class="block rounded-2xl bg-olive py-3 text-center text-cream">ادامه سفارش</a>
            </div>
        </aside>
    @endif
</div>
