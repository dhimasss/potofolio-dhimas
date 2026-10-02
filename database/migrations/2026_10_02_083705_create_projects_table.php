<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();

            // Identitas proyek
            $table->string('title');
            $table->string('slug')->unique(); // dipakai di URL: /projects/{slug}

            // Path relatif di disk "public", contoh: projects/abc123.jpg
            $table->string('cover_image')->nullable();

            // Inti storytelling: masalah -> solusi
            $table->text('the_challenge');
            $table->text('the_solution');

            // Disimpan sebagai array JSON, contoh: ["Laravel","MySQL","Tailwind"]
            $table->json('tech_stack')->nullable();

            $table->string('project_url')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
