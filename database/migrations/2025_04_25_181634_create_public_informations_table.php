<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('public_informations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            // File Upload
            $table->string('ktp');
            $table->string('surat_pertanggungjawaban');
            $table->text('surat_permintaan');

            // Data Pribadi
            $table->string('pekerjaan');
            $table->string('npwp');

            // Permintaan Informasi
            $table->text('rincian_informasi');
            $table->text('tujuan_informasi');
            $table->string('cara_memperoleh');
            $table->string('cara_salinan');
            $table->enum('status',['Belum dibalas', 'Sudah dibalas'])->default('Belum dibalas');  // belum dibalas, sudah dibalas
            $table->string('link_balasan')->nullable(); // link balasan jika sudah dibalas
            $table->timestamp('replied_at')->nullable(); // waktu balasan jika sudah dibalas
            $table->timestamps(); // created_at & updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('public_informations');
    }
};
