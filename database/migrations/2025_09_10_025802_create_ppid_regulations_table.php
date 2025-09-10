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
        Schema::create('ppid_regulations', function (Blueprint $table) {
            $table->id();
            
            // Kolom ENUM untuk memastikan kategori selalu salah satu dari tiga pilihan ini
            $table->enum('category', [
                'Peraturan Undang-undang',
                'Peraturan Komisi Informasi Pusat',
                'Peraturan Kementrian Perhubungan Terkait Keterbukaan Informasi Publik'
            ]);

            $table->text('title');
            $table->text('document_link'); // Menggunakan TEXT untuk URL yang panjang
            $table->date('published_date')->nullable(); // Tanggal terbit (opsional)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ppid_regulations');
    }
};