<x-layouts.admin title="Dashboard">
    <div class="mb-8 flex flex-wrap items-end justify-between gap-4">
        <div>
            <p class="text-sm text-stone-500">Halo, {{ auth()->user()->name }} 👋</p>
            <h1 class="font-display text-3xl font-semibold">Dashboard</h1>
        </div>
        <a href="{{ route('admin.projects.create') }}"
           class="rounded-lg bg-accent px-4 py-2 text-sm font-medium text-white hover:bg-accent-dark">
            + Proyek baru
        </a>
    </div>

    <div class="grid gap-4 sm:grid-cols-3">
        <div class="rounded-2xl border border-stone-200 bg-white p-6">
            <p class="text-sm text-stone-500">Total Projects</p>
            <p class="mt-2 font-display text-5xl font-semibold">{{ $totalProjects }}</p>
            <a href="{{ route('admin.projects.index') }}" class="mt-4 inline-block text-sm text-accent hover:underline">
                Kelola semua →
            </a>
        </div>
    </div>

    <section class="mt-10">
        <h2 class="mb-4 text-sm font-semibold uppercase tracking-wider text-stone-500">Terakhir ditambahkan</h2>

        <div class="overflow-hidden rounded-2xl border border-stone-200 bg-white">
            @forelse ($latestProjects as $project)
                <div class="flex items-center justify-between gap-4 border-b border-stone-100 px-5 py-4 last:border-0">
                    <div class="min-w-0">
                        <p class="truncate font-medium">{{ $project->title }}</p>
                        <p class="text-xs text-stone-500">{{ $project->created_at->translatedFormat('d M Y') }}</p>
                    </div>
                    <div class="flex shrink-0 gap-3 text-sm">
                        <a href="{{ route('projects.show', $project) }}" target="_blank" class="text-stone-500 hover:text-ink">Lihat</a>
                        <a href="{{ route('admin.projects.edit', $project) }}" class="text-accent hover:underline">Edit</a>
                    </div>
                </div>
            @empty
                <div class="px-5 py-12 text-center text-stone-500">
                    Belum ada proyek. Ceritakan proyek pertama Anda!
                </div>
            @endforelse
        </div>
    </section>
</x-layouts.admin>
