<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('air_traffic_logs', function (Blueprint $table) {
            $table->id();
            $table->date('date')->unique(); // Hanya ada 1 entri per tanggal

            // Data Pesawat
            $table->unsignedInteger('aircraft_arrival')->default(0);
            $table->unsignedInteger('aircraft_departure')->default(0);

            // Data Penumpang
            $table->unsignedInteger('passenger_arrival')->default(0);
            $table->unsignedInteger('passenger_departure')->default(0);

            // Data Bagasi (dalam KG)
            $table->unsignedInteger('baggage_arrival')->default(0);
            $table->unsignedInteger('baggage_departure')->default(0);

            // Data Kargo (dalam KG)
            $table->unsignedInteger('cargo_arrival')->default(0);
            $table->unsignedInteger('cargo_departure')->default(0);

            // Jika Penumpang Transit & Pos masih diperlukan, tambahkan di sini
            // $table->unsignedInteger('transit_arrival')->default(0);
            // $table->unsignedInteger('transit_departure')->default(0);
            // $table->unsignedInteger('mail_arrival')->default(0);
            // $table->unsignedInteger('mail_departure')->default(0);

            $table->timestamps();
        });
    }
};
