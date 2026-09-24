<div>
    <section class="mb-10 overflow-hidden rounded-[2rem] bg-olive px-6 py-10 text-cream shadow-xl sm:px-10">
        <p class="mb-2 text-xs tracking-[0.3em] text-saffron">ویترین محله</p>
        <h1 class="font-display text-3xl font-semibold sm:text-4xl">{{ \App\Support\Storefront::name() }}</h1>
        <p class="mt-3 max-w-xl text-cream/80">سفارش آنلاین از همان قفسه‌های فروشگاه فیزیکی. قیمت و موجودی از سیستم مدیریت می‌آید.</p>
        <div class="mt-6">
            <input
                type="search"
                wire:model.live.debounce.300ms="search"
                placeholder="جستجوی کالا…"
                class="w-full max-w-md rounded-2xl border-0 bg-cream/10 px-4 py-3 text-cream placeholder:text-cream/50 ring-1 ring-cream/20 focus:ring-2 focus:ring-saffron"
            >
        </div>
    </section>

    <div class="mb-6 flex flex-wrap gap-2">
        <button
            type="button"
            wire:click="filterCategory(null)"
            class="rounded-full px-4 py-1.5 text-sm {{ $categoryId === null ? 'bg-saffron text-ink' : 'bg-white text-olive ring-1 ring-olive/15' }}"
        >همه</button>
        @foreach ($this->categories as $category)
            <button
                type="button"
                wire:click="filterCategory({{ $category->id }})"
                class="rounded-full px-4 py-1.5 text-sm {{ $categoryId === $category->id ? 'bg-saffron text-ink' : 'bg-white text-olive ring-1 ring-olive/15' }}"
            >{{ $category->name }}</button>
        @endforeach
    </div>

    @if ($this->products->isEmpty())
        <p class="rounded-3xl bg-white p-10 text-center text-ink/60 ring-1 ring-olive/10">هنوز کالایی همگام نشده است.</p>
    @else
        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($this->products as $product)
                <article class="flex flex-col rounded-3xl bg-white p-5 shadow-sm ring-1 ring-olive/10">
                    <div class="mb-4 flex h-28 items-end justify-between rounded-2xl bg-gradient-to-br from-olive/10 to-saffron/20 p-4">
                        <span class="text-xs text-olive/70">{{ $product->unit }}</span>
                        <span class="rounded-full bg-white/80 px-2 py-0.5 text-xs text-olive">موجودی {{ rtrim(rtrim(number_format((float) $product->stock, 3, '.', ''), '0'), '.') }}</span>
                    </div>
                    <h2 class="font-display text-lg text-olive">{{ $product->name }}</h2>
                    <p class="mt-auto pt-4 text-xl font-semibold text-ink">
                        {{ number_format((float) $product->sell_price) }}
                        <span class="text-sm font-normal text-ink/50">{{ \App\Support\Storefront::currency() }}</span>
                    </p>
                    <button
                        type="button"
                        wire:click="addToCart({{ $product->id }})"
                        class="mt-4 rounded-2xl bg-olive py-2.5 text-sm font-medium text-cream transition hover:bg-olive/90"
                    >افزودن به سبد</button>
                </article>
            @endforeach
        </div>
    @endif
</div>
