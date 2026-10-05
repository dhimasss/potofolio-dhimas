{{--
    ABOUT ME: biodata dari database (dikelola di /admin/about).
    Variabel: $profile (Profile), $label (nomor section, contoh "01")
--}}
<section id="about" class="scroll-mt-20 border-t border-line">
    <div class="mx-auto grid max-w-6xl items-start gap-12 px-4 py-24 sm:px-6 sm:py-32 lg:grid-cols-12 lg:gap-16">
        {{-- Foto --}}
        <div class="mx-auto w-full max-w-sm lg:col-span-5 lg:max-w-none" data-reveal>
            <div class="relative">
                <div aria-hidden="true" class="absolute inset-0 translate-x-3 translate-y-3 rounded-3xl border border-accent/40 sm:translate-x-4 sm:translate-y-4"></div>
                @if ($profile->photo_url)
                    <img src="{{ $profile->photo_url }}" alt="Foto {{ $profile->full_name }}" loading="lazy"
                         class="relative aspect-[4/5] w-full rounded-3xl bg-chip object-cover">
                @else
                    <div role="img" aria-label="{{ $profile->full_name }}"
                         class="relative flex aspect-[4/5] w-full items-center justify-center rounded-3xl bg-linear-to-br from-accent-soft via-chip to-line">
                        <span class="font-display text-8xl font-semibold text-accent/40">{{ $profile->initials }}</span>
                    </div>
                @endif
            </div>
        </div>

        {{-- Cerita & biodata --}}
        <div class="lg:col-span-7">
            <div data-reveal>
                <p class="text-sm font-medium uppercase tracking-[0.2em] text-accent">{{ $label }} — About Me</p>
                <h2 class="mt-4 font-display text-4xl font-semibold leading-tight sm:text-5xl">
                    {{ $profile->full_name }}
                </h2>
                @if ($profile->headline)
                    <p class="mt-3 text-lg text-muted">{{ $profile->headline }}</p>
                @endif
            </div>

            <x-prose :text="$profile->bio" class="mt-8" data-reveal />

            @if ($details = $profile->details())
                <dl class="mt-10 grid gap-x-8 gap-y-5 border-t border-line pt-8 sm:grid-cols-2" data-reveal>
                    @foreach ($details as $detailLabel => $value)
                        <div class="min-w-0">
                            <dt class="text-sm text-subtle">{{ $detailLabel }}</dt>
                            <dd class="mt-1 break-words font-medium">{{ $value }}</dd>
                        </div>
                    @endforeach
                </dl>
            @endif

            @if (! empty($profile->skills))
                <div class="mt-10" data-reveal>
                    <h3 class="text-sm font-medium uppercase tracking-[0.2em] text-subtle">Skills</h3>
                    <ul class="mt-4 flex flex-wrap gap-2">
                        @foreach ($profile->skills as $skill)
                            <li class="rounded-full border border-line-strong px-4 py-1.5 text-sm">{{ $skill }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
    </div>
</section>
