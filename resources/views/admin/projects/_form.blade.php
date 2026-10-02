{{--
    Form bersama untuk create & edit.
    Variabel: $project (Project baru atau yang diedit)
--}}
@php
    $input = 'w-full rounded-lg border border-stone-300 bg-white px-3 py-2 outline-none focus:border-accent focus:ring-2 focus:ring-accent/20';
    $label = 'mb-1.5 block text-sm font-medium';
    $hint = 'mb-2 text-sm text-stone-500';
    $error = 'mt-1.5 text-sm text-red-600';
@endphp

@if ($errors->any())
    <div class="mb-6 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700" role="alert">
        Ada {{ $errors->count() }} isian yang perlu diperbaiki.
    </div>
@endif

<div class="grid gap-8 lg:grid-cols-3">
    {{-- Kolom kiri: cerita --}}
    <div class="space-y-6 rounded-2xl border border-stone-200 bg-white p-6 lg:col-span-2">
        <div>
            <label for="title" class="{{ $label }}">Judul proyek</label>
            <input id="title" name="title" type="text" value="{{ old('title', $project->title) }}" required class="{{ $input }}">
            @if ($project->exists)
                <p class="mt-1.5 text-xs text-stone-500">URL saat ini: /projects/{{ $project->slug }} — ikut berubah bila judul diganti.</p>
            @endif
            @error('title') <p class="{{ $error }}">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="the_challenge" class="{{ $label }}">The Challenge</label>
            <p class="{{ $hint }}">Masalah apa yang dihadapi? Siapa yang terdampak, dan mengapa itu penting?</p>
            <textarea id="the_challenge" name="the_challenge" rows="7" required class="{{ $input }}">{{ old('the_challenge', $project->the_challenge) }}</textarea>
            @error('the_challenge') <p class="{{ $error }}">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="the_solution" class="{{ $label }}">The Solution</label>
            <p class="{{ $hint }}">Bagaimana Anda menyelesaikannya? Keputusan apa yang diambil, dan apa dampaknya?</p>
            <textarea id="the_solution" name="the_solution" rows="9" required class="{{ $input }}">{{ old('the_solution', $project->the_solution) }}</textarea>
            @error('the_solution') <p class="{{ $error }}">{{ $message }}</p> @enderror
        </div>
    </div>

    {{-- Kolom kanan: media & meta --}}
    <div class="space-y-6">
        <div class="rounded-2xl border border-stone-200 bg-white p-6">
            <label for="cover_image" class="{{ $label }}">Gambar cover</label>
            <p class="{{ $hint }}">JPG, PNG, atau WEBP · maks. 2 MB · disarankan rasio 4:3.</p>

            <img id="cover-preview"
                 src="{{ $project->cover_image_url }}"
                 alt="Pratinjau cover"
                 @class(['mb-3 aspect-[4/3] w-full rounded-lg object-cover', 'hidden' => ! $project->cover_image_url])>

            <input id="cover_image" name="cover_image" type="file" accept="image/jpeg,image/png,image/webp"
                   @required(! $project->exists)
                   class="block w-full text-sm text-stone-600 file:mr-3 file:rounded-md file:border-0 file:bg-accent-soft file:px-3 file:py-2 file:text-sm file:font-medium file:text-accent-dark hover:file:bg-accent/20">
            @if ($project->exists)
                <p class="mt-1.5 text-xs text-stone-500">Kosongkan bila tidak ingin mengganti gambar.</p>
            @endif
            @error('cover_image') <p class="{{ $error }}">{{ $message }}</p> @enderror
        </div>

        <div class="space-y-6 rounded-2xl border border-stone-200 bg-white p-6">
            <div>
                <label for="tech_stack" class="{{ $label }}">Tech stack</label>
                <p class="{{ $hint }}">Pisahkan dengan koma.</p>
                <input id="tech_stack" name="tech_stack" type="text" placeholder="Laravel, MySQL, Tailwind CSS"
                       value="{{ old('tech_stack', implode(', ', $project->tech_stack ?? [])) }}" class="{{ $input }}">
                @error('tech_stack') <p class="{{ $error }}">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="project_url" class="{{ $label }}">URL proyek <span class="font-normal text-stone-400">(opsional)</span></label>
                <input id="project_url" name="project_url" type="url" placeholder="https://github.com/..."
                       value="{{ old('project_url', $project->project_url) }}" class="{{ $input }}">
                @error('project_url') <p class="{{ $error }}">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="flex gap-3">
            <button type="submit" class="flex-1 rounded-lg bg-ink px-4 py-2.5 font-medium text-white hover:bg-stone-800">
                {{ $project->exists ? 'Simpan perubahan' : 'Terbitkan proyek' }}
            </button>
            <a href="{{ route('admin.projects.index') }}" class="rounded-lg border border-stone-300 px-4 py-2.5 text-stone-700 hover:bg-white">
                Batal
            </a>
        </div>
    </div>
</div>

<script>
    // Pratinjau gambar sebelum di-upload (murni di browser, belum dikirim ke server).
    document.getElementById('cover_image').addEventListener('change', (e) => {
        const file = e.target.files[0];
        const preview = document.getElementById('cover-preview');
        if (!file) return;
        preview.src = URL.createObjectURL(file);
        preview.classList.remove('hidden');
    });
</script>
