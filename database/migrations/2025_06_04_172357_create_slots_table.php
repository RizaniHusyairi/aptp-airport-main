<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('slots', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('aircraft_registration', 10)->index(); // Contoh: PK-ABC
            $table->string('aircraft_type', 50); // Contoh: Airbus A320
            $table->dateTime('departure_schedule');
            $table->dateTime('arrival_schedule');
            $table->string('origin_airport', 4); // Contoh: CGK
            $table->string('destination_airport', 4); // Contoh: DPS
            $table->enum('flight_type', ['penumpang', 'kargo', 'lainnya']);
            $table->string('flight_more')->nullable(); // Informasi tambahan tentang jenis penerbangan
            $table->enum('status', ['diajukan', 'disetujui', 'ditolak'])->default('diajukan'); // Status pengajuan slot: diajukan, disetujui, ditolak
            $table->string('documents'); 
            $table->text('admin_comments')->nullable(); // Catatan dari admin (misalnya, alasan penolakan)
            $table->timestamps();
            $table->softDeletes();
        });
    }
};
