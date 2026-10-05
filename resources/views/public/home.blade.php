@php
    // Nomor section otomatis (01, 02, ...) — tetap urut meskipun section About disembunyikan.
    $n = 0;
    $next = function () use (&$n) {
        return sprintf('%02d', ++$n);
    };
@endphp

<x-layouts.public>
    @include('public.sections.hero')

    @if ($profile)
        @include('public.sections.about', ['label' => $next()])
    @endif

    @include('public.sections.journey', ['label' => $next()])
    @include('public.sections.works', ['label' => $next()])
</x-layouts.public>
