<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('air_freight_traffics', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->enum('type', ['Pesawat', 'Penumpang', 'Penumpang Transit', 'Bagasi', 'Kargo', 'Pos']); 
            $table->unsignedInteger('arrival'); 
            $table->unsignedInteger('departure'); 
            $table->timestamps();
        });
    }
};
