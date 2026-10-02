<x-layouts.admin title="Projects">
    <div class="mb-8 flex flex-wrap items-end justify-between gap-4">
        <div>
            <h1 class="font-display text-3xl font-semibold">Projects</h1>
            <p class="text-sm text-stone-500">{{ $projects->total() }} proyek tersimpan</p>
        </div>
        <a href="{{ route('admin.projects.create') }}"
           class="rounded-lg bg-accent px-4 py-2 text-sm font-medium text-white hover:bg-accent-dark">
            + Proyek baru
        </a>
    </div>

    <div class="overflow-x-auto rounded-2xl border border-stone-200 bg-white">
        <table class="w-full min-w-[640px] text-left text-sm">
            <thead class="border-b border-stone-200 bg-stone-50 text-xs uppercase tracking-wider text-stone-500">
                <tr>
                    <th class="px-5 py-3 font-medium">Proyek</th>
                    <th class="px-5 py-3 font-medium">Tech stack</th>
                    <th class="px-5 py-3 font-medium">Diperbarui</th>
                    <th class="px-5 py-3 text-right font-medium">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-stone-100">
                @forelse ($projects as $project)
                    <tr class="align-middle">
                        <td class="px-5 py-3">
                            <div class="flex items-center gap-4">
                                @if ($project->cover_image_url)
                                    <img src="{{ $project->cover_image_url }}" alt="" class="h-12 w-16 shrink-0 rounded-md object-cover">
                                @else
                                    <div class="h-12 w-16 shrink-0 rounded-md bg-stone-100"></div>
                                @endif
                                <div class="min-w-0">
                                    <p class="truncate font-medium">{{ $project->title }}</p>
                                    <p class="truncate text-xs text-stone-500">/projects/{{ $project->slug }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-5 py-3">
                            <div class="flex flex-wrap gap-1">
                                @foreach (array_slice($project->tech_stack ?? [], 0, 3) as $tech)
                                    <span class="whitespace-nowrap rounded-full bg-stone-100 px-2 py-0.5 text-xs text-stone-700">{{ $tech }}</span>
                                @endforeach
                                @if (count($project->tech_stack ?? []) > 3)
                                    <span class="px-1 text-xs text-stone-400">+{{ count($project->tech_stack) - 3 }}</span>
                                @endif
                            </div>
                        </td>
                        <td class="whitespace-nowrap px-5 py-3 text-stone-500">
                            {{ $project->updated_at->translatedFormat('d M Y') }}
                        </td>
                        <td class="px-5 py-3">
                            <div class="flex items-center justify-end gap-3">
                                <a href="{{ route('projects.show', $project) }}" target="_blank" class="text-stone-500 hover:text-ink">Lihat</a>
                                <a href="{{ route('admin.projects.edit', $project) }}" class="text-accent hover:underline">Edit</a>
                                <form method="POST" action="{{ route('admin.projects.destroy', $project) }}"
                                      onsubmit="return confirm({{ Js::from('Hapus proyek "'.$project->title.'"? Gambar cover juga akan dihapus.') }})">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:underline">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-5 py-16 text-center text-stone-500">
                            Belum ada proyek.
                            <a href="{{ route('admin.projects.create') }}" class="text-accent hover:underline">Tambahkan yang pertama →</a>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $projects->links() }}
    </div>
</x-layouts.admin>
