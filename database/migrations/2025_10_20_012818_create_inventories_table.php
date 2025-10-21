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
        Schema::create('inventories', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Untuk Nama Alat
            $table->enum('status', ['Baik', 'Pemeliharaan'])->default('Baik');
            $table->string('photo_path'); // Untuk Path Foto Alat
            $table->string('maintenance_report_link')->nullable();
            $table->date('input_date'); // Untuk Tanggal Penginputan
            // Kolom untuk status kondisi alat
            
            // Kolom untuk menyimpan link laporan pemeliharaan
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventories');
    }
};
