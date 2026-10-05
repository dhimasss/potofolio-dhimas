<x-layouts.admin title="About">
    <div class="mb-8 flex flex-wrap items-end justify-between gap-4">
        <div>
            <h1 class="font-display text-3xl font-semibold">About</h1>
            <p class="text-sm text-stone-500">Biodata yang tampil di section "About Me" pada beranda.</p>
        </div>

        @if ($profile)
            <div class="flex items-center gap-3">
                <a href="{{ route('home') }}#about" target="_blank" class="text-sm text-stone-500 hover:text-ink">Lihat di situs ↗</a>
                <a href="{{ route('admin.about.edit') }}"
                   class="rounded-lg bg-accent px-4 py-2 text-sm font-medium text-white hover:bg-accent-dark">
                    Edit biodata
                </a>
            </div>
        @endif
    </div>

    @if (! $profile)
        <div class="rounded-2xl border border-dashed border-stone-300 bg-white px-6 py-16 text-center">
            <p class="font-display text-xl font-semibold">Belum ada biodata</p>
            <p class="mx-auto mt-2 max-w-md text-sm text-stone-500">
                Section "About Me" disembunyikan dari situs sampai biodata dibuat.
            </p>
            <a href="{{ route('admin.about.create') }}"
               class="mt-6 inline-block rounded-lg bg-accent px-4 py-2 text-sm font-medium text-white hover:bg-accent-dark">
                + Buat biodata
            </a>
        </div>
    @else
        <div class="grid gap-8 lg:grid-cols-3">
            {{-- Kartu identitas --}}
            <div class="rounded-2xl border border-stone-200 bg-white p-6 text-center">
                @if ($profile->photo_url)
                    <img src="{{ $profile->photo_url }}" alt="Foto {{ $profile->full_name }}"
                         class="mx-auto aspect-[4/5] w-full max-w-60 rounded-xl object-cover">
                @else
                    <div class="mx-auto flex aspect-[4/5] w-full max-w-60 items-center justify-center rounded-xl bg-accent-soft font-display text-5xl text-accent/60">
                        {{ $profile->initials }}
                    </div>
                @endif

                <p class="mt-5 font-display text-xl font-semibold">{{ $profile->full_name }}</p>
                @if ($profile->headline)
                    <p class="mt-1 text-sm text-stone-500">{{ $profile->headline }}</p>
                @endif

                @if (! empty($profile->skills))
                    <div class="mt-5 flex flex-wrap justify-center gap-1.5">
                        @foreach ($profile->skills as $skill)
                            <span class="whitespace-nowrap rounded-full bg-stone-100 px-2.5 py-0.5 text-xs text-stone-700">{{ $skill }}</span>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- Bio & biodata --}}
            <div class="space-y-6 lg:col-span-2">
                <div class="rounded-2xl border border-stone-200 bg-white p-6">
                    <h2 class="text-xs font-semibold uppercase tracking-wider text-stone-500">Bio</h2>
                    <x-prose :text="$profile->bio" class="mt-3" />
                </div>

                <div class="rounded-2xl border border-stone-200 bg-white p-6">
                    <h2 class="text-xs font-semibold uppercase tracking-wider text-stone-500">Biodata</h2>
                    @if ($details = $profile->details())
                        <dl class="mt-3 divide-y divide-stone-100 text-sm">
                            @foreach ($details as $label => $value)
                                <div class="flex flex-col gap-1 py-2.5 sm:flex-row sm:gap-4">
                                    <dt class="text-stone-500 sm:w-44 sm:shrink-0">{{ $label }}</dt>
                                    <dd class="break-words font-medium">{{ $value }}</dd>
                                </div>
                            @endforeach
                        </dl>
                    @else
                        <p class="mt-3 text-sm text-stone-500">Belum ada detail biodata yang diisi.</p>
                    @endif
                </div>

                {{-- Zona berbahaya --}}
                <div class="flex flex-col gap-4 rounded-2xl border border-red-200 bg-red-50/50 p-6 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <p class="font-medium text-red-700">Hapus biodata</p>
                        <p class="text-sm text-red-600/80">Data &amp; foto dihapus permanen, section About disembunyikan dari situs.</p>
                    </div>
                    <form method="POST" action="{{ route('admin.about.destroy') }}"
                          onsubmit="return confirm('Hapus biodata beserta fotonya? Tindakan ini tidak bisa dibatalkan.')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="whitespace-nowrap rounded-lg border border-red-300 bg-white px-4 py-2 text-sm font-medium text-red-700 hover:bg-red-50">
                            Hapus biodata
                        </button>
                    </form>
                </div>
            </div>
        </div>
    @endif
</x-layouts.admin>
