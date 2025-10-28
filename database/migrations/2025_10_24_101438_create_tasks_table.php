<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
         Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('work_program_id')->constrained('work_programs')->onDelete('cascade'); // Relasi ke Program Kerja
            $table->text('description'); // Deskripsi Tugas
            $table->enum('status', [
                'Belum Selesai',
                'Menunggu Verifikasi',
                'Diverifikasi',
                'Revisi Diperlukan'
            ])->default('Belum Selesai');
            $table->string('supporting_document_link')->nullable();

            // 3. Tambahkan kolom untuk ID verifikator (Kanit)
            $table->foreignId('verifier_id')->nullable()->after('supporting_document_link')
                  ->constrained('users')->nullOnDelete();

            $table->text('verification_notes')->nullable()->after('verifier_id');

            $table->timestamps();
        });
    }
};
