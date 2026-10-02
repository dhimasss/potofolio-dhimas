<x-layouts.admin title="Proyek Baru">
    <a href="{{ route('admin.projects.index') }}" class="text-sm text-stone-500 hover:text-ink">← Semua proyek</a>
    <h1 class="mb-8 mt-1 font-display text-3xl font-semibold">Ceritakan proyek baru</h1>

    {{-- enctype multipart wajib agar file bisa ikut terkirim --}}
    <form method="POST" action="{{ route('admin.projects.store') }}" enctype="multipart/form-data">
        @csrf
        @include('admin.projects._form')
    </form>
</x-layouts.admin>
