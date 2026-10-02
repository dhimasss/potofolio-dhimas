<?php

namespace Database\Seeders;

use App\Models\Project;
use Illuminate\Database\Seeder;

/**
 * Data CONTOH untuk melihat tampilan publik. Tidak dipanggil otomatis oleh DatabaseSeeder.
 * Jalankan manual:  php artisan db:seed --class=ProjectSeeder
 * Hapus lewat admin panel bila sudah punya proyek sungguhan.
 */
class ProjectSeeder extends Seeder
{
    public function run(): void
    {
        $projects = [
            [
                'title' => 'Sistem Kasir untuk Warung Kopi Lokal',
                'the_challenge' => "Pemilik warung mencatat setiap transaksi di buku tulis. Di akhir hari, ia butuh hampir satu jam untuk menghitung omzet — dan sering kali angkanya tidak cocok dengan uang di laci.\n\nMasalah sebenarnya bukan sekadar pencatatan, tetapi rasa tidak yakin: apakah usahanya untung atau rugi bulan ini?",
                'the_solution' => "Saya membangun aplikasi kasir berbasis web yang bisa dipakai dari tablet murah. Fokusnya satu: mencatat pesanan dalam tiga ketukan.\n\nLaporan harian dibuat otomatis, sehingga waktu tutup kasir turun dari satu jam menjadi lima menit. Pemilik kini bisa melihat menu mana yang paling laku dan kapan jam tersibuk.",
                'tech_stack' => ['Laravel', 'MySQL', 'Tailwind CSS', 'Alpine.js'],
                'project_url' => 'https://github.com/username/kasir-warung',
            ],
            [
                'title' => 'Portal Absensi Sekolah',
                'the_challenge' => "Guru menghabiskan sepuluh menit pertama setiap kelas untuk memanggil nama satu per satu. Rekap bulanan dikerjakan manual di spreadsheet dan sering terlambat sampai ke orang tua.",
                'the_solution' => "Absensi dilakukan dengan memindai kode QR di kartu siswa. Data langsung masuk ke dashboard, dan orang tua menerima ringkasan mingguan.\n\nGuru mendapatkan kembali waktu mengajarnya, dan sekolah punya data kehadiran yang akurat.",
                'tech_stack' => ['Laravel', 'MySQL', 'Livewire'],
                'project_url' => null,
            ],
            [
                'title' => 'Katalog Online UMKM Kerajinan',
                'the_challenge' => "Pengrajin di desa memiliki produk berkualitas, tetapi hanya dikenal di pasar lokal. Mereka tidak punya waktu maupun pengetahuan untuk mengelola toko online yang rumit.",
                'the_solution' => "Saya merancang katalog sederhana yang bisa diperbarui lewat ponsel, dengan tombol pesan langsung ke WhatsApp. Tanpa keranjang belanja, tanpa kerumitan.\n\nDalam tiga bulan pertama, pesanan dari luar kota mulai berdatangan.",
                'tech_stack' => ['Laravel', 'MySQL', 'Tailwind CSS'],
                'project_url' => 'https://example.com',
            ],
        ];

        foreach ($projects as $data) {
            Project::firstOrCreate(['title' => $data['title']], $data);
        }
    }
}
