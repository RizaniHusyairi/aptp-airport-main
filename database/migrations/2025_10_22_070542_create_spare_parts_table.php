<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('spare_parts', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nama Suku Cadang
            $table->unsignedInteger('stock')->default(0); // Stok
            $table->string('photo_path')->nullable(); // Path Foto
            $table->timestamps();
        });
    }
};
