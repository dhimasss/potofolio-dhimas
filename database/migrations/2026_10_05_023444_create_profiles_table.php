<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Biodata pemilik portofolio (section "About").
     * Tabel ini hanya berisi SATU baris (singleton) — dijaga oleh AboutController.
     */
    public function up(): void
    {
        Schema::create('profiles', function (Blueprint $table) {
            $table->id();

            // Path relatif di disk media, contoh: profile/abc123.jpg
            $table->string('photo')->nullable();

            $table->string('full_name');
            $table->string('headline')->nullable(); // contoh: "Web Development & Graphic Designer"
            $table->text('bio');                    // cerita singkat tentang diri Anda

            // Biodata — semua opsional, hanya yang terisi yang tampil di publik
            $table->string('birth_place')->nullable();
            $table->date('birth_date')->nullable();
            $table->string('location')->nullable();  // domisili
            $table->string('education')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();

            // Disimpan sebagai array JSON, contoh: ["Laravel","Figma","Illustrator"]
            $table->json('skills')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('profiles');
    }
};
