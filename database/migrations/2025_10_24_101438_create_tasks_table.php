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
            $table->boolean('is_completed')->default(false); // Status Selesai (true/false)
            $table->timestamps();
        });
    }
};
