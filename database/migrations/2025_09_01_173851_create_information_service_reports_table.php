<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('information_service_reports', function (Blueprint $table) {
        $table->id();
        $table->string('title'); // Untuk 'Judul Laporan'
        $table->year('publication_year'); // Untuk 'Tahun Publikasi'
        $table->text('document_link'); // Untuk link drive dokumen
        $table->timestamps();
    });
    }
};
