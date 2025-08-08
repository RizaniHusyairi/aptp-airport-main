<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('persuratans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->comment('User pembuat surat');
            $table->unsignedBigInteger('assigned_to_user_id')->nullable()->comment('User penanggung jawab saat ini');
            
            $table->string('letter_number')->unique()->nullable();
            $table->string('title');
            $table->text('content_preview')->nullable();
            $table->string('file_path');
            
            $table->enum('status', [
                'Draft', 
                'Menunggu Persetujuan Kasi', 
                'Menunggu Persetujuan Kasubbag', 
                'Menunggu Persetujuan Kabandara', 
                'Revisi Diperlukan', 
                'Disetujui',
                'Ditolak'
            ])->default('Draft');

            $table->timestamps();
        });


    }
};
