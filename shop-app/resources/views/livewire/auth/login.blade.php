<div class="mx-auto max-w-md rounded-[2rem] bg-white p-8 ring-1 ring-olive/10">
    <h1 class="font-display text-2xl text-olive">ورود مشتری</h1>
    <form wire:submit="login" class="mt-6 space-y-4">
        <label class="block text-sm">موبایل
            <input wire:model="phone" dir="ltr" class="mt-1 w-full rounded-2xl bg-cream px-4 py-3 ring-1 ring-olive/15" placeholder="09123456789" required>
            @error('phone') <span class="text-terracotta">{{ $message }}</span> @enderror
        </label>
        <label class="block text-sm">رمز عبور
            <input type="password" wire:model="password" class="mt-1 w-full rounded-2xl bg-cream px-4 py-3 ring-1 ring-olive/15" required>
            @error('password') <span class="text-terracotta">{{ $message }}</span> @enderror
        </label>
        <label class="flex items-center gap-2 text-sm">
            <input type="checkbox" wire:model="remember"> مرا به خاطر بسپار
        </label>
        <button type="submit" class="w-full rounded-2xl bg-olive py-3 text-cream">ورود</button>
    </form>
    <p class="mt-4 text-center text-sm">حساب ندارید؟ <a class="text-olive underline" href="{{ route('register') }}">ثبت‌نام</a></p>
</div>
