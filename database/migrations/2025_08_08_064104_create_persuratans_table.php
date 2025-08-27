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
            $table->foreignId('user_id');
            $table->unsignedBigInteger('assigned_to_user_id')->nullable();
            
            // Tambahkan kolom baru sesuai formulir
            $table->string('letter_type');
            $table->date('letter_date');
            $table->text('recipient_address'); // Tujuan Alamat Surat
            $table->string('subject'); // Perihal
            
            $table->foreignId('final_approver_id')->constrained('users'); // Pejabat Final
            
            $table->json('collaborators')->nullable(); // Dikerjakan bersama (menyimpan array user_id)
            $table->json('attachments'); // Dokumen Konsep Surat (menyimpan array path file)
            
           
            $table->timestamps();
        });


    }
};
