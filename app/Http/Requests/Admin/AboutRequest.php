<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Validasi biodata (create & update) untuk /admin/about.
 */
class AboutRequest extends FormRequest
{
    /**
     * Akses sudah dijaga middleware "auth" di routes.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'full_name' => ['required', 'string', 'max:255'],
            'headline' => ['nullable', 'string', 'max:255'],
            'bio' => ['required', 'string', 'max:5000'],
            'photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'remove_photo' => ['nullable', 'boolean'],
            'birth_place' => ['nullable', 'string', 'max:100'],
            'birth_date' => ['nullable', 'date', 'before:today'],
            'location' => ['nullable', 'string', 'max:255'],
            'education' => ['nullable', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:30', 'regex:/^[0-9+()\-\s]+$/'],
            'skills' => ['nullable', 'string', 'max:500'], // "Laravel, Figma, Illustrator"
        ];
    }

    public function attributes(): array
    {
        return [
            'full_name' => 'nama lengkap',
            'headline' => 'headline',
            'bio' => 'bio',
            'photo' => 'foto',
            'birth_place' => 'tempat lahir',
            'birth_date' => 'tanggal lahir',
            'location' => 'domisili',
            'education' => 'pendidikan',
            'email' => 'email',
            'phone' => 'nomor telepon',
            'skills' => 'skills',
        ];
    }

    public function messages(): array
    {
        return [
            'required' => ':Attribute wajib diisi.',
            'image' => ':Attribute harus berupa gambar.',
            'mimes' => ':Attribute harus berformat: :values.',
            'photo.max' => ':Attribute maksimal 2 MB.',
            'max' => ':Attribute maksimal :max karakter.',
            'email' => ':Attribute harus berupa alamat email yang valid.',
            'date' => ':Attribute harus berupa tanggal yang valid.',
            'birth_date.before' => ':Attribute harus sebelum hari ini.',
            'phone.regex' => ':Attribute hanya boleh berisi angka, spasi, +, -, dan tanda kurung.',
        ];
    }

    /**
     * Data siap simpan: skills "A, B, C" -> ["A","B","C"].
     * photo & remove_photo diproses terpisah di controller.
     */
    public function profileData(): array
    {
        $data = $this->safe()->except(['photo', 'remove_photo']);

        $data['skills'] = collect(explode(',', $data['skills'] ?? ''))
            ->map(fn ($skill) => trim($skill))
            ->filter()
            ->unique()
            ->values()
            ->all();

        return $data;
    }
}
