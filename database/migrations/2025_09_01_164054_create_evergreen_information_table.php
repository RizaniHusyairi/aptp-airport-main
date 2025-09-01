<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
          Schema::create('evergreen_information', function (Blueprint $table) {
        $table->id();
        $table->string('title'); // Untuk Judul/Uraian
        $table->date('published_date'); // Tanggal Publikasi
        $table->text('document_path'); // Path ke file PDF
        $table->timestamps();
    });
    }
};
