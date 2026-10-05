<?php

namespace App\Models\Concerns;

use Illuminate\Support\Facades\Storage;

/**
 * Untuk model yang menyimpan file gambar (cover proyek, foto profil).
 * Disk-nya diatur lewat MEDIA_DISK: "public" di lokal, "s3" (R2) di Vercel.
 */
trait UsesMediaDisk
{
    public static function mediaDisk(): string
    {
        return config('filesystems.media');
    }

    /**
     * URL publik sebuah file di disk media, atau null bila path kosong.
     */
    protected static function mediaUrl(?string $path): ?string
    {
        return $path ? Storage::disk(static::mediaDisk())->url($path) : null;
    }

    protected static function deleteMedia(?string $path): void
    {
        if ($path) {
            Storage::disk(static::mediaDisk())->delete($path);
        }
    }
}
