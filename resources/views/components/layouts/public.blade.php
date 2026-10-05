@props(['title' => null, 'description' => null, 'image' => null])

@php
    $home = route('home');
    // $hasAbout dikirim oleh View::composer di AppServiceProvider.
    $links = array_values(array_filter([
        ($hasAbout ?? false) ? ['label' => 'About', 'href' => $home.'#about'] : null,
        ['label' => 'Journey', 'href' => $home.'#journey'],
        ['label' => 'Works', 'href' => $home.'#works'],
        ['label' => 'Contact', 'href' => '#contact'],
    ]));
@endphp

<x-layouts.base :title="$title" :description="$description ?? config('portfolio.role').' — '.config('portfolio.name')" :image="$image"
                 class="transition-colors duration-300">
    <x-slot:head>
        {{--
            Pasang tema SEBELUM halaman tampil agar tidak "berkedip" putih saat mode gelap.
            Urutan: pilihan tersimpan (localStorage) -> pengaturan sistem perangkat.
        --}}
        <script>
            (() => {
                let saved = null;
                try { saved = localStorage.getItem('theme'); } catch (e) {}
                const dark = saved ? saved === 'dark' : matchMedia('(prefers-color-scheme: dark)').matches;
                document.documentElement.classList.toggle('dark', dark);
            })();
        </script>
    </x-slot:head>

    {{-- Navigasi --}}
    <header class="sticky top-0 z-40 border-b border-line/70 bg-paper/85 backdrop-blur transition-colors duration-300">
        <nav class="mx-auto flex max-w-6xl items-center justify-between gap-3 px-4 py-4 sm:gap-4 sm:px-6">
            <a href="{{ $home }}" class="font-display text-xl font-semibold tracking-tight">
                {{ config('portfolio.short_name') }}<span class="text-accent">.</span>
            </a>

            <div class="flex items-center gap-2.5 sm:gap-8">
                <ul class="flex items-center gap-3 text-[0.8125rem] sm:gap-8 sm:text-sm">
                    @foreach ($links as $link)
                        <li>
                            <a href="{{ $link['href'] }}" class="text-muted transition hover:text-ink">{{ $link['label'] }}</a>
                        </li>
                    @endforeach
                </ul>

                {{-- Tombol tema: ikon bulan di mode terang, ikon matahari di mode gelap --}}
                <button type="button" data-theme-toggle aria-label="Aktifkan mode gelap"
                        class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full border border-line-strong text-muted transition hover:border-ink hover:text-ink">
                    <svg class="h-4 w-4 dark:hidden" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M21 12.8A9 9 0 1 1 11.2 3a7 7 0 0 0 9.8 9.8z"/>
                    </svg>
                    <svg class="hidden h-4 w-4 dark:block" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <circle cx="12" cy="12" r="4"/>
                        <path d="M12 2v2M12 20v2M4.9 4.9l1.4 1.4M17.7 17.7l1.4 1.4M2 12h2M20 12h2M4.9 19.1l1.4-1.4M17.7 6.3l1.4-1.4"/>
                    </svg>
                </button>
            </div>
        </nav>
    </header>

    <main>
        {{ $slot }}
    </main>

    {{-- Footer / Contact — tampil di setiap halaman publik --}}
    <footer id="contact" class="scroll-mt-20 bg-footer text-stone-300 transition-colors duration-300">
        <div class="mx-auto max-w-6xl px-4 py-20 sm:px-6 sm:py-28">
            <p class="text-sm font-medium uppercase tracking-[0.2em] text-accent" data-reveal>Let's talk</p>

            <h2 class="mt-4 max-w-3xl font-display text-4xl font-semibold leading-tight text-white sm:text-6xl" data-reveal>
                Punya masalah yang ingin <em class="font-normal text-accent">dipecahkan</em> bersama?
            </h2>

            <p class="mt-6 max-w-xl text-lg text-stone-400" data-reveal>
                Saya senang mendengar cerita di balik sebuah ide. Kirim pesan — mari cari solusinya.
            </p>

            <a href="mailto:{{ config('portfolio.email') }}"
               class="mt-10 inline-block break-all font-display text-2xl text-white underline decoration-accent decoration-2 underline-offset-8 transition hover:text-accent sm:text-4xl"
               data-reveal>
                {{ config('portfolio.email') }}
            </a>

            <div class="mt-16 flex flex-col gap-6 border-t border-stone-800 pt-8 text-sm sm:flex-row sm:items-center sm:justify-between">
                <ul class="flex flex-wrap gap-6">
                    <li><a href="mailto:{{ config('portfolio.email') }}" class="hover:text-white">Email</a></li>
                    <li><a href="{{ config('portfolio.linkedin') }}" target="_blank" rel="noopener" class="hover:text-white">LinkedIn ↗</a></li>
                    <li><a href="{{ config('portfolio.github') }}" target="_blank" rel="noopener" class="hover:text-white">GitHub ↗</a></li>
                    <li><a href="{{ config('portfolio.behance') }}" target="_blank" rel="noopener" class="hover:text-white">Behance ↗</a></li>
                </ul>
                <p class="text-stone-500">© {{ date('Y') }} {{ config('portfolio.name') }}</p>
            </div>
        </div>
    </footer>
</x-layouts.base>
