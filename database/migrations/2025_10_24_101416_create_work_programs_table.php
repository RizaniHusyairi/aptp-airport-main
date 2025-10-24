<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('work_programs', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nama Program Kerja
            // Tambahkan kolom lain jika perlu, misal: periode, penanggung jawab, dll.
            $table->timestamps();
        });
    }
};
