{{--
    PROJECT DETAIL: alur cerita Challenge -> Solution -> Tools -> Ajakan,
    agar pengunjung memahami NILAI proyek, bukan sekadar melihat tampilannya.
--}}
<x-layouts.public
    :title="$project->title"
    :description="Str::limit($project->the_challenge, 155)"
    :image="$project->cover_image_url">

    <article>
        {{-- Pembuka --}}
        <header class="mx-auto max-w-6xl px-4 pb-12 pt-16 sm:px-6 sm:pt-24">
            <a href="{{ route('home') }}#works" class="text-sm text-subtle transition hover:text-ink">← Semua karya</a>

            <h1 class="mt-8 max-w-4xl font-display text-4xl font-semibold leading-[1.1] tracking-tight sm:text-6xl" data-reveal>
                {{ $project->title }}
            </h1>

            <dl class="mt-10 grid gap-6 border-t border-line pt-6 text-sm sm:grid-cols-3" data-reveal>
                <div>
                    <dt class="text-subtle">Peran</dt>
                    <dd class="mt-1 font-medium">{{ config('portfolio.role') }}</dd>
                </div>
                <div>
                    <dt class="text-subtle">Tech stack</dt>
                    <dd class="mt-1 font-medium">{{ implode(', ', $project->tech_stack ?? []) ?: '—' }}</dd>
                </div>
                <div>
                    <dt class="text-subtle">Tautan</dt>
                    <dd class="mt-1 font-medium">
                        @if ($project->project_url)
                            <a href="{{ $project->project_url }}" target="_blank" rel="noopener" class="text-accent hover:underline">
                                {{ Str::of($project->project_url)->after('://')->rtrim('/')->limit(40) }} ↗
                            </a>
                        @else
                            —
                        @endif
                    </dd>
                </div>
            </dl>
        </header>

        {{-- Cover --}}
        <div class="mx-auto max-w-6xl px-4 sm:px-6" data-reveal>
            <div class="overflow-hidden rounded-3xl bg-chip">
                <x-project-cover :project="$project" eager class="aspect-[16/9] w-full" />
            </div>
        </div>

        {{-- Bab 1: The Challenge --}}
        <section class="mx-auto grid max-w-6xl gap-8 px-4 py-20 sm:px-6 sm:py-28 lg:grid-cols-12">
            <div class="lg:col-span-4">
                <div class="lg:sticky lg:top-28" data-reveal>
                    <p class="text-sm font-medium uppercase tracking-[0.2em] text-accent">Bab 01</p>
                    <h2 class="mt-3 font-display text-3xl font-semibold sm:text-4xl">The Challenge</h2>
                    <p class="mt-3 text-subtle">Masalah apa yang perlu diselesaikan?</p>
                </div>
            </div>
            <x-prose :text="$project->the_challenge" class="lg:col-span-7 lg:col-start-6" data-reveal />
        </section>

        {{-- Bab 2: The Solution --}}
        <section class="border-y border-line bg-surface">
            <div class="mx-auto grid max-w-6xl gap-8 px-4 py-20 sm:px-6 sm:py-28 lg:grid-cols-12">
                <div class="lg:col-span-4">
                    <div class="lg:sticky lg:top-28" data-reveal>
                        <p class="text-sm font-medium uppercase tracking-[0.2em] text-accent">Bab 02</p>
                        <h2 class="mt-3 font-display text-3xl font-semibold sm:text-4xl">The Solution</h2>
                        <p class="mt-3 text-subtle">Bagaimana saya menjawabnya.</p>
                    </div>
                </div>
                <div class="lg:col-span-7 lg:col-start-6">
                    <x-prose :text="$project->the_solution" data-reveal />

                    @if (! empty($project->tech_stack))
                        <div class="mt-12" data-reveal>
                            <h3 class="text-sm font-medium uppercase tracking-[0.2em] text-subtle">Alat yang dipakai</h3>
                            <ul class="mt-4 flex flex-wrap gap-2">
                                @foreach ($project->tech_stack as $tech)
                                    <li class="rounded-full border border-line-strong px-4 py-1.5 text-sm">{{ $tech }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if ($project->project_url)
                        <a href="{{ $project->project_url }}" target="_blank" rel="noopener"
                           class="mt-12 inline-flex items-center gap-2 rounded-full bg-ink px-7 py-3.5 font-medium text-paper transition hover:bg-accent"
                           data-reveal>
                            Lihat proyeknya ↗
                        </a>
                    @endif
                </div>
            </div>
        </section>
    </article>

    {{-- Lanjut membaca --}}
    @if ($nextProject)
        <a href="{{ route('projects.show', $nextProject) }}" class="group block">
            <div class="mx-auto flex max-w-6xl flex-col gap-8 px-4 py-20 sm:px-6 sm:py-24 md:flex-row md:items-center md:justify-between">
                <div>
                    <p class="text-sm font-medium uppercase tracking-[0.2em] text-subtle">Cerita berikutnya</p>
                    <p class="mt-3 font-display text-3xl font-semibold transition group-hover:text-accent sm:text-5xl">
                        {{ $nextProject->title }} <span class="inline-block transition group-hover:translate-x-2">→</span>
                    </p>
                </div>
                <div class="w-full overflow-hidden rounded-2xl bg-chip md:w-72">
                    <x-project-cover :project="$nextProject" class="aspect-[4/3] w-full transition duration-700 group-hover:scale-105" />
                </div>
            </div>
        </a>
    @endif
</x-layouts.public>
