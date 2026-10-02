{{-- HERO: sapaan hangat + pesan utama + ajakan --}}
<section class="relative overflow-hidden">
    {{-- Aksen dekoratif lembut --}}
    <div aria-hidden="true" class="pointer-events-none absolute -right-32 -top-32 h-[28rem] w-[28rem] rounded-full bg-accent-soft blur-3xl"></div>

    <div class="relative mx-auto flex min-h-[calc(100svh-4.5rem)] max-w-6xl flex-col justify-center px-4 py-24 sm:px-6">
        <p class="text-lg text-muted" data-reveal>
            {{ config('portfolio.role') }} — <span class="font-medium text-ink">{{ config('portfolio.name') }}</span>
        </p>

        <h1 class="mt-6 max-w-5xl text-balance font-display text-5xl font-semibold leading-[1.05] tracking-tight sm:text-6xl lg:text-7xl xl:text-[5.25rem]" data-reveal>
            Crafting visual stories<br class="hidden sm:block">
            that <em class="font-normal text-accent">inspire</em> and <em class="font-normal text-accent">connect</em>.
        </h1>

        <p class="mt-8 max-w-xl text-lg leading-relaxed text-muted sm:text-xl" data-reveal>
            Saya percaya karya terbaik — baik kode maupun visual — lahir dari memahami manusia di baliknya.
        </p>

        <div class="mt-12 flex flex-wrap items-center gap-4" data-reveal>
            <a href="#works"
               class="group inline-flex items-center gap-2 rounded-full bg-ink px-7 py-3.5 font-medium text-paper transition hover:bg-accent">
                Lihat karya saya
                <span class="transition group-hover:translate-y-0.5">↓</span>
            </a>
            <a href="#contact"
               class="inline-flex items-center rounded-full border border-line-strong px-7 py-3.5 font-medium transition hover:border-ink">
                Mari ngobrol
            </a>
        </div>
    </div>
</section>
