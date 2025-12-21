<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('ojt_students', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('id_number'); // KTP/KTM
            $table->string('birth_place');
            $table->date('birth_date');
            $table->text('address');
            $table->string('institution'); // Asal Sekolah/Kampus
            $table->string('major'); // Jurusan

            // Data OJT
            $table->string('duration'); // Lama OJT (misal: 3 Bulan)
            $table->date('start_date');
            $table->date('end_date');
            $table->string('status')->default('Menunggu Verifikasi');

            // JSON Columns (Array)
            $table->json('supervisors'); // Bisa lebih dari 1 pembimbing
            $table->json('work_units');  // Checkbox pilihan unit



            $table->string('phone_number');

            // File Paths
            $table->string('identity_card_path')->nullable(); // Foto KTP
            $table->string('photo_path')->nullable(); // Pas Foto 4x6

            $table->string('final_certificate_path')->nullable();

            $table->json('grades')->nullable(); // Menyimpan detail penilaian (Nama Komponen & Nilai)
            $table->decimal('average_score', 5, 2)->nullable(); // Nilai Rata-rata
            $table->string('predicate')->nullable(); // "Sangat Memuaskan", "Baik", dll
            $table->string('letter_grade')->nullable(); // "A", "B", "C"

        
            // Catatan dari staff jika ada revisi/penolakan
            $table->text('staff_notes')->nullable();

            $table->timestamps();
        });
    }
};
