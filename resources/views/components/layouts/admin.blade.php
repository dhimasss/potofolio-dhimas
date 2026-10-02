@props(['title' => 'Admin'])

@php
    $nav = [
        ['label' => 'Dashboard', 'route' => 'admin.dashboard', 'active' => 'admin.dashboard'],
        ['label' => 'Projects', 'route' => 'admin.projects.index', 'active' => 'admin.projects.*'],
    ];
@endphp

<x-layouts.base :title="$title" class="bg-stone-100">
    <header class="border-b border-stone-200 bg-white">
        <div class="mx-auto flex max-w-6xl flex-wrap items-center justify-between gap-4 px-4 py-3 sm:px-6">
            <div class="flex items-center gap-8">
                <a href="{{ route('admin.dashboard') }}" class="font-display text-lg font-semibold">
                    Panel<span class="text-accent">.</span>
                </a>

                <nav class="flex gap-1 text-sm">
                    @foreach ($nav as $item)
                        <a href="{{ route($item['route']) }}"
                           @class([
                               'rounded-md px-3 py-1.5 transition',
                               'bg-accent-soft font-medium text-accent-dark' => request()->routeIs($item['active']),
                               'text-stone-600 hover:bg-stone-100' => ! request()->routeIs($item['active']),
                           ])>
                            {{ $item['label'] }}
                        </a>
                    @endforeach
                </nav>
            </div>

            <div class="flex items-center gap-4 text-sm">
                <a href="{{ route('home') }}" target="_blank" class="text-stone-500 hover:text-ink">Lihat situs ↗</a>

                <form method="POST" action="{{ route('admin.logout') }}">
                    @csrf
                    <button type="submit" class="rounded-md border border-stone-300 px-3 py-1.5 text-stone-700 hover:bg-stone-50">
                        Keluar
                    </button>
                </form>
            </div>
        </div>
    </header>

    <main class="mx-auto max-w-6xl px-4 py-8 sm:px-6">
        @if (session('success'))
            <div class="mb-6 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800" role="status">
                {{ session('success') }}
            </div>
        @endif

        {{ $slot }}
    </main>
</x-layouts.base>
