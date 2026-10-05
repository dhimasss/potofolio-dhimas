{{--
    Form biodata bersama untuk create & edit.
    Variabel: $profile (Profile baru atau yang diedit)
--}}
@php
    $input = 'w-full rounded-lg border border-stone-300 bg-white px-3 py-2 outline-none focus:border-accent focus:ring-2 focus:ring-accent/20';
    $label = 'mb-1.5 block text-sm font-medium';
    $hint = 'mb-2 text-sm text-stone-500';
    $error = 'mt-1.5 text-sm text-red-600';
    $optional = '<span class="font-normal text-stone-400">(opsional)</span>';
@endphp

@if ($errors->any())
    <div class="mb-6 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700" role="alert">
        Ada {{ $errors->count() }} isian yang perlu diperbaiki.
    </div>
@endif

<div class="grid gap-8 lg:grid-cols-3">
    {{-- Kolom kiri: identitas & cerita --}}
    <div class="space-y-6 lg:col-span-2">
        <div class="space-y-6 rounded-2xl border border-stone-200 bg-white p-6">
            <div class="grid gap-6 sm:grid-cols-2">
                <div>
                    <label for="full_name" class="{{ $label }}">Nama lengkap</label>
                    <input id="full_name" name="full_name" type="text" required class="{{ $input }}"
                           value="{{ old('full_name', $profile->full_name) }}">
                    @error('full_name') <p class="{{ $error }}">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="headline" class="{{ $label }}">Headline {!! $optional !!}</label>
                    <input id="headline" name="headline" type="text" placeholder="Web Development & Graphic Designer" class="{{ $input }}"
                           value="{{ old('headline', $profile->headline) }}">
                    @error('headline') <p class="{{ $error }}">{{ $message }}</p> @enderror
                </div>
            </div>

            <div>
                <label for="bio" class="{{ $label }}">Bio</label>
                <p class="{{ $hint }}">Ceritakan siapa Anda, apa yang Anda kerjakan, dan apa yang membuat Anda bersemangat. Pisahkan paragraf dengan baris kosong.</p>
                <textarea id="bio" name="bio" rows="8" required class="{{ $input }}">{{ old('bio', $profile->bio) }}</textarea>
                @error('bio') <p class="{{ $error }}">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="skills" class="{{ $label }}">Skills {!! $optional !!}</label>
                <p class="{{ $hint }}">Pisahkan dengan koma.</p>
                <input id="skills" name="skills" type="text" placeholder="Laravel, Tailwind CSS, Figma, Adobe Illustrator" class="{{ $input }}"
                       value="{{ old('skills', implode(', ', $profile->skills ?? [])) }}">
                @error('skills') <p class="{{ $error }}">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="rounded-2xl border border-stone-200 bg-white p-6">
            <h2 class="text-sm font-semibold">Biodata</h2>
            <p class="mt-1 text-sm text-stone-500">Semua opsional. Hanya yang diisi yang tampil di situs — pertimbangkan privasi sebelum mengisi tanggal lahir &amp; nomor telepon.</p>

            <div class="mt-6 grid gap-6 sm:grid-cols-2">
                <div>
                    <label for="birth_place" class="{{ $label }}">Tempat lahir</label>
                    <input id="birth_place" name="birth_place" type="text" class="{{ $input }}"
                           value="{{ old('birth_place', $profile->birth_place) }}">
                    @error('birth_place') <p class="{{ $error }}">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="birth_date" class="{{ $label }}">Tanggal lahir</label>
                    <input id="birth_date" name="birth_date" type="date" max="{{ now()->subDay()->toDateString() }}" class="{{ $input }}"
                           value="{{ old('birth_date', $profile->birth_date?->toDateString()) }}">
                    @error('birth_date') <p class="{{ $error }}">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="location" class="{{ $label }}">Domisili</label>
                    <input id="location" name="location" type="text" placeholder="Yogyakarta, Indonesia" class="{{ $input }}"
                           value="{{ old('location', $profile->location) }}">
                    @error('location') <p class="{{ $error }}">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="education" class="{{ $label }}">Pendidikan</label>
                    <input id="education" name="education" type="text" placeholder="S1 Informatika — Universitas ..." class="{{ $input }}"
                           value="{{ old('education', $profile->education) }}">
                    @error('education') <p class="{{ $error }}">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="email" class="{{ $label }}">Email</label>
                    <input id="email" name="email" type="email" class="{{ $input }}"
                           value="{{ old('email', $profile->email) }}">
                    @error('email') <p class="{{ $error }}">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="phone" class="{{ $label }}">Telepon / WhatsApp</label>
                    <input id="phone" name="phone" type="tel" placeholder="+62 812 3456 7890" class="{{ $input }}"
                           value="{{ old('phone', $profile->phone) }}">
                    @error('phone') <p class="{{ $error }}">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>
    </div>

    {{-- Kolom kanan: foto & aksi --}}
    <div class="space-y-6">
        <div class="rounded-2xl border border-stone-200 bg-white p-6">
            <label for="photo" class="{{ $label }}">Foto {!! $optional !!}</label>
            <p class="{{ $hint }}">JPG, PNG, atau WEBP · maks. 2 MB · disarankan potret 4:5.</p>

            <img id="photo-preview" src="{{ $profile->photo_url }}" alt="Pratinjau foto"
                 @class(['mb-3 aspect-[4/5] w-full rounded-lg object-cover', 'hidden' => ! $profile->photo_url])>

            <input id="photo" name="photo" type="file" accept="image/jpeg,image/png,image/webp"
                   class="block w-full text-sm text-stone-600 file:mr-3 file:rounded-md file:border-0 file:bg-accent-soft file:px-3 file:py-2 file:text-sm file:font-medium file:text-accent-dark hover:file:bg-accent/20">
            @error('photo') <p class="{{ $error }}">{{ $message }}</p> @enderror

            @if ($profile->photo)
                <label class="mt-4 flex items-center gap-2 text-sm text-stone-600">
                    <input type="checkbox" name="remove_photo" value="1" @checked(old('remove_photo')) class="rounded border-stone-300 accent-accent">
                    Hapus foto saat ini
                </label>
            @endif
        </div>

        <div class="flex gap-3">
            <button type="submit" class="flex-1 rounded-lg bg-ink px-4 py-2.5 font-medium text-white hover:bg-stone-800">
                {{ $profile->exists ? 'Simpan perubahan' : 'Simpan biodata' }}
            </button>
            <a href="{{ route('admin.about.show') }}" class="rounded-lg border border-stone-300 px-4 py-2.5 text-stone-700 hover:bg-white">
                Batal
            </a>
        </div>
    </div>
</div>

<script>
    // Pratinjau foto sebelum di-upload.
    document.getElementById('photo').addEventListener('change', (e) => {
        const file = e.target.files[0];
        const preview = document.getElementById('photo-preview');
        if (!file) return;
        preview.src = URL.createObjectURL(file);
        preview.classList.remove('hidden');
    });
</script>
