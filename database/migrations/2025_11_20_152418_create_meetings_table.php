<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('meetings', function (Blueprint $table) {
            $table->id();
            $table->string('title'); // Judul Rapat
            $table->string('slug')->unique(); // Link unik (misal: rapat-koordinasi-agustus)
            $table->date('date'); // Tanggal Rapat
            $table->time('start_time'); // Jam Mulai
            $table->string('location'); // Lokasi Rapat
            $table->string('organizer'); // Pimpinan Rapat / Penyelenggara
            $table->string('organizer_nip')->nullable()
            $table->boolean('is_active')->default(true) // Status apakah form absensi masih bisa diisi
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Staff pembuat
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('meetings');
    }
};