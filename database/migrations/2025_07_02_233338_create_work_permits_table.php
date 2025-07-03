<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('work_permits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('work_type'); // e.g., Pekerjaan Panas, Ketinggian, Listrik
            $table->string('location');
            $table->text('description');
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->json('workers'); // Menyimpan daftar nama pekerja
            $table->json('equipment'); // Menyimpan daftar peralatan
            $table->json('documents'); // Menyimpan path file-file
            $table->enum('status', ['Diajukan', 'Disetujui', 'Ditolak', 'Revisi Diperlukan'])->default('Diajukan');
            $table->text('staff_notes')->nullable();
            $table->timestamps();
        });
    }
};
