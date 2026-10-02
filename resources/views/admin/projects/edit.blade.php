<x-layouts.admin :title="'Edit: '.$project->title">
    <div class="mb-8 flex flex-wrap items-end justify-between gap-4">
        <div>
            <a href="{{ route('admin.projects.index') }}" class="text-sm text-stone-500 hover:text-ink">← Semua proyek</a>
            <h1 class="mt-1 font-display text-3xl font-semibold">Edit proyek</h1>
        </div>
        <a href="{{ route('projects.show', $project) }}" target="_blank" class="text-sm text-accent hover:underline">
            Lihat di situs ↗
        </a>
    </div>

    <form method="POST" action="{{ route('admin.projects.update', $project) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT') {{-- HTML form hanya kenal GET/POST; ini "menyamar" menjadi PUT --}}
        @include('admin.projects._form')
    </form>
</x-layouts.admin>
