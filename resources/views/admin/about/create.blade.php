<x-layouts.admin title="Buat Biodata">
    <a href="{{ route('admin.about.show') }}" class="text-sm text-stone-500 hover:text-ink">← About</a>
    <h1 class="mb-8 mt-1 font-display text-3xl font-semibold">Perkenalkan diri Anda</h1>

    <form method="POST" action="{{ route('admin.about.store') }}" enctype="multipart/form-data">
        @csrf
        @include('admin.about._form')
    </form>
</x-layouts.admin>
