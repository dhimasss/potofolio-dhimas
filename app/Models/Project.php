<?php

namespace App\Models;

use App\Models\Concerns\UsesMediaDisk;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Project extends Model
{
    use UsesMediaDisk;

    /**
     * Kolom yang boleh diisi lewat mass assignment (Project::create($data)).
     * `slug` sengaja tidak dimasukkan karena dibuat otomatis dari `title`.
     */
    protected $fillable = [
        'title',
        'cover_image',
        'the_challenge',
        'the_solution',
        'tech_stack',
        'project_url',
    ];

    /**
     * `tech_stack` otomatis dikonversi JSON <-> array PHP.
     */
    protected function casts(): array
    {
        return [
            'tech_stack' => 'array',
        ];
    }

    /**
     * Route model binding memakai slug, bukan id.
     * Jadi route('projects.show', $project) menghasilkan /projects/nama-proyek.
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /**
     * Model events:
     * - saving  : buat slug unik dari title (saat create, atau saat title berubah)
     * - deleted : hapus file cover dari storage agar tidak menjadi file yatim
     */
    protected static function booted(): void
    {
        static::saving(function (Project $project) {
            if (blank($project->slug) || $project->isDirty('title')) {
                $project->slug = static::uniqueSlug($project->title, $project->id);
            }
        });

        static::deleted(fn (Project $project) => static::deleteMedia($project->cover_image));
    }

    /**
     * Menghasilkan slug unik: "my-app", "my-app-2", "my-app-3", ...
     */
    protected static function uniqueSlug(string $title, ?int $ignoreId = null): string
    {
        $base = Str::slug($title) ?: 'project';
        $slug = $base;
        $i = 2;

        while (static::where('slug', $slug)
            ->when($ignoreId, fn ($q) => $q->whereKeyNot($ignoreId))
            ->exists()) {
            $slug = "{$base}-{$i}";
            $i++;
        }

        return $slug;
    }

    /**
     * Accessor: $project->cover_image_url -> URL publik gambar (atau null).
     */
    protected function coverImageUrl(): Attribute
    {
        return Attribute::get(
            fn () => static::mediaUrl($this->cover_image)
        );
    }
}
