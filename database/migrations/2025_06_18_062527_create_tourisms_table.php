<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('tourisms', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique(); // Untuk URL yang bersih, contoh: /pariwisata/masjid-islamic-center
            $table->string('category'); // Kategori: Alam, Budaya, Religi, Kuliner
            $table->string('cover_image'); // Path ke gambar utama
            $table->json('gallery')->nullable(); // Menyimpan daftar path gambar galeri dalam format JSON
            $table->text('short_desc'); // Deskripsi singkat untuk kartu di halaman daftar
            $table->longText('description'); // Deskripsi lengkap untuk halaman detail
            $table->text('address'); // Alamat lengkap destinasi
            $table->text('gmaps_url')->nullable(); // URL untuk embed Google Maps
            $table->enum('status', ['published', 'draft'])->default('published'); // Status untuk publikasi
            $table->timestamps(); // Membuat kolom created_at dan updated_at
        });
    }
};
