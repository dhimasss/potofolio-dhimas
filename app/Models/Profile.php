<?php

namespace App\Models;

use App\Models\Concerns\UsesMediaDisk;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * Biodata pemilik portofolio — ditampilkan di section "About" dan dikelola di /admin/about.
 * Hanya ada satu baris; ambil dengan Profile::current().
 */
class Profile extends Model
{
    use UsesMediaDisk;

    protected $fillable = [
        'photo',
        'full_name',
        'headline',
        'bio',
        'birth_place',
        'birth_date',
        'location',
        'education',
        'email',
        'phone',
        'skills',
    ];

    protected function casts(): array
    {
        return [
            'birth_date' => 'date',
            'skills' => 'array',
        ];
    }

    protected static function booted(): void
    {
        // Foto ikut terhapus dari storage saat biodata dihapus.
        static::deleted(fn (Profile $profile) => static::deleteMedia($profile->photo));
    }

    /**
     * Satu-satunya biodata (atau null bila belum dibuat / sudah dihapus).
     */
    public static function current(): ?self
    {
        return static::query()->oldest('id')->first();
    }

    /**
     * $profile->photo_url -> URL publik foto (atau null).
     */
    protected function photoUrl(): Attribute
    {
        return Attribute::get(fn () => static::mediaUrl($this->photo));
    }

    /**
     * $profile->initials -> "DJ" untuk placeholder bila belum ada foto.
     */
    protected function initials(): Attribute
    {
        return Attribute::get(fn () => Str::of($this->full_name)
            ->explode(' ')
            ->filter()
            ->take(2)
            ->map(fn ($word) => Str::upper(Str::substr($word, 0, 1)))
            ->implode(''));
    }

    /**
     * $profile->birth_info -> "Jakarta, 29 Juli 2000" (bagian yang kosong dilewati).
     */
    protected function birthInfo(): Attribute
    {
        return Attribute::get(fn () => collect([
            $this->birth_place,
            $this->birth_date?->translatedFormat('d F Y'),
        ])->filter()->implode(', ') ?: null);
    }

    /**
     * Daftar biodata yang terisi saja, siap ditampilkan sebagai label => nilai.
     */
    public function details(): array
    {
        return array_filter([
            'Tempat, tanggal lahir' => $this->birth_info,
            'Domisili' => $this->location,
            'Pendidikan' => $this->education,
            'Email' => $this->email,
            'Telepon' => $this->phone,
        ]);
    }
}
