<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('services', function (Blueprint $table) {
        $table->id();
        $table->string('name'); // Contoh: "Tenant", "Sewa"
        $table->string('slug')->unique(); // Contoh: "tenant", "sewa"
        $table->string('title'); // Contoh: "Syarat & Ketentuan Pengajuan Tenant"
        $table->json('requirements')->nullable(); // Untuk menyimpan daftar dokumen/syarat
        $table->json('steps')->nullable(); // Untuk menyimpan langkah-langkah pendaftaran
        $table->boolean('has_pricing')->default(false); // Penanda jika ada info harga
        $table->json('pricing_info')->nullable(); // Untuk menyimpan detail harga
        $table->string('submission_url'); // URL untuk tombol "Ajukan Sekarang"
        $table->boolean('is_active')->default(true); // Untuk menampilkan/menyembunyikan dari menu
        $table->timestamps();
    });
    }
};
