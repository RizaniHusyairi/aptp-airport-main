<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('nataru_events', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Contoh: "Posko Nataru 2024/2025"
            $table->date('start_date'); // Tanggal awal posko
            $table->date('end_date');   // Tanggal akhir posko
            $table->string('public_token')->unique(); // Token unik untuk link publik
            $table->boolean('is_active')->default(true); // Status aktif/tidak
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('nataru_events');
    }
};
