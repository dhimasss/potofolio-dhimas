<x-layouts.base title="Login Admin" class="flex items-center justify-center px-4">
    <div class="w-full max-w-sm">
        <div class="mb-8 text-center">
            <a href="{{ route('home') }}" class="font-display text-2xl font-semibold">
                Panel<span class="text-accent">.</span>
            </a>
            <p class="mt-2 text-sm text-stone-500">Masuk untuk mengelola cerita proyek Anda.</p>
        </div>

        <div class="rounded-2xl border border-stone-200 bg-white p-6 shadow-sm sm:p-8">
            @if (session('status'))
                <div class="mb-5 rounded-lg bg-stone-100 px-3 py-2 text-sm text-stone-700">{{ session('status') }}</div>
            @endif

            <form method="POST" action="{{ route('admin.login.store') }}" class="space-y-5">
                @csrf

                <div>
                    <label for="email" class="mb-1.5 block text-sm font-medium">Email</label>
                    <input id="email" name="email" type="email" value="{{ old('email') }}"
                           required autofocus autocomplete="username"
                           class="w-full rounded-lg border border-stone-300 px-3 py-2 outline-none focus:border-accent focus:ring-2 focus:ring-accent/20">
                    @error('email')
                        <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="mb-1.5 block text-sm font-medium">Password</label>
                    <input id="password" name="password" type="password"
                           required autocomplete="current-password"
                           class="w-full rounded-lg border border-stone-300 px-3 py-2 outline-none focus:border-accent focus:ring-2 focus:ring-accent/20">
                    @error('password')
                        <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <label class="flex items-center gap-2 text-sm text-stone-600">
                    <input type="checkbox" name="remember" class="rounded border-stone-300 accent-accent">
                    Ingat saya
                </label>

                <button type="submit"
                        class="w-full rounded-lg bg-ink px-4 py-2.5 font-medium text-white transition hover:bg-stone-800">
                    Masuk
                </button>
            </form>
        </div>

        <p class="mt-6 text-center text-sm">
            <a href="{{ route('home') }}" class="text-stone-500 hover:text-ink">← Kembali ke situs</a>
        </p>
    </div>
</x-layouts.base>
