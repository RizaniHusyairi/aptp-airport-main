<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {

        Schema::create('nataru_flights', function (Blueprint $table) {
            $table->id();
            
            // Relasi ke Event (PENTING)
            $table->foreignId('nataru_event_id')->constrained('nataru_events')->onDelete('cascade');
            
            // Data Waktu
            $table->date('flight_date'); 
            $table->time('flight_time');

            // Identitas Penerbangan
            $table->string('airline'); // Maskapai
            $table->string('flight_number'); // Kode Penerbangan (ID-6257)
            $table->string('status_flight'); // Berjadwal, Perintis, Tidak Berjadwal
            
            // Rute
            $table->string('route'); // Destination (From-To)
            $table->enum('direction', ['arrival', 'departure']); // Arah

            // Data Pesawat (Opsional tapi ada di Excel)
            $table->string('aircraft_type')->nullable(); 
            $table->string('aircraft_registration')->nullable(); 

            // Muatan
            $table->integer('pax_adult')->default(0);
            $table->integer('pax_child')->default(0);
            $table->integer('pax_infant')->default(0);
            $table->integer('pax_total')->default(0);
            $table->integer('cargo')->default(0); // Kg
            $table->integer('baggage')->default(0); // Kg
            $table->decimal('load_factor', 5, 2)->nullable(); // Persentase Load Factor

            // Ekonomi
            $table->decimal('ticket_price_high', 15, 2)->nullable(); 
            $table->decimal('ticket_price_low', 15, 2)->nullable(); 

            // Petugas (String manual karena input via link publik)
            $table->string('officer_name'); 
            
            // User ID (Nullable, terisi jika yang input adalah Staff login, null jika via link publik)
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');

            $table->text('remarks')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('nataru_flights');
    }
};