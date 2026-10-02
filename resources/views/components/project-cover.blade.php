@props(['project', 'eager' => false])

{{-- Gambar cover proyek; bila belum ada gambar, tampilkan placeholder bergaya dengan inisial judul. --}}
@if ($project->cover_image_url)
    <img src="{{ $project->cover_image_url }}"
         alt="Cover proyek {{ $project->title }}"
         loading="{{ $eager ? 'eager' : 'lazy' }}"
         {{ $attributes->merge(['class' => 'object-cover']) }}>
@else
    <div role="img" aria-label="Cover proyek {{ $project->title }}"
         {{ $attributes->merge(['class' => 'flex items-center justify-center bg-linear-to-br from-accent-soft via-chip to-line']) }}>
        <span class="font-display text-7xl font-semibold text-accent/40">
            {{ Str::upper(Str::substr($project->title, 0, 1)) }}
        </span>
    </div>
@endif
