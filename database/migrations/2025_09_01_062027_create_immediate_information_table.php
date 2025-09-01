<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('immediate_information', function (Blueprint $table) {
            $table->id();
            $table->text('uraian'); // Deskripsi atau topik utama
            $table->text('keterangan'); // Penjelasan lebih detail
            $table->string('link_url'); // URL tujuan
            $table->string('link_text')->default('Lihat Detail'); // Teks untuk tombol/link
            $table->timestamps();
        });
    }
};
