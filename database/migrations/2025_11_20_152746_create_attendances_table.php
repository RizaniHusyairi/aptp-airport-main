<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('meeting_id')->constrained('meetings')->onDelete('cascade');
            $table->string('name'); // Nama Peserta
            $table->string('department'); // Instansi / Jabatan / Unit Kerja
            $table->string('phone')->nullable(); // Nomor HP (Opsional)
            $table->text('signature')->nullable(); // Nanti kita siapkan kolom ini untuk Tanda Tangan Digital (Base64 image)
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};