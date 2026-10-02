<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use RuntimeException;

class AdminUserSeeder extends Seeder
{
    /**
     * Membuat (atau memperbarui) satu akun admin dari nilai ADMIN_* di .env.
     * Tidak ada halaman register publik, jadi inilah satu-satunya cara membuat admin.
     */
    public function run(): void
    {
        $admin = config('app.admin');

        if (blank($admin['email']) || blank($admin['password'])) {
            throw new RuntimeException('Isi ADMIN_EMAIL dan ADMIN_PASSWORD di file .env terlebih dahulu.');
        }

        // Password otomatis di-hash oleh cast 'hashed' di model User.
        User::updateOrCreate(
            ['email' => $admin['email']],
            ['name' => $admin['name'], 'password' => $admin['password']],
        );

        $this->command?->info("Admin siap: {$admin['email']}");
    }
}
