<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Validasi untuk create (POST) & update (PUT) project.
 * Controller hanya menerima data yang sudah lolos dari sini.
 */
class ProjectRequest extends FormRequest
{
    /**
     * Akses sudah dijaga middleware "auth" di routes, jadi cukup true.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        // Saat create cover wajib; saat update boleh kosong (artinya: pakai gambar lama).
        $isCreate = $this->isMethod('post');

        return [
            'title' => ['required', 'string', 'max:255'],
            'cover_image' => [
                $isCreate ? 'required' : 'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048', // KB = 2 MB
            ],
            'the_challenge' => ['required', 'string'],
            'the_solution' => ['required', 'string'],
            'tech_stack' => ['nullable', 'string', 'max:500'], // "Laravel, MySQL, Tailwind"
            'project_url' => ['nullable', 'url', 'max:255'],
        ];
    }

    /**
     * Nama field yang tampil di pesan error.
     */
    public function attributes(): array
    {
        return [
            'title' => 'judul',
            'cover_image' => 'gambar cover',
            'the_challenge' => 'tantangan',
            'the_solution' => 'solusi',
            'tech_stack' => 'tech stack',
            'project_url' => 'URL proyek',
        ];
    }

    public function messages(): array
    {
        return [
            'required' => ':Attribute wajib diisi.',
            'image' => ':Attribute harus berupa gambar.',
            'mimes' => ':Attribute harus berformat: :values.',
            'cover_image.max' => ':Attribute maksimal 2 MB.',
            'max' => ':Attribute maksimal :max karakter.',
            'url' => ':Attribute harus berupa URL lengkap (contoh: https://github.com/...).',
        ];
    }

    /**
     * Data siap simpan: tech_stack diubah dari teks "A, B, C" menjadi array ["A","B","C"].
     * cover_image tidak ikut, karena file diproses terpisah di controller.
     */
    public function projectData(): array
    {
        $data = $this->safe()->except('cover_image');

        $data['tech_stack'] = collect(explode(',', $data['tech_stack'] ?? ''))
            ->map(fn ($tech) => trim($tech))
            ->filter()
            ->unique()
            ->values()
            ->all();

        return $data;
    }
}
