<div class="mx-auto max-w-md rounded-[2rem] bg-white p-8 ring-1 ring-olive/10">
    <h1 class="font-display text-2xl text-olive">ثبت‌نام مشتری</h1>
    <p class="mt-1 text-sm text-ink/60">با موبایل وارد می‌شوید؛ ارسال فقط در شهر فروشگاه.</p>
    <form wire:submit="register" class="mt-6 space-y-4">
        <label class="block text-sm">نام
            <input wire:model="name" class="mt-1 w-full rounded-2xl bg-cream px-4 py-3 ring-1 ring-olive/15" required>
            @error('name') <span class="text-terracotta">{{ $message }}</span> @enderror
        </label>
        <label class="block text-sm">موبایل
            <input wire:model="phone" dir="ltr" class="mt-1 w-full rounded-2xl bg-cream px-4 py-3 ring-1 ring-olive/15" placeholder="09123456789" required>
            @error('phone') <span class="text-terracotta">{{ $message }}</span> @enderror
        </label>
        <label class="block text-sm">آدرس
            <textarea wire:model="address" rows="2" class="mt-1 w-full rounded-2xl bg-cream px-4 py-3 ring-1 ring-olive/15" required></textarea>
            @error('address') <span class="text-terracotta">{{ $message }}</span> @enderror
        </label>
        <label class="block text-sm">رمز عبور
            <input type="password" wire:model="password" class="mt-1 w-full rounded-2xl bg-cream px-4 py-3 ring-1 ring-olive/15" required>
            @error('password') <span class="text-terracotta">{{ $message }}</span> @enderror
        </label>
        <label class="block text-sm">تکرار رمز
            <input type="password" wire:model="password_confirmation" class="mt-1 w-full rounded-2xl bg-cream px-4 py-3 ring-1 ring-olive/15" required>
        </label>
        <button type="submit" class="w-full rounded-2xl bg-olive py-3 text-cream">ساخت حساب</button>
    </form>
    <p class="mt-4 text-center text-sm">حساب دارید؟ <a class="text-olive underline" href="{{ route('login') }}">ورود</a></p>
</div>
