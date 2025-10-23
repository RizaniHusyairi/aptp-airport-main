<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('spare_part_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Staff yang membuat permintaan
            $table->foreignId('spare_part_id')->constrained('spare_parts')->onDelete('cascade'); // Suku cadang yang diminta
            $table->string('subject'); // Perihal
            $table->text('follow_up_notes')->nullable(); // Tindak Lanjut (opsional)
            $table->string('memo_link'); // Link Nota Dinas (Google Drive)
            // Tambahkan kolom status jika diperlukan nanti
            // $table->enum('status', ['Diajukan', 'Diproses', 'Selesai', 'Ditolak'])->default('Diajukan');
            $table->timestamps();
        });
    }
};
