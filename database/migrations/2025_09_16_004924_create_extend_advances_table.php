<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('extend_advances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // ID Pengaju

            // Bagian I: Pesawat Udara
            $table->string('operator');
            $table->string('aircraft_type');
            $table->string('registration_and_flight_number');

            // Bagian II: Penerbangan
            $table->date('flight_date');
            $table->time('eobt'); // Jam Keberangkatan
            $table->time('aobt'); // Jam Kedatangan
            $table->string('route');
            $table->string('take_off_alternate')->nullable();
            $table->string('purpose_of_flight');

            // Bagian III: Pernyataan & Status
            $table->string('pic_name'); // Nama Pilot in Command
            
            // Kolom status untuk tracking oleh staff
            $table->enum('submission_status', ['Diajukan', 'Disetujui', 'Ditolak', 'Revisi Diperlukan'])->default('Diajukan');
            $table->text('staff_notes')->nullable();
            $table->string('reply_document_path')->nullable();

            

            $table->timestamps();
        });
    }
};
