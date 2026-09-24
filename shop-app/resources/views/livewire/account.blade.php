<div class="grid gap-8 lg:grid-cols-5">
    <div class="lg:col-span-3 rounded-[2rem] bg-white p-8 ring-1 ring-olive/10">
        <h1 class="font-display text-2xl text-olive">حساب من</h1>
        @if (session('status'))
            <p class="mt-3 text-sm text-olive">{{ session('status') }}</p>
        @endif
        <form wire:submit="save" class="mt-6 space-y-4">
            <p class="text-sm text-ink/60">موبایل: {{ auth()->user()->phone }}</p>
            <label class="block text-sm">نام
                <input wire:model="name" class="mt-1 w-full rounded-2xl bg-cream px-4 py-3 ring-1 ring-olive/15">
                @error('name') <span class="text-terracotta">{{ $message }}</span> @enderror
            </label>
            <label class="block text-sm">آدرس
                <textarea wire:model="address" rows="3" class="mt-1 w-full rounded-2xl bg-cream px-4 py-3 ring-1 ring-olive/15"></textarea>
                @error('address') <span class="text-terracotta">{{ $message }}</span> @enderror
            </label>
            <div class="flex gap-3">
                <button type="submit" class="rounded-2xl bg-olive px-5 py-2.5 text-cream">ذخیره</button>
                <button type="button" wire:click="logout" class="rounded-2xl px-5 py-2.5 ring-1 ring-olive/20">خروج</button>
            </div>
        </form>
    </div>
    <aside class="lg:col-span-2 rounded-[2rem] bg-olive p-6 text-cream">
        <h2 class="font-display text-lg">سفارش‌های اخیر</h2>
        @forelse ($orders as $order)
            <div class="mt-4 border-t border-cream/20 pt-3 text-sm">
                <p>شماره {{ $order->id }} · {{ number_format((float) $order->total) }} {{ \App\Support\Storefront::currency() }}</p>
                <p class="text-cream/70">{{ $order->status }}</p>
            </div>
        @empty
            <p class="mt-4 text-cream/70">هنوز سفارشی ندارید.</p>
        @endforelse
    </aside>
</div>
