<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('service_standards', function (Blueprint $table) {
            $table->id();
            $table->string('type', 100); // Kunci pengelompokan, cth: "Standar Pelayanan"
            $table->string('title');
            $table->string('document_number', 150)->nullable(); // Nomor SK penetapan
            $table->text('description')->nullable();
            $table->string('file_path')->nullable();    // Terisi jika dokumen diunggah
            $table->text('document_link')->nullable();  // Terisi jika dokumen berupa tautan
            $table->date('published_date');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('service_standards');
    }
};
