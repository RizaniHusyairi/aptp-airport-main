<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('surat_verifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('persuratan_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->comment('Pejabat yang memverifikasi');
            $table->enum('status', ['Menunggu', 'Disetujui', 'Ditolak'])->default('Menunggu');
            $table->integer('order')->default(1)->comment('Urutan verifikasi');
            $table->text('comments')->nullable();
            $table->timestamps();
        });
    }
};
