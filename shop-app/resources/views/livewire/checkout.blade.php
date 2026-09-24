<div class="grid gap-8 lg:grid-cols-5">
    <div class="lg:col-span-3">
        @if ($orderId)
            <div class="rounded-[2rem] bg-white p-8 ring-1 ring-olive/10">
                <p class="text-saffron">ثبت شد</p>
                <h1 class="mt-2 font-display text-2xl text-olive">سفارش شماره {{ $orderId }}</h1>
                <p class="mt-3 text-ink/70">سفارش در صف ارسال به سیستم مدیریت قرار گرفت. اگر فروشگاه خاموش باشد، به‌محض اتصال پردازش می‌شود.</p>
                <a href="{{ route('home') }}" class="mt-6 inline-block rounded-2xl bg-olive px-5 py-2 text-cream">بازگشت به ویترین</a>
            </div>
        @else
            <form wire:submit="place" class="rounded-[2rem] bg-white p-8 ring-1 ring-olive/10">
                <h1 class="font-display text-2xl text-olive">اطلاعات ارسال</h1>
                <div class="mt-6 space-y-4">
                    <label class="block text-sm">نام
                        <input wire:model="name" class="mt-1 w-full rounded-2xl border-0 bg-cream px-4 py-3 ring-1 ring-olive/15" required>
                    </label>
                    <label class="block text-sm">موبایل
                        <input wire:model="phone" class="mt-1 w-full rounded-2xl border-0 bg-cream px-4 py-3 ring-1 ring-olive/15" required>
                    </label>
                    <label class="block text-sm">آدرس در شهر
                        <textarea wire:model="address" rows="3" class="mt-1 w-full rounded-2xl border-0 bg-cream px-4 py-3 ring-1 ring-olive/15" required></textarea>
                    </label>
                    @error('address') <p class="text-sm text-terracotta">{{ $message }}</p> @enderror
                </div>
                <button type="submit" class="mt-6 w-full rounded-2xl bg-saffron py-3 font-medium text-ink">ثبت سفارش</button>
            </form>
        @endif
    </div>
    <aside class="lg:col-span-2">
        <div class="rounded-[2rem] bg-olive p-6 text-cream">
            <h2 class="font-display text-lg">خلاصه سبد</h2>
            @forelse ($lines as $line)
                <div class="mt-4 flex justify-between text-sm">
                    <span>{{ $line->product->name }} × {{ $line->quantity }}</span>
                    <span>{{ number_format($line->line_total) }}</span>
                </div>
            @empty
                <p class="mt-4 text-cream/70">سبدی نیست.</p>
            @endforelse
            <p class="mt-6 border-t border-cream/20 pt-4 text-xl">{{ number_format($total) }} {{ \App\Support\Storefront::currency() }}</p>
        </div>
    </aside>
</div>
