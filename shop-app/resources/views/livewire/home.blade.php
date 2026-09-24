<div class="space-y-14">
    <section class="relative overflow-hidden rounded-[2.5rem] bg-olive px-6 py-14 text-cream sm:px-12">
        <div class="absolute -left-10 -top-10 h-40 w-40 rounded-full bg-saffron/30 blur-2xl"></div>
        <div class="absolute bottom-0 right-10 h-32 w-32 rounded-full bg-cream/10 blur-xl"></div>
        <p class="text-xs tracking-[0.35em] text-saffron">ارسال در محله</p>
        <h1 class="mt-3 max-w-xl font-display text-4xl font-semibold leading-tight sm:text-5xl">
            {{ \App\Support\Storefront::name() }}
        </h1>
        <p class="mt-4 max-w-lg text-lg text-cream/80">
            همان قفسه‌های فروشگاه فیزیکی، روی اینترنت.
            @if (\App\Support\Storefront::city())
                پیک فقط در {{ \App\Support\Storefront::city() }}.
            @endif
        </p>
        <div class="mt-8 flex flex-wrap gap-3">
            <a href="{{ route('shop') }}" class="rounded-2xl bg-saffron px-6 py-3 font-medium text-ink">مشاهده کالاها</a>
            @guest
                <a href="{{ route('register') }}" class="rounded-2xl bg-cream/10 px-6 py-3 ring-1 ring-cream/30">ثبت‌نام مشتری</a>
            @endguest
        </div>
    </section>

    <section class="grid gap-4 sm:grid-cols-3">
        @foreach ([
            ['۱', 'انتخاب از ویترین', 'قیمت و موجودی از سیستم فروشگاه می‌آید.'],
            ['۲', 'ثبت سفارش', 'با حساب مشتری، آدرس همان شهر ذخیره می‌شود.'],
            ['۳', 'ارسال با پیک', 'حتی اگر صندوق خاموش باشد سفارش در صف می‌ماند.'],
        ] as $step)
            <article class="rounded-3xl bg-white p-6 ring-1 ring-olive/10">
                <span class="font-display text-3xl text-saffron">{{ $step[0] }}</span>
                <h2 class="mt-2 font-display text-lg text-olive">{{ $step[1] }}</h2>
                <p class="mt-1 text-sm text-ink/60">{{ $step[2] }}</p>
            </article>
        @endforeach
    </section>

    @if ($categories->isNotEmpty())
        <section>
            <div class="mb-4 flex items-end justify-between">
                <h2 class="font-display text-2xl text-olive">دسته‌ها</h2>
                <a href="{{ route('shop') }}" class="text-sm text-olive/70">همه کالاها</a>
            </div>
            <div class="flex flex-wrap gap-2">
                @foreach ($categories as $category)
                    <a href="{{ route('shop', ['category' => $category->id]) }}" class="rounded-full bg-white px-4 py-2 text-sm text-olive ring-1 ring-olive/15">{{ $category->name }}</a>
                @endforeach
            </div>
        </section>
    @endif

    <section>
        <h2 class="mb-4 font-display text-2xl text-olive">تازه‌های قفسه</h2>
        @if ($featured->isEmpty())
            <p class="rounded-3xl bg-white p-10 text-center text-ink/50 ring-1 ring-olive/10">کالا هنوز از سیستم مدیریت همگام نشده است.</p>
        @else
            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($featured as $product)
                    <article class="flex flex-col rounded-3xl bg-white p-5 ring-1 ring-olive/10">
                        <div class="mb-4 h-24 rounded-2xl bg-gradient-to-br from-olive/15 to-saffron/25"></div>
                        <h3 class="font-display text-lg text-olive">{{ $product->name }}</h3>
                        <p class="mt-auto pt-3 text-xl font-semibold">
                            {{ number_format((float) $product->sell_price) }}
                            <span class="text-sm font-normal text-ink/50">{{ \App\Support\Storefront::currency() }}</span>
                        </p>
                        <button type="button" wire:click="addToCart({{ $product->id }})" class="mt-4 rounded-2xl bg-olive py-2.5 text-sm text-cream">افزودن به سبد</button>
                    </article>
                @endforeach
            </div>
        @endif
    </section>
</div>
