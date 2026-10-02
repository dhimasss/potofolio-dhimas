{{-- SELECTED WORKS: grid proyek -> klik menuju halaman detail --}}
<section id="works" class="scroll-mt-20 border-t border-line bg-surface">
    <div class="mx-auto max-w-6xl px-4 py-24 sm:px-6 sm:py-32">
        <div class="flex flex-wrap items-end justify-between gap-6" data-reveal>
            <div>
                <p class="text-sm font-medium uppercase tracking-[0.2em] text-accent">02 — Selected Works</p>
                <h2 class="mt-4 font-display text-4xl font-semibold leading-tight sm:text-5xl">
                    Masalah nyata, solusi nyata.
                </h2>
            </div>
            <p class="max-w-sm text-muted">
                Setiap proyek adalah cerita: tantangan yang dihadapi, dan cara saya menjawabnya.
            </p>
        </div>

        @if ($projects->isEmpty())
            <p class="mt-16 rounded-2xl border border-dashed border-line-strong px-6 py-16 text-center text-subtle">
                Cerita proyek sedang ditulis. Kembali lagi segera ✍️
            </p>
        @else
            <div class="mt-16 grid gap-x-8 gap-y-16 md:grid-cols-2">
                @foreach ($projects as $project)
                    <article @class(['group', 'md:mt-24' => $loop->even]) data-reveal>
                        <a href="{{ route('projects.show', $project) }}" class="block">
                            <div class="overflow-hidden rounded-2xl bg-chip">
                                <x-project-cover :project="$project"
                                    class="aspect-[4/3] w-full transition duration-700 ease-out group-hover:scale-105" />
                            </div>

                            <div class="mt-6 flex items-start justify-between gap-4">
                                <div>
                                    <p class="text-sm text-subtle">{{ implode(' · ', array_slice($project->tech_stack ?? [], 0, 3)) }}</p>
                                    <h3 class="mt-1 font-display text-2xl font-semibold transition group-hover:text-accent sm:text-3xl">
                                        {{ $project->title }}
                                    </h3>
                                </div>
                                <span aria-hidden="true"
                                      class="mt-2 flex h-10 w-10 shrink-0 items-center justify-center rounded-full border border-line-strong transition group-hover:border-accent group-hover:bg-accent group-hover:text-paper">
                                    →
                                </span>
                            </div>

                            <p class="mt-3 line-clamp-2 text-muted">{{ $project->the_challenge }}</p>
                        </a>
                    </article>
                @endforeach
            </div>
        @endif
    </div>
</section>
