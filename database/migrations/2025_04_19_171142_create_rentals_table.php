<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rentals', function (Blueprint $table) {
            $table->id();
            $table->string('rental_name');
            $table->text('description');
            $table->string('rental_type');
            $table->string('documents'); 
            $table->enum('submission_status', ['diajukan', 'disetujui', 'ditolak'])->default('diajukan');
            // Kolom tambahan
            $table->integer('area')->nullable(); // Untuk ruang/lahan (m²)
            $table->string('location')->nullable(); // Lokasi (terminal, lounge, dll.)
            $table->integer('quantity')->nullable(); // Jumlah unit (Xray, kendaraan)
            $table->string('design_file')->nullable(); // File desain untuk reklame
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rentals');
    }
};
